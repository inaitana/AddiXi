<?php
class AddiXi_Cache_CacheCleaner {
    public static function clean()
    {        
        if(Zend_Registry::isRegistered('cache_core'))
            Zend_Registry::get('cache_core')->clean();
        
        if(Zend_Registry::isRegistered('cache_page'))
            Zend_Registry::get('cache_page')->clean();
    }
}
?>
