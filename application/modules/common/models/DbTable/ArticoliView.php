<?php

class Application_Model_DbTable_ArticoliView extends Zend_Db_Table_Abstract
{
    protected $_name = 'Articoli View';
    protected $_primary = 'idArticoloSpecifico';
    protected $_rowClass = 'ArticleViewRow';
    
    protected $_referenceMap = array(
            'Categoria' => array(
                'columns'	=> array('idCategoria'),
                'refTableClass'	=> 'Application_Model_DbTable_Categorie',
                'refColumns'	=> array('idCategoria')
            )
    );

    public function findArticleByName($article_name, $category_id)
    {
        return $this->fetchRow($this->select()->where("CONCAT(Marca,'_',Nome) LIKE ?",$article_name)->where('idCategoria = ?',$category_id));
    }
}

class ArticleViewRow extends Zend_Db_Table_Row_Abstract
{
    public function getNome()
    {
        return $this->Nome;
    }

    public function getDescrizione()
    {
        return $this->Descrizione;
    }

    public function getCategoria()
    {
        $categorieDb = new Application_Model_DbTable_Categorie();
        return $categorieDb->find($this->idCategoria)->current();
    }
}