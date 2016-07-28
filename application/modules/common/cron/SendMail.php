<?php
class Application_Cron_SendMail extends AddiXi_Cron_CronJobAbstract{
    protected $_interval = 1200;

    protected $_mailDb;
    
    protected function setup()
    {
        $this->_mailDb = new Application_Model_DbTable_Email();
    }

    protected function run()
    {
        $mailPool = $this->_mailDb->fetchAll(array('Inviata = ?' => false, 'Tentativi <= ?' => 10));

        foreach($mailPool as $mail)
        {
            $mail = $mail->toArray();
            $mailSender = new AddiXi_Mail($mail['Mittente'], $mail['Destinatario'], $mail['Oggetto'], $mail['Body']);
            
            if($mailSender->send())
                $mail['Inviata'] = true;

            $timestamp = new Zend_Date();
            $mail['Ultimo Tentativo'] = $timestamp->get('yyyyMMddHHmmss');
            $mail['Tentativi']++;
            
            $this->_mailDb->update($mail, array('idEmail = ?' => $mail['idEmail']));
        }

        return true;
    }
}
?>
