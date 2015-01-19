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

        PageLayout::addScript($this->getPluginURL() . '/assets/application.js');
        PageLayout::addScript($this->getPluginURL() . '/assets/jquery-1.11.2.min.js');
        PageLayout::addScript($this->getPluginURL() . '/assets/bootstrap/js/bootstrap.min.js');
        PageLayout::addStylesheet($this->getPluginURL() . '/assets/style.css');
        PageLayout::addStylesheet($this->getPluginURL() . '/assets/bootstrap/css/bootstrap.min.css');

        $GLOBALS['OHN_IMAGES'] = $this->getPluginURL() .'/assets/images';
        
        ##add Footer Navigation
        $navigation = new Navigation('Über uns',
                PluginEngine::getURL($this, array(), 'index/ueberuns', true));
        Navigation::insertItem('/footer/ueberuns', $navigation,null );

        $navigation = new Navigation('Neuigkeiten', 'http://www.offene-hochschule-niedersachsen.de/site/offene-hochschule/aktuelles/news');
        Navigation::insertItem('/footer/neugikeiten', $navigation,null );

        $navigation = new Navigation('FAQ',
                PluginEngine::getURL($this, array(), 'index/faq', true));
        Navigation::insertItem('/footer/faq', $navigation,null );

        $navigation = new Navigation('Kontakt',
                PluginEngine::getURL($this, array(), 'index/kontakt', true));
        Navigation::insertItem('/footer/kontakt', $navigation,null );


        $navigation = new Navigation('Impressum',
                PluginEngine::getURL($this, array(), 'index/impressum', true));
        Navigation::insertItem('/footer/impressum', $navigation,null );

        $navigation = new Navigation('Nutzungsbedingungen',
                PluginEngine::getURL($this, array(), 'index/nutzungsbedingungen', true));
        Navigation::insertItem('/footer/nutzungsbedingungen', $navigation,null );  

         $navigation = new Navigation('Datenschutz',
                PluginEngine::getURL($this, array(), 'index/datenschutz', true));
        Navigation::insertItem('/footer/datenschutz', $navigation,null );              
        
        $navigation = new Navigation('facebook', 'https://www.facebook.com/offenehochschuleniedersachsen');
        Navigation::insertItem('/footer/facebook', $navigation,null );

        ##remove studip Standard navigation
        Navigation::removeItem('/footer/siteinfo');
        Navigation::removeItem('/footer/blog');
        Navigation::removeItem('/footer/sitemap');
        Navigation::removeItem('/footer/studip');
    }

    public function perform($unconsumed_path)
    {
        require_once 'vendor/trails/trails.php';
        require_once 'app/controllers/studip_controller.php';
        require_once 'app/controllers/authenticated_controller.php';

        $dispatcher = new Trails_Dispatcher(
            $this->getPluginPath(),
            rtrim(PluginEngine::getLink($this, array(), null), '/'),
            NULL
        );
        $dispatcher->plugin = $this;
        $dispatcher->dispatch($unconsumed_path);
    }
}
