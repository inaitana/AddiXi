<?php

class Application_Model_DbTable_MovimentiArticoli extends Zend_Db_Table_Abstract
{
    protected $_name = 'Movimenti Articoli';
    protected $_rowClass = 'MovimentoArticoloRow';
    
    protected $_referenceMap = array(
            'Movimento' => array(
                'columns'	=> array('idMovimento'),
                'refTableClass'	=> 'Application_Model_DbTable_Movimenti',
                'refColumns'	=> array('idMovimento')
            ),
            'Articolo Specifico' => array(
                'columns'	=> array('idArticoloSpecifico'),
                'refTableClass'	=> 'Application_Model_DbTable_ArticoliSpecifici',
                'refColumns'	=> array('idArticoloSpecifico')
            )
    );
    
    protected $_dependentTables = array('Application_Model_DbTable_LineeVendita');
}

class MovimentoArticoloRow extends Zend_Db_Table_Row_Abstract
{
    public function getArticolo()
    {
        return $this->findParentRow('Application_Model_DbTable_ArticoliSpecifici');
    }

    public function checkAvailability()
    {
        $articolo = $this->getArticolo();
        if($this->{'Quantità'} > $articolo->{'Quantità'})
            return false;
        else
            return true;
    }

    public function updateAvailableQuantity()
    {
        $articolo = $this->getArticolo();
        $articolo->{'Quantità'} -= $this->{'Quantità'};
        $articolo->save();
    }
}