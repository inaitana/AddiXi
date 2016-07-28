<?php

class Application_Model_DbTable_MotoriRicerca extends Zend_Db_Table_Abstract
{
    protected $_name = 'Motori di ricerca';
    protected $_rowClass = 'MotoreRicercaRow';

    public function __construct()
    {
        parent::__construct();
    }
}

class MotoreRicercaRow extends Zend_Db_Table_Row_Abstract
{
    function updateTimestamp() {
        $timestamp = new Zend_Date();
        $this->{'Ultimo Ping Sitemap'} = $timestamp->get('yyyyMMddHHmmss');
        $this->save();        
        return $timestamp->toString();
    }
}