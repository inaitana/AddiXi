<?php

class AddiXi_Cron_CronJobsDb extends Zend_Db_Table_Abstract
{
    protected $_name = 'Cronjobs';
    
    public function getScheduledJobs()
    {
        $jobRows = $this->fetchAll('`Prossimo Run` < NOW()');
        $jobs = array();
        if($jobRows!= NULL)
            foreach($jobRows as $jobRow)
               $jobs[] = $jobRow['Nome Classe'];
        return $jobs;
    }

    public function setupJob($className, $firstTime)
    {
        if($this->fetchRow(array('`Nome Classe` = ?' => $className))==NULL)
        {
        	if($firstTime != null)
	            $this->insert(array('Nome Classe' => $className, 'Prossimo Run' => new Zend_Db_Expr("CONCAT(CURDATE(),' ','".$firstTime."')")));
        	else
	            $this->insert(array('Nome Classe' => $className, 'Prossimo Run' => new Zend_Db_Expr('FROM_UNIXTIME('.(time()-1).')')));
        }
    }

    public function updateJob($className, $interval)
    {
    	$this->update(array('Prossimo Run' => new Zend_Db_Expr('FROM_UNIXTIME('.(time()+$interval).')')),array('`Nome Classe` = ?' => $className));
    }
}

