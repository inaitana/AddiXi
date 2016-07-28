<?php
class Application_Model_Impostazioni {
    protected $_settingsDb;
    protected $_cache;
    
    function __construct()
    {
        $this->_settingsDb = new Application_Model_DbTable_Impostazioni();
        if(Zend_Registry::isRegistered('cache_core'))
            $this->_cache = Zend_Registry::get('cache_core');
    }

    function get($settingName)
    {
        $cacheName = 'setting_'.str_replace(" ","_",$settingName);

        if($this->_cache == NULL || !$this->_cache->test($cacheName))
        {
            $row = $this->_settingsDb->fetchRow(array('Nome = ?' => $settingName));
            if($row !== null)
            {
                if ($this->_cache != NULL)
                    $this->_cache->save($row->Valore, $cacheName);
                return $row->Valore;
            }
            else
                return null;
        }
        else if ($this->_cache != NULL)
            return $this->_cache->load($cacheName);
    }
}
?>
