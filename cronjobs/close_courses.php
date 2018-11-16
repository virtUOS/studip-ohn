<?php
/**
* close_courses.php
*
* @author Annelene Sudau <asudau@uos.de>
* @access public
*/
require_once 'lib/classes/CronJob.class.php';
//require_once 'public/plugins_packages/virtUOS/OHNLayout/models/OHNMailEntry.class.php';


class CloseCourses extends CronJob
{

    public static function getName()
    {
        return dgettext('Close_courses', 'Stuft in Moocs nach Kursende Autoren zu Lesern herunter und archiviert Kurse 10 Monate nach Ablauf');
    }

    public static function getDescription()
    {
        return dgettext('Close_courses', 'Stuft in Moocs nach Kursende Autoren zu Lesern herunter und archiviert Kurse 10 Monate nach Ablauf');
    }
    
    

    public function execute($last_result, $parameters = array())
    {
        $db = DBManager::get();
        //PluginEngine::getPlugin('OHNLayout');
        
          //get datafield_id of (M)ooc Startdatum  
        $res = $db->query("SELECT datafield_id FROM datafields WHERE `name` LIKE '(M)OOC Startdatum'");
        $result_id = $res->fetchColumn();
        $mooc_start_id =  $result_id;
        
        //get datafield_id of (M)ooc Startdatum  
        $res = $db->query("SELECT datafield_id FROM datafields WHERE `name` LIKE '(M)OOC Dauer'");
        $result_id = $res->fetchColumn();
        $mooc_dauer_id =  $result_id;
        
        //get today
        $today = new DateTime(date("Y-m-d"));
        
        $res = $db->query("SELECT range_id FROM datafields_entries WHERE datafield_id LIKE '". $mooc_start_id ."'");
        $moocs = $res->fetchAll();
        
        //get mooc-Courses
        
        foreach($moocs as $mooc){
            
            $res = $db->query("SELECT content FROM datafields_entries WHERE datafield_id LIKE '". $mooc_start_id ."' AND range_id LIKE '". $mooc[0] ."'");
            $start = $res->fetchColumn();
            $startdate = new DateTime($start);
            
            $res = $db->query("SELECT content FROM datafields_entries WHERE datafield_id LIKE '". $mooc_dauer_id ."' AND range_id LIKE '". $mooc[0] ."'");
            $dauer = $res->fetchColumn();
            
            if($dauer){
                $nr_weeks = explode(' ', $dauer)[0];
                echo 'kursid: ' . $mooc[0];
                $end = $startdate->modify('+' . $nr_weeks . ' weeks');

                $course = new \Seminar($mooc[0]);
                $members = $course->getMembers('autor');
         
                //wenn der Kurs beendet ist und es noch Autoren gibt müssen diese zu Lesern heruntergestuft werden
                if( ($end->getTimestamp() < $today->getTimestamp()) && $members) {
                    echo "Dieser hier ist vorbei (". $course->name .") Start: " . $startdate->format('Y-m-d') . " Dauer: " . $nr_weeks . " Wochen " ;
                    foreach($members as $member){
                        //echo $member['user_id']. '->userID';
                        $query = 'UPDATE seminar_user SET status = ? WHERE Seminar_id = ? AND user_id = ? AND status = ?';
                        $statement = DBManager::get()->prepare($query);
                        $ergebnis = $statement->execute(array('user', $mooc[0], $member['user_id'], 'autor'));  
                        //echo $ergebnis . 'seminra: ' . $mooc[0] . ' userid: ' . $member->id;
                    }  
                    echo count($members) . ' Autoren erfolgreich heruntergestuft.';
                }
            }
        }
            
        return true;
    }
    
    
}
