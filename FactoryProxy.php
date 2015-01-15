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

        if (file_exists($this->factory_path .'/'. $template . '.php') ||
                file_exists($this->factory_path .'/'. $template)) {
            $template = $this->factory_path .'/'. $template;
        }
        
        // get file
        $file = $this->get_template_file($template);

        // retrieve handler
        list($class, $options) = $this->get_template_handler($file);

        return new $class($file, $this, $options);
    }
}