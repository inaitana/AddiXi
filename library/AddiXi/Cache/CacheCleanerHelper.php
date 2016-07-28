<?php
class AddiXi_Cache_CacheCleanerHelper extends Zend_Controller_Action_Helper_Abstract {
    public function getName()
    {
        return "CacheCleaner";
    }

    public function direct()
    {
        $settingsDb = new Admin_Model_Impostazioni();
        $settingsDb->set('Aggiornato', true);
        AddiXi_Cache_CacheCleaner::clean();
    }
}
?>