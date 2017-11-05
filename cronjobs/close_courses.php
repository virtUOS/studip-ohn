<?php
/**
* preliminary_participants.php
*
* @author Till Glöggler <tgloeggl@uos.de>
* @access public
*/
require_once 'lib/classes/CronJob.class.php';
require_once 'public/plugins_packages/virtUOS/OHNLayout/models/OHNMailEntry.class.php';


class InfoMail extends CronJob
{

    public static function getName()
    {
        return dgettext('Mooc_Infomail', 'Mooc - automatisiert Infomails an TN verschicken');
    }

    public static function getDescription()
    {
        return dgettext('Mooc_Infomail', 'Sendet gemäß der individuellen Konfiguration zu bestimmten Zeiten Infomails an Kursteilnehmer');
    }
    
    

    public function execute($last_result, $parameters = array())
    {
        $db = DBManager::get();
        PluginEngine::getPlugin('OHNLayout');
        
          //get datafield_id of (M)ooc Startdatum  
        $res = $db->query("SELECT datafield_id FROM datafields WHERE `name` LIKE '(M)OOC Startdatum'");
        $result_id = $res->fetchColumn();
        $mooc_start_id =  $result_id;
        
        //get all planned mailnotifications
        $entries = OHNMailEntry::findBySQL('sent IS NULL');
        
        //get today
        $today = new DateTime(date("Y-m-d"));
        
        //get startdate of courses for which we have found planned mailnotifications
        //and compare planned day which is difference between mooc_start and today
        foreach ($entries as $entry){
            $res = $db->query("SELECT content FROM datafields_entries WHERE datafield_id LIKE '". $mooc_start_id ."' AND range_id LIKE '". $entry->course_id ."'");
            $result = $res->fetchColumn();
            echo var_dump($mooc_start_id) . " kursid " . $entry->course_id .  " /n";
            $startdate = new DateTime($result);
            
            $interval = date_diff($startdate, $today);
            echo "today " . $today->format('Y-m-d') . " starttermin: " . $startdate->format('Y-m-d') . " gewünschter abstand: " . intval($entry->days_from_start) . " tatsächlicher abstand: " . intval($interval->format('%R%a')) . " /n";

            if (intval($interval->format('%R%a')) == intval($entry->days_from_start)){
                echo('hier bin ich');
                //TODO
                if (self::sendMail($entry)){
                
                    $entry->sent = date("d-m-Y");
                    $entry->store();
                
                echo "Dieser hier ist heute (". $today->format('Y-m-d') . ") fällig: Kursbeginn: ". $startdate->format('Y-m-d') . " - " . $entry->mailsubject . " /n" ;
                }
                
                } else echo "Ist noch nicht soweit: " .  $entry->mailsubject . " \n"; 
            
        }

        return true;
    }
    
    private static function sendMail($entry){
        
        echo 'mail senden';
        //$filepath = self::pdf_action($user, $seminar);

        
        /**$mailtext = '<html>
          

            <body>

            <h2>Teilnahmezertifikat für ' . $user . ':</h2>

            <p>Im Anhang finden Sie ein Teilnahmezertifikat für den/die Teilnehmer/in einer Onlineschulung</p>

            </body>
            </html>
            ';
         * **/
            $course = new \Seminar($entry->course_id);
            $members = $course->getMembers('autor');
            
            //$empfaenger = $contact_mail;//$contact_mail; //Mailadresse
            //$absender   = "asudau@uos.de";
            $betreff    = $entry->mailsubject;
            $mailtext   = $entry->mailcontent;
            $messaging = new messaging();
           
            //if (\Message::send($course->getMembers('dozent')[0], $members, $betreff, $mailtext)){
            if($messaging->insert_message($mailtext, $members, '____%system%____', FALSE, FALSE, '1', FALSE, $betreff, TRUE)){
                    
                return true;
            }
            
            /**
           $ok = \StudipMail::sendMessage($members[0], $betreff, $mailtext);
           echo $ok;
             /**
            $mail = new \StudipMail();
            //get all Course Members and their Mailadresses
            foreach($members as $member){
                $user = new \Seminar_User($member['user_id']); 
                $email = $user->email;
                echo $email;
                $mail->addRecipient($email);
            }
            
           
            echo $mail->setReplyToEmail('')
                 ->setSenderEmail('')
                 ->setSenderName('OHN Kursportal')
                 ->setSubject($betreff)
                 ->setBodyHtml($mailtext)
                 ->setBodyHtml(strip_tags($mailtext))  
                 ->send();
            
            /**
            $mail = new StudipMail();
            return $mail->addRecipient($empfaenger)
                //->addRecipient('elmar.ludwig@uos.de', 'Elmar Ludwig', 'Cc')
                 ->setReplyToEmail('')
                 ->setSenderEmail('')
                 ->setSenderName('OHN Kursportal')
                 ->setSubject($betreff)
                 ->setBodyHtml($mailtext)
                 ->setBodyHtml(strip_tags($mailtext))  
                 ->send();
             * 
             */

    }
    
    
    private function clear_string($str){
        $search = array("ä", "ö", "ü", "ß", "Ä", "Ö",
                "Ü", "&", "é", "á", "ó", " ");
        $replace = array("ae", "oe", "ue", "ss", "Ae", "Oe",
                 "Ue", "und", "e", "a", "o", "_");
        $str = str_replace($search, $replace, $str);
        //$str = strtolower(preg_replace("/[^a-zA-Z0-9]+/", trim($how), $str));
        return $str;
}
    
    
}
