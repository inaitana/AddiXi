<?php
class Application_Model_CategorieSpeciali_Offerte
{
    protected $_nome = NULL;
    protected $_descrizione = NULL;
    public $idCategoria = 'Offerte';
    
    public function getNome()
    {
        return $this->_nome;
    }

    public function getDescrizione()
    {
        return $this->_descrizione;
    }

    public function setNome($nome)
    {
        $this->_nome = $nome;
    }

    public function setDescrizione($descrizione)
    {
        $this->_descrizione = $descrizione;
    }

    public function getChildren()
    {
        return null;
    }

    public function getArticles($generici = false, $order = NULL)
    {
        $articlesDb = new Application_Model_DbTable_ArticoliView();
        return $articlesDb->fetchAll($this->getArticlesSelect($order));
    }

    public function getArticlesSelect($order = NULL)
    {
        $articlesDb = new Application_Model_DbTable_ArticoliView();
        $selectArticles = $articlesDb->select()->where('Offerta = TRUE')->where('Attivo = TRUE');
        if($order!==NULL)
            $selectArticles = $selectArticles->order($order);
        return $selectArticles;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(),array('Nome' => $this->getNome(), 'Descrizione' => $this->getDescrizione()));
    }
}