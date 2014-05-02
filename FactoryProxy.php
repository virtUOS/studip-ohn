<?php

namespace OHN;

/**
 * FactoryProxy - enable overwriting of Stud.IP-Templates
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Affero General Public License
 * version 3 as published by the Free Software Foundation.
 *
 * @author      Till Glöggler <tgloeggl@uos.de>
 * @license     https://www.gnu.org/licenses/agpl-3.0.html AGPL version 3
 */

class FactoryProxy extends \Flexi_TemplateFactory
{
    private 
        $orig_factory,
        $factory_path;

    function __construct(\Flexi_TemplateFactory $orig_factory, $factory_path) {
        $this->orig_factory = $orig_factory;
        $this->factory_path = $factory_path;
        
        parent::__construct($orig_factory->path);
    }

    function open($template)
    {
        // if it is not a string, this method behaves like identity
        if (!is_string($template)) {
            return $template;
        }
        
        // check if we have an overwritten template
        try {
            return $this->orig_factory->open($this->factory_path .'/'. $template);
        } catch (\Flexi_TemplateNotFoundException $e) {
            return $this->orig_factory->open($template);
        }
    }
}