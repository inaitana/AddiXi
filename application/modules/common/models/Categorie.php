<?php
class Application_Model_Categorie {
    protected $_categoriesDb = null;
    protected $_categoriesList = null;
    
    public function __construct()
    {
        $this->_categoriesDb = new Application_Model_DbTable_Categorie();
    }

    public function getCategory($categoryId)
    {
        if(!is_numeric($categoryId) && $categoryId != '')
        {
            $className = "Application_Model_CategorieSpeciali_".$categoryId;
            return new $className();
        }
        else
            return $this->_categoriesDb->find($categoryId)->current();
    }

    public function countCategories ()
    {
        return $this->_categoriesDb->fetchAll()->count();
    }

    public function getArticle($articleId)
    {
        return $this->_specificArticlesDb->find(str_replace('art','',$articleId))->current();
    }

    public function getCategories($onlyFathers = false)
    {
        $selectCategories = Zend_Registry::get('db')->select()->from('Categorie Articoli')->joinUsing('Nomi Categorie', 'idCategoria')->joinUsing('Descrizioni Categorie', 'idCategoria');

        if($onlyFathers)
            $selectCategories = $selectCategories->where('idCategoriaPadre IS NULL');
     
        $selectCategories = $selectCategories->order('idCategoriaPadre')->order('Ordine')->order('Nome');

        return $selectCategories->query()->fetchAll();
    }

    public function getCategoriesIds()
    {
        return $this->_categoriesDb->select()->from('Categorie Articoli', 'idCategoria')->order('idCategoriaPadre')->query()->fetchAll();
    }

    public function getCategoriesList()
    {
        if($this->_categoriesList == null)
            $this->buildCategoriesList();
        
        return $this->_categoriesList;
    }

    protected function buildCategoriesList()
    {
        if($this->_categoriesList == null)
        {
            $categories = $this->getCategories();

            foreach($categories as $category)
                $this->_categoriesList[$category['idCategoriaPadre']][$category['idCategoria']] = $category;
        }        
    }
}
?>
