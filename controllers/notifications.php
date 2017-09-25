<?php

class NotificationsController extends StudipController
{
    function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
        Navigation::activateItem('course/notifications');
    }

    

    public function index_action()
    {   
        
        //Kontextaktionen
        $actions = new ActionsWidget();
        $actions->setTitle(_('Aktionen'));
        $actions->addLink(
        'Neue Benachrichtigung anlegen',
        $this->url_for('notifications/edit'),'icons/16/blue/add.png'); 
        Sidebar::get()->addWidget($actions);
        
        
        $this->course_id = Course::findCurrent()->id;
        $db = DBManager::get();
        
        $res = $db->query("SELECT * FROM mail_notifications WHERE course_id LIKE '". $this->course_id . "'");
        $entries = $res->fetchAll(PDO::FETCH_ASSOC);
        $this->planned_mails = $entries;
    }
    
    
     public function edit_action($entry_id = NULL)
    {   
        
        //Kontextaktionen
        $actions = new ActionsWidget();
        $actions->setTitle(_('Aktionen'));
        $actions->addLink(
        'Abbrechen',
        $this->url_for('notifications/'),''); 
        Sidebar::get()->addWidget($actions);
        
        $this->entry_id = $entry_id;
        $this->entry = OHNMailEntry::find($entry_id);
        
        
    }
    
       public function save_action($entry_id = NULL)
    {   

        if($this->entry = OHNMailEntry::find($entry_id)){
            
            foreach($_POST as $key => $value){
                if (is_array($value)){
                    $value = implode(", ", $value);
                }
                
                    try {
                    $this->entry->setValue($key, $value);
                    } catch (Exception $e){}
                
            }
            if ($_POST['delay'] == 'before'){
                $this->entry->days_from_start = '-' . $_POST['days_from_start'];
            }

            $this->entry->course_id  = Course::findCurrent()->id;
            $this->entry->store();
            PageLayout::postMessage(MessageBox::success(_('Die Änderungen wurden gespeichert.')));
        } else {
            $this->entry = new OHNMailEntry();
            foreach($_POST as $key => $value){
                if (is_array($value)){
                    $value = implode(", ", $value);
                }
                
                    try {
                    $this->entry->setValue($key, $value);
                    } catch (Exception $e){}
                
            }
            if ($_POST['delay'] == 'before'){
                $this->entry->days_from_start = '-' . $_POST['days_from_start'];
            }

            $this->entry->course_id  = Course::findCurrent()->id;
            $this->entry->store();
            PageLayout::postMessage(MessageBox::success(_('Die geplante Mailbenachrichtigung wurde angelegt.')));
        }
        
        $this->redirect($this->url_for('/notifications'));
    }
    
     public function delete_action($entry_id) {
        
        $this->entry_id = $entry_id;
        $this->entry = OHNMailEntry::find($entry_id);
        $this->entry->delete();
        
        PageLayout::postMessage(MessageBox::success(_('Der Eintrag wurde gelöscht.')));
        
        $this->redirect($this->url_for('/notifications'));
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

    
}
