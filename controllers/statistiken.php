<?php

/**
 * File - description
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License
 * version 3 as published by the Free Software Foundation.
 *
 * @author      Annelene Sudau
 * @license     https://www.gnu.org/licenses/agpl-3.0.html AGPL version 3
 */

class StatistikenController extends StudipController
{
    
    public function __construct($dispatcher)
    {
        parent::__construct($dispatcher);
        $this->plugin = $dispatcher->plugin;
    }
    
    
    function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        Navigation::activateItem('/admin/ohn-statistiken');
        
        PageLayout::addScript($this->plugin->getPluginURL() . '/assets/Chart.js');
    }
    
    function index_action(){
        Navigation::activateItem('/admin/ohn-statistiken/statistics');
        //$this->datafields = DataField::findBySQL('object_type = :user AND name NOT IN (\'Hinweise\', \'Gruende\')', [':user' => 'user']);
        $this->datafields = $this->parseRegistrationFormFields();
        
        $mooc_userdomain = new UserDomain('mooc-user');
        $this->mooc_user = $mooc_userdomain->getUsers();
        $this->mooc_user18 = [];
     
        foreach($this->mooc_user as $user_id){
            $usermodel = User::find($user_id);
            if($usermodel->mkdate > 1514764800) {
                $this->mooc_user18[] = $user_id;
            } 

        }
        
        $this->data = [];
        
        foreach($this->datafields as $field){
            if ($field['fieldName'] != 'geschlecht'){
                $data_fieldentries = [];
                foreach ($field['choices'] as $choice){
                    $entries = DatafieldEntryModel::findBySQL('mkdate > 1514764800 AND datafield_id = :datafield_id AND content = :choice', [':datafield_id' => $field['fieldName'], ':choice' => trim($choice)]);
                    $data_fieldentries[trim($choice)] = sizeof($entries);
                }
                $this->data[$field['fieldName']] = $data_fieldentries;
            } else {
                
            }
        }
        
    }
    
    function courses_action(){
        Navigation::activateItem('/admin/ohn-statistiken/course');
        $this->courses = self::getOHN_courses();
        
        $this->datafields = DataField::findBySQL('object_type = :user', [':user' => 'user']);
        $mooc_userdomain = new UserDomain('mooc-user');
        $this->mooc_user = $mooc_userdomain->getUsers();
        $this->users = [];
        $this->coursemembers = [];
        
        foreach($this->mooc_user as $user){
            foreach($this->datafields as $field){
                $entry = DatafieldEntryModel::findOneBySQL('range_id = :user_id AND datafield_id = :datafield_id', [':user_id' => $user, ':datafield_id' => $field->datafield_id]);
                $this->users[$user][] = $entry->content;
                
            }
        }
        
        foreach ($this->courses as $course_entry){
            $course = Course::find($course_entry['Seminar_id']);
            $this->coursemembers[$course->id] = sizeof($course->getMembersWithStatus('autor'));
        }
    }
    
    function all_action(){
        Navigation::activateItem('/admin/ohn-statistiken/all');
        $mooc_userdomain = new UserDomain('mooc-user');
        $this->mooc_user = $mooc_userdomain->getUsers();
        $this->mooc_user18 = [];
        $this->mooc_user17 = [];
        
        $this->kursteilnahmen_gesamt18_all = 0;
        $this->kursteilnahmen_gesamt18 = [];
        $this->kursteilnahmen_gesamt18[1] = 0;
        $this->kursteilnahmen_gesamt18[2] = 0;
        $this->kursteilnahmen_gesamt18[3] = 0;
        $this->kursteilnahmen_gesamt18[4] = 0;
        $this->kursteilnahmen_gesamt17_all = 0;
        $this->kursteilnahmen_gesamt17 = [];
        $this->kursteilnahmen_gesamt17[1] = 0;
        $this->kursteilnahmen_gesamt17[2] = 0;
        $this->kursteilnahmen_gesamt17[3] = 0;
        $this->kursteilnahmen_gesamt17[4] = 0;
        
        
        foreach($this->mooc_user as $user_id){
            $usermodel = User::find($user_id);
            $kursteilnahmen = sizeof($usermodel->course_memberships);
            if($usermodel->mkdate > 1514764800) {
                $this->kursteilnahmen_gesamt18_all += $kursteilnahmen;
                $this->kursteilnahmen_gesamt18[$kursteilnahmen] ++;
            } else if ($usermodel->mkdate > 1483228800){
                $this->kursteilnahmen_gesamt17_all += $kursteilnahmen;
                $this->kursteilnahmen_gesamt17[$kursteilnahmen] ++;
            }
        
        }
        ksort($this->kursteilnahmen_gesamt18);
        ksort($this->kursteilnahmen_gesamt17);
        
        
        foreach($this->mooc_user as $user_id){
            $usermodel = User::find($user_id);
            if($usermodel->mkdate > 1514764800) {
                $this->mooc_user18[] = $user_id;
            } else if ($usermodel->mkdate > 1483228800){
                $this->mooc_user17[] = $user_id;
            }
        
        }
    }
    
    public function url_for($to)
    {
        $args = func_get_args();

        # find params
        $params = array();
        if (is_array(end($args))) {
            $params = array_pop($args);
        }

        # urlencode all but the first argument
        $args = array_map('urlencode', $args);
        $args[0] = $to;

        return PluginEngine::getURL($this->dispatcher->plugin, $params, join('/', $args));
    }
    
    public static function getOHN_courses(){
        $course_number_prefixe = ['AM1' => 'Allgemeiner Vorbereitungskurs Mathematik',
            'BS1' => 'Vom Beruf ins Studium', 
            'HS1' => 'Handwerkszeug Studieren',
            'MI1' => 'Mathematik f�r Informatik',
            'MING1' => 'Mathematik f�r Ingenieure',
            'MW1' => 'Mathematik f�r Wirtschaftswissenschaften',
            'ZSM1' => 'Zeit- und Selbstmanagement'];
        
        $db         = DBManager::get();
        $return_arr = [];
        $query      = "SELECT Seminar_id, VeranstaltungsNummer, Name FROM seminare WHERE VeranstaltungsNummer RLIKE (:prefixe) ORDER BY start_time DESC";
        $statement  = $db->prepare($query);
        $statement->execute([':prefixe' => implode('|', array_keys($course_number_prefixe))]);
        $courses = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $courses;
    }
    
    public function parseRegistrationFormFields()
    {
        $fields = explode("\n", Config::get()->getValue(\Mooc\REGISTRATION_FORM_CONFIG_ID));
        $parsedFields = array();
        $fieldNameMap = array(
            'firstname' => 'vorname',
            'lastname' => 'nachname',
            'email' => 'mail',
            'birthday' => 'geburtsdatum',
            'sex' => 'geschlecht',
           // 'terms_of_service' => 'accept_tos',
        );

        foreach ($fields as $field) {
            if (substr($field, 0, 6) === 'field:') {
                $field = trim($field);
                $separatorPos = strpos($field, '|');
                $required = false;
                $label = null;
                $choices = null;

                // field name and label are separated by a pipe character
                if ($separatorPos !== false) {
                    $label = substr($field, $separatorPos + 1);
                    $fieldName = substr($field, 6, $separatorPos - 6);
                } else {
                    $fieldName = substr($field, 6);
                }

                // the field is required if its name ends with an asterisk character
                if (substr($fieldName, -1) === '*') {
                    $fieldName = substr($fieldName, 0, -1);
                    $required = true;
                }

                // map configured field names to user properties
                if (isset($fieldNameMap[$fieldName])) {
                    $fieldName = $fieldNameMap[$fieldName];
                    $fieldType = 'text';
                } elseif ($this->isDataFieldFormField($fieldName)) {
                    $dataField = new \DataField($fieldName);
                    $fieldType = $dataField->type;

                    if ($dataField->type === 'selectbox' || $dataField->type === 'selectboxmultiple') {
                        $choices = explode("\n", $dataField->typeparam);
                    }
                } elseif ($fieldName !== 'terms_of_service') {
                    // skip the field if it is not recognised
                    continue;
                }

                $parsedFields[] = array(
                    'fieldName' => $fieldName,
                    'label' => $label,
                    'required' => $required,
                    'choices' => $choices,
                    'type' => $fieldType,
                );
            } else {
                $parsedFields[] = $field;
            }
        }

        return $parsedFields;
    }
    
    private function isDataFieldFormField($fieldName)
    {
        return preg_match('/^[a-z0-9]{32}$/i', $fieldName);
    }
    
}