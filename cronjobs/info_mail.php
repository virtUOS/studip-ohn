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
            echo "today " . $today->format('Y-m-d') . " starttermin: " . $startdate->format('Y-m-d') . " gewünschter abstand: " . (string)$entry->days_from_start . " tatsächlicher abstand: " . $interval->format('%R%a') . " /n";
            if ($interval->format('%R%a') == (string)$entry->days_from_start){
                
                //TODO
                self::sendMail($entry);
                
                $entry->sent = date("d-m-Y");
                $entry->store();
                
                echo "Dieser hier ist heute (". $today->format('Y-m-d') . ") fällig: Kursbeginn: ". $startdate->format('Y-m-d') . " - " . $entry->mailsubject . " /n" ;
            } else echo "Ist noch nicht soweit: " .  $entry->mailsubject . " \n"; 
            
        }
        
      
        /**
        
            $members = $course->getMembers('autor');
            
            //foreach TN()
            foreach ($members as $member){
  
                $complete = false;

                foreach ($blocks_ids as $block_id){
                    $block = new \Mooc\DB\Block($block_id['id']);
                    if (!$block->hasUserCompleted($member['user_id'])){
                        $complete = false;
                        break;
                    } else {
                        $complete = true;
                    }
                }
              
                    if ($complete){
                    
                    echo 'User '. $member['fullname'] .' hat die Inhalte des Kurses '. $course->name ." vollständig abgeschlossen: " . $ist ." von " . $soll  . "\n";

                
                    //if not already sent
                     $stmt = $db->prepare("SELECT * FROM zertifikat_sent
                        WHERE user_id = :user_id
                        AND course_id = :sem_id");
                     $stmt->execute(array('user_id' => $member['user_id'], 'sem_id' => $seminar_id));
                     $result = $stmt->fetch(PDO::FETCH_ASSOC);
                     
                     if (!$result){
                                    
                         if(self::sendZertifikatsMail($member['fullname'], $course->name, $institut->name, $contact_mail)){
                         
                            $stmt = $db->prepare("INSERT INTO zertifikat_sent
                                (user_id, course_id, mail_sent)
                                VALUES (:user_id, :sem_id, '1')");
                            $stmt->execute(array('user_id' => $member['user_id'], 'sem_id' => $seminar_id));
                            
                            echo 'Bescheinigung über Abschluss der Inhalte des Kurses '. $course->name . " durch User " . $member['fullname'] . " wurde versendet \n";

                         }
                    
                     } else {
                         
                        echo 'User '. $member['fullname'] .' hat Bescheinigung über Abschluss der Inhalte des Kurses '. $course->name . " bereits erhalten. \n";

                     }
                    
                }
            }
            
            
            //unset($course);
        }**/

        return true;
    }
    
    private static function sendMail($entry){
        
        //$filepath = self::pdf_action($user, $seminar);

        
        /**$mailtext = '<html>
          

            <body>

            <h2>Teilnahmezertifikat für ' . $user . ':</h2>

            <p>Im Anhang finden Sie ein Teilnahmezertifikat für den/die Teilnehmer/in einer Onlineschulung</p>

            </body>
            </html>
            ';
         * **/
            $course = new Course($entry->course_id);
            $members = $course->getMembers('autor');
            //get all Course Members and their Mailadresses
            $empfaenger = $contact_mail;//$contact_mail; //Mailadresse
            //$absender   = "asudau@uos.de";
            $betreff    = $entry->mailsubject;
            $mailtext   = $entry->mailcontent;
            
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
