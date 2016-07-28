<?php

class Application_Model_DbTable_NomiArticoli extends Zend_Db_Table_Abstract
{

    protected $_name = 'Nomi Articoli';

    protected $_referenceMap = array(
            'ArticoloGenerico' => array(
                'columns'	=> array('idArticolo'),
                'refTableClass'	=> 'Application_Model_DbTable_ArticoliGenerici',
                'refColumns'	=> array('idArticolo')
            ),
            'ArticoloSpecifico' => array(
                'columns'	=> array('idArticoloSpecifico'),
                'refTableClass'	=> 'Application_Model_DbTable_ArticoliSpecifici',
                'refColumns'	=> array('idArticoloSpecifico')
            )
    );
}

