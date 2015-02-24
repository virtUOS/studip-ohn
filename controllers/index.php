<?php

/**
 * File - description
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License
 * version 3 as published by the Free Software Foundation.
 *
 * @author      Till Gl�ggler <tgloeggl@uos.de>
 * @license     https://www.gnu.org/licenses/agpl-3.0.html AGPL version 3
 */

class IndexController extends StudipController
{
    function before_filter(&$action, &$args)
    {
        parent::before_filter($action, $args);

        $this->set_layout($GLOBALS['template_factory']->open('layouts/base'));
    }

    public function impressum_action()
    {

        
    }

    public function kontakt_action()
    {

        
    }

    public function faq_action()
    {

        
    }

	public function neuigkeiten_action()
    {

        
    }

    public function ueberuns_action()
    {     
    }
    public function nutzungsbedingungen_action()
    {     
    }
    public function datenschutz_action()
    {     
    }

    public function ohnkursportal_action()
    {
    }

    public function kurse_action()
    {
    }

    public function projektpartner_action()
    {
    }


    public function logout_action()
    {
        global $auth, $sess, $user;

        require_once 'lib/messaging.inc.php';

        //nur wenn wir angemeldet sind sollten wir dies tun!
        if ($auth->auth["uid"]!="nobody")
        {
            $sms = new messaging();

            $my_messaging_settings = UserConfig::get($user->id)->MESSAGING_SETTINGS;

            //Wenn Option dafuer gewaehlt, alle ungelsesenen Nachrichten als gelesen speichern
            if ($my_messaging_settings["logout_markreaded"]) {
                $sms->set_read_all_messages();
            }

            $logout_user=$user->id;

            // TODO this needs to be generalized or removed
            //erweiterung cas
            if ($auth->auth["auth_plugin"] == "cas"){
                $casauth = StudipAuthAbstract::GetInstance('cas');
                $docaslogout = true;
            }
            //Logout aus dem Sessionmanagement
            $auth->logout();
            $sess->delete();

            page_close();

            //Session changed zuruecksetzen
            $timeout=(time()-(15 * 60));
            $user->set_last_action($timeout);

            //der logout() Aufruf fuer CAS (dadurch wird das Cookie (Ticket) im Browser zerstoert)
            if ($docaslogout){
                $casauth->logout();
            }
        } else {
            $sess->delete();
            page_close();
        }

        $this->redirect(URLHelper::getURL('plugins.php/mooc/courses/overview'));
    }
}
