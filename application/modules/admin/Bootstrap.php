<?php

class Admin_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected function _initAutoLoad()
    {
        $autoloader = new Zend_Application_Module_Autoloader(
        array(
        'namespace' => 'Admin',
        'basePath' => APPLICATION_PATH . '/modules/admin')
        );

        $autoloader->addResourceType('cron', 'cron', 'Cron');
        return $autoloader;
    }

    public function _loadAuth()
    {
        Zend_Controller_Front::getInstance()->registerPlugin(new AddiXi_AuthPlugin('admin'));
    }

    public function _loadKCFinderSynchronizePlugin()
    {
        $configAdmin = Zend_Registry::get('configAdmin');
    	if($configAdmin->plugins->kcfinder)
        	Zend_Controller_Front::getInstance()->registerPlugin(new AddiXi_KCFinder_SynchronizePlugin());
    }

    public function _loadLayout()
    {
        $config = Zend_Registry::get('config');
        $configAdmin = Zend_Registry::get('configAdmin');
        
        $layout = Zend_Layout::getMvcInstance();

        $layout->setLayoutPath(APPLICATION_PATH .'/modules/admin/layouts/scripts');
        $view = $layout->getView();
        $view->registerHelper(new Admin_View_Helper_UserStatus(), 'UserStatus');
        
        $view->registerHelper(new Admin_View_Helper_CKEditor(), 'CKEditor');
        if($configAdmin->plugins->kcfinder)
            $view->registerHelper(new Admin_View_Helper_KCFinder(), 'KCFinder');
        if($configAdmin->plugins->uploadify)
        {
            $view->registerHelper(new Admin_View_Helper_Uploadify(), 'Uploadify');
            $view->jQuery()->addJavascriptFile('/js/swfobject.js');
        }
        $view->registerHelper(new Admin_View_Helper_TabMenu(), 'TabMenu');
        $view->registerHelper(new Application_View_Helper_FormAjaxify(), 'formAjaxify');
        if($config->SMTP->server == "")
        	$view->registerHelper(new Application_View_Helper_DisplayMail(), 'DisplayMail');

        $view->jQuery()->addJavascriptFile('/js/jquery-ui.min.js');
        $view->jQuery()->addJavascriptFile('/js/jquery.mom.js');
        $view->jQuery()->addStylesheet('/css/jquery.mom.css');
        $view->jQuery()->addStylesheet('/css/jquery.mom.theme.css');

        if($configAdmin->plugins->ckeditor)
        {
            $view->jQuery()->addJavascriptFile('/js/ckeditor/ckeditor.js');
            $view->jQuery()->addJavascriptFile('/js/ckeditor/adapters/jquery.js');
        }
    }
}

