<?php
class AddiXi_Cache_CacheStarterPlugin extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $this->initCaches();
        
        if($this->getRequest()->getParam('clean') !== null)
            AddiXi_Cache_CacheCleaner::clean();
        else if ($this->getRequest()->getModuleName() == 'frontend')
        {
            $browserSession = new Zend_Session_Namespace('Browser');

            $userAgent = new Zend_Http_UserAgent();

            if(@$userAgent->getDevice()->getBrowser() == "Internet Explorer" && @$userAgent->getDevice()->getBrowserVersion() <= 7)
                $browserSession->IE = true;
            else
                $browserSession->IE = false;
            
            Zend_Registry::get('cache_page')->start();
        }
    }

    public function initCaches()
    {
        $config = Zend_Registry::get('config');

        if(APPLICATION_ENV == 'development')
            $debug_header = true;
        else
            $debug_header = false;

        $frontController = Zend_Controller_Front::getInstance();
        $frontController->setParam('disableOutputBuffering', true);

        $cache_page = Zend_Cache::factory('Page','File',array('debug_header' => $debug_header,
                                                              'default_options' => array(
                                                                  'cache_with_cookie_variables' => true,
                                                                  'cache_with_session_variables' => true,
                                                                  'cache_with_post_variables' => true,
                                                                  'cache_with_get_variables' => true,
                                                                  'make_id_with_cookie_variables' => false)),array('file_name_prefix' => 'Zend_Cache_'.$config->shopPath.'_'.APPLICATION_ENV));

        $cache_core = Zend_Cache::factory('core','file',array('automatic_serialization' => true),array('file_name_prefix' => 'Zend_Cache_'.$config->shopPath.'_'.APPLICATION_ENV));

        Zend_Db_Table_Abstract::setDefaultMetadataCache($cache_core);

        Zend_Registry::set('cache_page', $cache_page);
        Zend_Registry::set('cache_core', $cache_core);
    }
}
?>
