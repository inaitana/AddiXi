<?php

class Application_Model_DbTable_ArticoliGenerici extends Zend_Db_Table_Abstract
{

    protected $_name = 'Articoli Generici';
    protected $_rowClass = 'GenericArticleRow';

    protected $_referenceMap = array(
            'Categoria' => array(
                'columns'	=> array('idCategoria'),
                'refTableClass'	=> 'Application_Model_DbTable_Categorie',
                'refColumns'	=> array('idCategoria')
            ),
            'Marca' => array(
                'columns'	=> array('idMarca'),
                'refTableClass'	=> 'Application_Model_DbTable_Marche',
                'refColumns'	=> array('idMarca')
            )
    );
    
	protected $_dependentTables = array('Application_Model_DbTable_ArticoliSpecifici','Application_Model_DbTable_NomiArticoli','Application_Model_DbTable_DescrizioniArticoli');
}

class GenericArticleRow extends Zend_Db_Table_Row_Abstract
{
    public function getNome()
    {
        return $this->findDependentRowset('Application_Model_DbTable_NomiArticoli')->current()->Nome;
    }

    public function getDescrizione()
    {
        return $this->findDependentRowset('Application_Model_DbTable_DescrizioniArticoli')->current()->Descrizione;
    }

    public function getArticoliSpecifici()
    {
        return $this->findDependentRowset('Application_Model_DbTable_ArticoliSpecifici');
    }

    public function getCategoria()
    {
        $categorieDb = new Application_Model_DbTable_Categorie();
        return $categorieDb->find($this->idCategoria)->current();
    }
}