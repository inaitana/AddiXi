<?php
class AddiXi_Cron_CronRunner {
    protected $_cronJobsPool;
    protected $_cronJobsDb;

    protected $_cronJobsRequested;

    public function __construct($jobNames = NULL)
    {
        $this->_cronJobsDb = new AddiXi_Cron_CronJobsDb();

        if($jobNames!==NULL && is_array($jobNames))
        {
            $this->_cronJobsRequested = true;

            foreach($jobNames as $class)
                if (is_subclass_of($class, 'Addixi_Cron_CronJobAbstract'))
                    $this->_cronJobsPool[$class] = new $class($this->_cronJobsDb);
        }
        else
        {
            $this->_cronJobsRequested = false;

            $this->_cronJobsPool = array();
            $front = Zend_Controller_Front::getInstance();
            $modulesRootDirectory = realpath($front->getModuleDirectory()."/..");

            foreach (scandir($modulesRootDirectory) as $moduleDirectory)
            {            	
                if(strpos($moduleDirectory,'.') !== 0 && is_dir($modulesRootDirectory.'/'.$moduleDirectory.'/cron/'))
                    foreach (scandir($modulesRootDirectory.'/'.$moduleDirectory.'/cron/') as $cronFile)
                        if ($cronFile !== '.' && $cronFile != '..')
                            include_once $modulesRootDirectory.'/'.$moduleDirectory.'/cron/'.$cronFile;
            }

            foreach (get_declared_classes() as $class)
                if (is_subclass_of($class, 'Addixi_Cron_CronJobAbstract'))
                    $this->_cronJobsPool[$class] = new $class($this->_cronJobsDb);
        }
    }

    public function start()
    {
        if($this->_cronJobsRequested)
            foreach ($this->_cronJobsPool as $cronJob)
                $cronJob->doJob();
        else
            foreach ($this->_cronJobsDb->getScheduledJobs() as $cronClassName)
            {
                $cronJob = $this->_cronJobsPool[$cronClassName];
                $cronJob->doJob();
            }
    }
}
?>
