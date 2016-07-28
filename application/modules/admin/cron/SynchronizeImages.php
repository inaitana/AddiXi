<?php
class Admin_Cron_SynchronizeImages extends AddiXi_Cron_CronJobAbstract{
    protected $_interval = 86400;
    protected $_firstTime = "04:00:00";
    protected $_settingsDb;
    protected $_seoModel;

    protected function setup()
    {
    	$this->_imagesDb = new Admin_Model_DbTable_ImmaginiTagliate();
    }

    protected function run()
    {
    	
        return true;
    }
}
?>
