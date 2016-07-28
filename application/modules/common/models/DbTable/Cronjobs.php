<?php

class Application_Model_DbTable_Cronjobs extends Zend_Db_Table_Abstract
{
    protected $_name = 'Cronjobs';

    public function check()
    {
        $jobRows = $this->fetchAll('Next < NOW()');
        var_dump($jobRows);
    }

    public function getScheduledJobs()
    {
        $jobRows = $this->fetchAll('`Prossimo Run` < NOW()');
        $jobs = array();
        if($jobRows!= NULL)
            foreach($jobRows as $jobRow)
               $jobs[] = $jobRow['Nome Classe'];
        return $jobs;
    }

    public function setupJob($className)
    {
        if($this->fetchRow(array('`Nome Classe` = ?' => $className))==NULL)
            $this->insert(array('Nome Classe' => $className, 'Prossimo Run' => new Zend_Db_Expr('FROM_UNIXTIME('.(time()-1).')')));
    }

    public function updateJob($className, $interval)
    {
        $this->update(array('Prossimo Run' => new Zend_Db_Expr('FROM_UNIXTIME('.(time()+$interval).')')),array('`Nome Classe` = ?' => $className));
    }
}

