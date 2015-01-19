<?php

/**
 * File - description
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License
 * version 3 as published by the Free Software Foundation.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
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

}
