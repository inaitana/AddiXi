<?php

class Application_Model_DbTable_Immagini extends Zend_Db_Table_Abstract
{
    protected $_name = 'Immagini';
    
	protected $_dependentTables = array('Application_Model_DbTable_ImmaginiTagliate');
}