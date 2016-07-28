<?php

class Application_Model_DbTable_Impostazioni extends Zend_Db_Table_Abstract
{
    protected $_name = 'Impostazioni';

    public function __construct()
    {
        parent::__construct();
        // $this->initializeDb();
    }

    public function initializeDb()
    {
        if($this->fetchRow(array('Nome = ?' => 'Titolo Base')) == null)
        {
            $config = Zend_Registry::get('config');
            $this->insert(array('Nome' => 'Titolo Base', 'Valore' => $config->shopName.' Shopping Online'));
        }

        if($this->fetchRow(array('Nome = ?' => 'Aggiornato')) == null)
            $this->insert(array('Nome' => 'Aggiornato', 'Valore' => true));
    }
}
?>
