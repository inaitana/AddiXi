<?php
abstract class AddiXi_Cron_CronJobAbstract {
    protected $_cronJobsDb;

    protected $_maxTryOuts = 10;
    protected $_minInterval = 300;

    // Periodo di esecuzione in secondi (default 86400 = 24 ore)
    protected $_defaultInterval = 86400;
    // Numero di tentativi per esecuzione
    protected $_defaultTryOuts = 1;
    protected $_interval;
    protected $_tryOuts;
    protected $_firstTime = null; // 'HH:mm:ss'

    public function __construct($cronJobsDb = null)
    {
        if($this->_interval == null)
            $this->_interval = $this->_defaultInterval;
        else if($this->_interval <= $this->_minInterval)
            $this->_interval = $this->_minInterval;

        if($this->_tryOuts == null || $this->_tryOuts <= 0)
            $this->_tryOuts = $this->_defaultTryOuts;
        else if($this->_tryOuts > $this->_maxTryOuts)
            $this->_tryOuts = $this->_maxTryOuts;
        
        $this->_cronJobsDb = $cronJobsDb;
        $this->setupDb();
    }

    protected function output($string) {
        echo "[".date("y-m-d H:i:s")."] ".get_class($this).": ".$string."\n";
    }

    protected function setupDb()
    {
        if($this->_cronJobsDb != null)
        {
            $this->_cronJobsDb->setupJob(get_class($this), $this->_firstTime);
        }
    }

    protected function updateDb()
    {
        if($this->_cronJobsDb != null)
            $this->_cronJobsDb->updateJob(get_class($this),$this->_interval);
    }

    public function doJob()
    {
        $this->setup();

        $i=1;
        while($i<=$this->_tryOuts && !$this->run())
        {
            $this->output("tentativo ".$i." di ".$this->_tryOuts." fallito.");
            $i++;
        }

        $this->updateDb();
    }

    protected abstract function setup();
    protected abstract function run();
}
?>
