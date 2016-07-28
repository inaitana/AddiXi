<?php

class Application_Model_DbTable_Clienti extends Zend_Db_Table_Abstract
{
    protected $_name = 'Clienti';

    protected $_referenceMap = array(
            'Utente' => array(
                'columns'	=> array('idUtente'),
                'refTableClass'	=> 'Application_Model_DbTable_Utenti',
                'refColumns'	=> array('idUtente')
            )
    );
    
    protected $_dependentTables = array('Application_Model_DbTable_Vendite','Application_Model_DbTable_Utenti');
}

