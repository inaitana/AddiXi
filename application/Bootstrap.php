<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{    
    protected function _initModuleControllers()
    {
        $frontController = Zend_Controller_Front::getInstance();

        $frontController->setControllerDirectory(
            array(
                'admin' => APPLICATION_PATH.'/admin/controllers',
                'user' => APPLICATION_PATH.'/user/controllers',
                'frontend' => APPLICATION_PATH.'/frontend/controllers'
            )
        );

        $frontController->setDefaultModule('frontend');
    }

    protected function _initPluginsAndHelpers()
    {
        Zend_Controller_Front::getInstance()->registerPlugin(new AddiXi_Cron_CronPlugin());
        Zend_Controller_Front::getInstance()->registerPlugin(new AddiXi_ModuleLoader());
        Zend_Controller_Front::getInstance()->registerPlugin(new AddiXi_Cache_CacheStarterPlugin());
        
        Zend_Controller_Action_HelperBroker::addHelper(new AddiXi_Cache_CacheCleanerHelper());
    }

    protected function _initConfig()
    {
    	include(realpath(APPLICATION_PATH.'/configs/shopname.inc.php'));
    	
        $shopPath = str_replace(" ","",strtolower($config->shopName));
        $dbconfig = new Zend_Config_Ini(APPLICATION_PATH.'/configs/'.$shopPath.'/config.ini','db');
        $this->setOptions($dbconfig->toArray());

        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/'.$shopPath.'/config.ini',$shopPath, array('allowModifications' => true));
        $config->shopPath = $shopPath;
        $config->setReadOnly();
        Zend_Registry::set('config',$config);

        $configAdmin = new Zend_Config_Ini(APPLICATION_PATH.'/configs/'.$shopPath.'/config.ini','admin');
        Zend_Registry::set('configAdmin',$configAdmin);
    }

    public function _initLocale()
    {
        Zend_Locale::setDefault('it_IT');
        $locale = new Zend_Locale('it_IT');
        Zend_Registry::set('Zend_Locale', $locale);   
    }

    public function _initValidatorsTranslation()
    {
        $translator = new Zend_Translate(
            'array',
            APPLICATION_PATH.'/../languages',
            'it',
            array('scan' => Zend_Translate::LOCALE_DIRECTORY)
        );
        Zend_Validate_Abstract::setDefaultTranslator($translator);
    }

    public function _initSMTP()
    {
        $config = Zend_Registry::get('config');

        if(!is_null($config->SMTP->auth) && $config->SMTP->auth != '')
            $configSmtpAuth = array(
                'auth' => $config->SMTP->auth,
                'username' => $config->SMTP->username,
                'password' => $config->SMTP->password,
                'ssl' => $config->SMTP->ssl,
                'port' => $config->SMTP->port
            );
        else
            $configSmtpAuth = array();
        
        $transport = new Zend_Mail_Transport_Smtp($config->SMTP->server,$configSmtpAuth);
        Zend_Mail::setDefaultTransport($transport);
    }

    protected function _initDocType()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML11');
    }

    protected function _initJQuery()
    {
        $config = Zend_Registry::get('config');
        
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
        $view->jQuery()->setVersion('1.6.2');
        $view->jQuery()->setUiVersion('1.8.16');
        $view->jQuery()->addJavascriptFile('/js/jquery.address-1.4.min.js');
        $view->jQuery()->addJavascriptFile('/js/jquery.uploadify.v2.1.0.min.js');
        $view->jQuery()->addJavascriptFile('/js/jquery.xTooltip.js');
        $view->jQuery()->addStylesheet('/css/jqueryui/'.$config->jqueryui->style.'/jquery-ui-1.8.5.custom.css');
        $view->jQuery()->addStylesheet('/css/jquery.xTooltip.css');
        $view->jQuery()->enable();

        $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
        $viewRenderer->setView($view);
        Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
    }

    protected function _initDbRegistry()
    {
        $resource = $this->getPluginResource('db');
        $db = $resource->getDbAdapter();

        Zend_Registry::set("db", $db);
    }
}