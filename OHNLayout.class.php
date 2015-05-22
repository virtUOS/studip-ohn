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
        
        $GLOBALS['default_factory'] = $GLOBALS['template_factory'];

        $GLOBALS['template_factory'] = new OHN\FactoryProxy(
            $GLOBALS['template_factory'], 
            realpath($this->getPluginPath() . '/templates')
        );

        PageLayout::addScript($this->getPluginURL() . '/assets/application.js');
        PageLayout::addStylesheet($this->getPluginURL() . '/assets/style.css');

        $GLOBALS['OHN_IMAGES'] = $this->getPluginURL() .'/assets/images';
        
        ##add Footer Navigation
        $navigation = new Navigation('�ber uns',
                PluginEngine::getURL($this, array(), 'index/ueberuns', true));
        Navigation::insertItem('/footer/ueberuns', $navigation, null);

        $navigation = new Navigation('Neuigkeiten', 'http://www.offene-hochschule-niedersachsen.de/site/offene-hochschule/aktuelles/news');
        Navigation::insertItem('/footer/neugikeiten', $navigation, null);

        $navigation = new Navigation('FAQ',
                PluginEngine::getURL($this, array(), 'index/faq', true));
        Navigation::insertItem('/footer/faq', $navigation, null);

        $navigation = new Navigation('Kontakt',
                PluginEngine::getURL($this, array(), 'index/kontakt', true));
        Navigation::insertItem('/footer/kontakt', $navigation, null);


        $navigation = new Navigation('Impressum',
                PluginEngine::getURL($this, array(), 'index/impressum', true));
        Navigation::insertItem('/footer/impressum', $navigation, null);

        
        ##remove studip Standard navigation
        Navigation::removeItem('/footer/siteinfo');
        Navigation::removeItem('/footer/blog');
        Navigation::removeItem('/footer/sitemap');
        Navigation::removeItem('/footer/studip');

        ##Header Navigation
        if ($GLOBALS['user']->id == 'nobody') {
            $navigation = new Navigation('Header', PluginEngine::getLink($this, array(), 'courses/overview'));
            Navigation::insertItem('/header', $navigation, null);

            $navigation = new Navigation('OHN-Kursportal',
                PluginEngine::getURL($this, array(), 'index/ohnkursportal', true));
            Navigation::insertItem('/header/ohnkursportal', $navigation, null);

            $navigation = new Navigation('Kurse',
                PluginEngine::getURL($this, array(), 'index/kurse', true));
            Navigation::insertItem('/header/kurse', $navigation, null);


            $navigation = new Navigation('Projektpartner',
                PluginEngine::getURL($this, array(), 'index/projektpartner', true));
            Navigation::insertItem('/header/projektpartner', $navigation, null);

        }

        // alternative logout
        $navigation = new Navigation('Logout',
                PluginEngine::getURL($this, array(), 'index/logout', true));
        Navigation::insertItem('/links/logout', $navigation, null);

        $navigation = new Navigation('Profil',
                URLHelper::getUrl('dispatch.php/profile'));
        Navigation::insertItem('/links/profil', $navigation, 'logout');
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
