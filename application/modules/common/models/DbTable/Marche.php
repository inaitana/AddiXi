<?php

class Application_Model_DbTable_Marche extends Zend_Db_Table_Abstract
{
    protected $_name = 'Marche';
    protected $_rowClass = 'BrandRow';
    
    protected $_dependentTables = array('Application_Model_DbTable_ArticoliGenerici');
}

class BrandRow extends Zend_Db_Table_Row_Abstract
{
    public function getArticles($generici = false, $order = NULL, $onlyActive = true, $onlyBrand = false)
    {
        $articlesDb = new Application_Model_DbTable_ArticoliView();
        return $articlesDb->fetchAll($this->getArticlesSelect($order, $onlyActive, $onlyBrand));
    }

    public function getArticlesSelect($order = NULL, $onlyActive = true, $onlyBrand = false)
    {
        $articlesDb = new Application_Model_DbTable_ArticoliView();

        $config = Zend_Registry::get('config');

        $selectArticles = $articlesDb->select()->where('idMarca = ?', $this->idMarca);

        if($onlyActive)
            $selectArticles = $selectArticles->where('Attivo = TRUE');

        if($order!==NULL)
            $selectArticles = $selectArticles->order($order);

        return $selectArticles;
    }
}

