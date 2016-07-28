<?php

class Application_Model_DbTable_Email extends Zend_Db_Table_Abstract
{
    protected $_name = 'Email';
    protected $_rowClass = 'EmailRow';

    public function __construct()
    {
        parent::__construct();
    }

    public function addMail($from, $to, $subject, $body)
    {
        $data = array(
            'Mittente' => $from,
            'Destinatario' => $to,
            'Oggetto' => $subject,
            'Body' => $body
        );

        $this->insert($data);
    }
    
    public function insert($data)
    {
        $data['Mittente'] = serialize($data['Mittente']);
        $data['Destinatario'] = serialize($data['Destinatario']);
        
        parent::insert($data);

        $mailSender = new Application_Cron_SendMail();
        $mailSender->doJob();
    }

    public function update($data, $where)
    {
        $data['Mittente'] = serialize($data['Mittente']);
        $data['Destinatario'] = serialize($data['Destinatario']);

        parent::update($data, $where);
    }
}

class EmailRow extends Zend_Db_Table_Row_Abstract
{
    public function toArray()
    {
        $data = parent::toArray();
        $data['Mittente'] = unserialize($data['Mittente']);
        $data['Destinatario'] = unserialize($data['Destinatario']);
        return $data;
    }
}