<?php

class Application_Model_DbTable_ArticoliSpecifici extends Zend_Db_Table_Abstract
{

    protected $_name = 'Articoli Specifici';
    protected $_rowClass = 'SpecificArticleRow';

    protected $_referenceMap = array(
            'ArticoloGenerico'  => array(
                'columns'       => array('idArticolo'),
                'refTableClass'	=> 'Application_Model_DbTable_ArticoliGenerici',
                'refColumns'	=> array('idArticolo')
            ),
            'ImmagineTagliata'  => array(
                'columns'       => array('idImmagineTagliata'),
                'refTableClass' => 'Application_Model_DbTable_ImmaginiTagliate',
                'refColumns'    => array('idImmagineTagliata')
            )
    );

	protected $_dependentTables = array('Application_Model_DbTable_NomiArticoli','Application_Model_DbTable_DescrizioniArticoli');
}

class SpecificArticleRow extends Zend_Db_Table_Row_Abstract
{
    public function getNome()
    {
        return $this->findDependentRowset('Application_Model_DbTable_NomiArticoli')->current()->Nome;
    }

    public function getDescrizione()
    {
        return $this->findDependentRowset('Application_Model_DbTable_DescrizioniArticoli')->current()->Descrizione;
    }

    public function getArticoloGenerico()
    {
        $articoliGenericiDb = new Application_Model_DbTable_ArticoliGenerici();
        return $articoliGenericiDb->find($this->idArticolo)->current();
    }

    public function getCategoria()
    {
        return $this->getArticoloGenerico()->getCategoria();
    }
}