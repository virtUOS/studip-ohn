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

class OHNLayout extends StudIPPlugin implements StandardPlugin, SystemPlugin {

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
        
        if($GLOBALS['perm']->have_perm('root')){
            $navigation = new Navigation('Statistiken', PluginEngine::getURL($this, array(), 'statistiken'));
            $navigation->addSubNavigation('statistics', new Navigation('Übersicht 2018', PluginEngine::getURL($this, array(), 'statistiken')));
            
            $navigation->addSubNavigation('course', new Navigation('KursteilnehmerInnen pro Kurs', PluginEngine::getURL($this, array(), 'statistiken/courses')));
            $navigation->addSubNavigation('all', new Navigation('Zusammenfassung gesamt', PluginEngine::getURL($this, array(), 'statistiken/all')));
            Navigation::addItem('/admin/ohn-statistiken', $navigation);
        }

        ##add Footer Navigation
        $navigation = new Navigation('Über uns',
                PluginEngine::getURL($this, array(), 'index/ueberuns', true));
        Navigation::insertItem('/footer/ueberuns', $navigation, null);

        $navigation = new Navigation('Neuigkeiten', 'http://www.offene-hochschule-niedersachsen.de/ohn/aktuelles/termine/');
        Navigation::insertItem('/footer/neugikeiten', $navigation, null);

        $navigation = new Navigation('FAQ',
                PluginEngine::getURL($this, array(), 'index/faq', true));
        Navigation::insertItem('/footer/faq', $navigation, null);

        $navigation = new Navigation('Kontakt',
                PluginEngine::getURL($this, array(), 'index/kontakt', true));
        Navigation::insertItem('/footer/kontakt', $navigation, null);


        $navigation = new Navigation('Impressum',
                URLHelper::getUrl('dispatch.php/siteinfo/show/2/3'));
        Navigation::insertItem('/footer/impressum', $navigation, null);
        
        ##remove studip Standard navigation
        //Navigation::removeItem('/footer/siteinfo');
        Navigation::removeItem('/footer/blog');
        Navigation::removeItem('/footer/sitemap');
        Navigation::removeItem('/footer/studip');

        ##Header Navigation
        if ($GLOBALS['user']->id == 'nobody') {
            $navigation = new Navigation('Header', PluginEngine::getLink($this, array(), 'courses/overview'));
            Navigation::insertItem('/header', $navigation, null);

            $navigation = new Navigation('Kurse',
                URLHelper::getUrl('plugins.php/mooc/courses/overview'));
            Navigation::insertItem('/header/kurse', $navigation, null);
            
            $navigation = new Navigation('OHN-Kursportal',
                PluginEngine::getURL($this, array(), 'index/ohnkursportal', true));
            Navigation::insertItem('/header/ohnkursportal', $navigation, null);

            $navigation = new Navigation('Projektpartnerinnen & Projektpartner',
                PluginEngine::getURL($this, array(), 'index/projektpartner', true));
            Navigation::insertItem('/header/projektpartner', $navigation, null);
            
            $navigation = new Navigation('Hochschule/Erwachsenenbildung',
                PluginEngine::getURL($this, array(), 'index/kursezumansehen', true));
            Navigation::insertItem('/header/hochschule', $navigation, null);

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
        $this->setupAutoload();
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
    
    private function setupAutoload() {
        if (class_exists("StudipAutoloader")) {
            StudipAutoloader::addAutoloadPath(__DIR__ . '/models');
        } else {
            spl_autoload_register(function ($class) {
                include_once __DIR__ . $class . '.php';
            });
        }
    }
    
    public function getTabNavigation($course_id)
    {
        global $perm;
        if ($perm->have_studip_perm('tutor', $course_id)){
            return array(
                'notifications' => new Navigation(
                    'Mail-Benachrichtigungen',
                    PluginEngine::getURL($this, array(), 'notifications')
                )
            );
        }
    }
    public function getInfoTemplate($course_id){}
    public function getIconNavigation($course_id, $last_visit, $user_id){}
    public function getNotificationObjects($course_id, $since, $user_id){}
}
