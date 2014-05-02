<?php
require 'bootstrap.php';

/**
 * OHNLayout.class.php
 *
 * ...
 *
 * @author  tgloeggl@uos.de
 * @version 0.1a
 */

require_once 'FactoryProxy.php';

class OHNLayout extends StudIPPlugin implements SystemPlugin {

    public function __construct() {
        parent::__construct();
        
        #var_dump($GLOBALS['template_factory']);
        $GLOBALS['template_factory'] = new OHN\FactoryProxy(
            $GLOBALS['template_factory'], 
            realpath($this->getPluginPath() . '/templates')
        );
    }
}
