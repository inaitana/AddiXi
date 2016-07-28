<?php

class Application_Model_DbTable_Movimenti extends Zend_Db_Table_Abstract
{
    protected $_name = 'Movimenti';
    protected $_rowClass = 'MovimentiRow';

    protected $_dependentTables = array('Application_Model_DbTable_Vendite','Application_Model_DbTable_MovimentiArticoli');
}

class MovimentiRow extends Zend_Db_Table_Row_Abstract
{
    public function getArticlesFlows()
    {
        return $this->findDependentRowset('Application_Model_DbTable_MovimentiArticoli');
    }

    public function delete()
    {
        foreach($this->getArticlesFlows() as $articleFlow)
            $articleFlow->delete();
        parent::delete();
    }

    public function updateAvailableQuantities()
    {
        foreach($this->getArticlesFlows() as $articleFlow)
            $articleFlow->updateAvailableQuantity();
    }
}