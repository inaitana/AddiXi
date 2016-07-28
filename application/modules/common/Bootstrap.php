<?php

class Common_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected function _initAutoLoad()
    {
        $autoloader = new Zend_Application_Module_Autoloader(
        array(
            'namespace' => 'Application',
            'basePath' => APPLICATION_PATH . '/modules/common')
        );

        $autoloader->addResourceType('cron', 'cron', 'Cron');
        return $autoloader;
    }
}

