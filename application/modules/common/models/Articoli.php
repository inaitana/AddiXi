<?php
class Application_Model_Articoli {
    protected $_articlesDb;

    public function __construct() {
        $this->_articlesDb = new Application_Model_DbTable_ArticoliView();
    }

    public function getArticles ($category = null, $order = NULL, $onlyActive = true, $onlyCategory = false)
    {
        if($category == null)
        {
            if($onlyActive)
                $where = 'Attivo = TRUE';
            else
                $where = null;

            return $this->_articlesDb->fetchAll($where,$order);
        }
        else
            return $category->getArticles(false, $order, $onlyActive, $onlyCategory);
    }

    public function countArticles ()
    {
        return $this->_articlesDb->fetchAll()->count();
    }

    public function findArticle ($id)
    {
        $articleArray = $this->_articlesDb->find($id)->current()->toArray();

        $articleArray['Codice'] = $articleArray['Codice Articolo'];
        unset($articleArray['Codice Articolo']);

        $articleArray['Prezzo'] = $articleArray['Prezzo di vendita'];
        unset($articleArray['Prezzo di vendita']);

        $articleArray['fuoriProduzione'] = $articleArray['Fuori Produzione'];
        unset($articleArray['Fuori Produzione']);

        return $articleArray;
    }
}
?>