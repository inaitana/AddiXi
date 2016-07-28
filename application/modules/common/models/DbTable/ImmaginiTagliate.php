<?php

class Application_Model_DbTable_ImmaginiTagliate extends Zend_Db_Table_Abstract
{
    protected $_name = 'Immagini Tagliate';


    protected $_referenceMap = array(
            'Immagini'  => array(
                'columns'       => array('idImmagine'),
                'refTableClass'	=> 'Application_Model_DbTable_Immagini',
                'refColumns'	=> array('idImmagine')
            )
    );
    
    protected $_dependentTables = array('Application_Model_DbTable_ArticoliSpecifici');
}
