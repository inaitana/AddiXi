<?php
class Frontend_Model_Catalogo {
    protected $_categoriesModel = null;
    protected $_config = null;
    protected $_cache = null;
    protected $_categoriesList = null;
    protected $_catalogNavigation = null;
    protected $_completeNavigation = null;

    public function __construct()
    {
        $this->_categoriesModel = new Application_Model_Categorie();
        $this->_config = Zend_Registry::get('config');
        $this->_cache = Zend_Registry::get('cache_core');

        if($this->_cache->test('categoriesNavigation'))
            $this->_catalogNavigation = $this->_cache->load('categoriesNavigation');

        if($this->_cache->test('completeNavigation'))
            $this->_completeNavigation = $this->_cache->load('completeNavigation');
    }

    public function getCatalogNavigation()
    {
        if(!isset($this->_catalogNavigation) || $this->_catalogNavigation == null)
        {
            $this->_catalogNavigation = Zend_Registry::get('mainNav');
            $this->_catalogNavigation->findOneById('CatalogoHome')->addPages($this->buildCategoriesNavigation()->getPages());
            $this->_cache->save($this->_catalogNavigation,'categoriesNavigation');
        }
        return $this->_catalogNavigation;
    }

    public function getCompleteNavigation()
    {
        if(!isset($this->_completeNavigation) || $this->_completeNavigation == null)
        {
            $this->_catalogNavigation = $this->getCatalogNavigation();
            $categories = $this->_categoriesModel->getCategoriesIds();

            foreach($categories as $category)
                $this->addArticles($category['idCategoria'], true);
            
            $this->_completeNavigation = $this->_catalogNavigation;
            $this->_cache->save($this->_completeNavigation,'completeNavigation');
        }
        return $this->_completeNavigation;
    }

    public function getCategoryNavigation($category)
    {
        if(is_string($category))
            $categoryId = $category;
        else
            $categoryId = $category->idCategoria;
        
        if(!($this->_cache->test('category'.$categoryId.'Navigation')))
        {
            $this->addArticles($category);
            $this->_cache->save($this->_catalogNavigation, 'category'.$categoryId.'Navigation');
        }
        else
            $this->_catalogNavigation = $this->_cache->load('category'.$categoryId.'Navigation');

        return $this->_catalogNavigation;
    }

    public function getBrandNavigation($brand)
    {
        if(!($this->_cache->test('brand'.$brand->idMarca.'Navigation')))
        {
            $this->addArticles($brand);
            $this->_cache->save($this->_catalogNavigation, 'brand'.$brand->idMarca.'Navigation');
        }
        else
            $this->_catalogNavigation = $this->_cache->load('brand'.$brand->idMarca.'Navigation');

        return $this->_catalogNavigation;
    }

    public function getArticleNavigation($article, $setActive = false)
    {
        $this->addArticleToNavigationEntry($article, $setActive);
        
        return $this->_catalogNavigation;
    }

    protected function buildCategoriesNavigation($rel_path = "/catalogo/")
    {
        $this->_categoriesList = $this->_categoriesModel->getCategoriesList();

        $categoriesNavigationTree = $this->buildNavigationBranch(null, $rel_path);
        
        $categoriesNavigationTree[] = $this->addSpecialOffers($rel_path);
        
        return new Zend_Navigation($categoriesNavigationTree);
    }

    protected function buildNavigationBranch($rootCategory, $rel_path)
    {
        $categoriesNavigationBranch = array();
        
        foreach($this->_categoriesList[$rootCategory] as $categoryId => $category)
        {
            if($category['Attivo'])
            {
                $categoryEntry = $this->makeNavigationPage($category, $rel_path);

                if(isset($this->_categoriesList[$categoryId]))
                {
                    $categoryEntry['class'] .= ' padre';
                    $categoryEntry['pages'] = $this->buildNavigationBranch($categoryId, $categoryEntry['uri']."/");
                }

                $categoriesNavigationBranch[] = $categoryEntry;
            }
        }
        
        return $categoriesNavigationBranch;
    }

    protected function makeNavigationPage($categoryEntry, $rel_path)
    {
        $categoryPage['id'] = $categoryEntry['idCategoria'];
        $categoryPage['label'] = $categoryEntry['Nome'];
        $categoryPage['title'] = strip_tags($categoryEntry['Descrizione']);
        $categoryPage['uri'] = $rel_path.preg_replace("/[^\w]/","_",$categoryPage['label']);
        $categoryPage['class'] = 'catalogo';

        return $categoryPage;
    }

    protected function addSpecialOffers($rel_path)
    {
        $categoryPage['id'] = 'Offerte';
        $categoryPage['label'] = $this->_config->articles->offers->name;
        $categoryPage['title'] = strip_tags($this->_config->articles->offers->description);
        $categoryPage['uri'] = $rel_path."Offerte";
        $categoryPage['class'] = 'catalogo categoriaOfferta';

        return $categoryPage;
    }

    protected function addArticles($container, $forNavigation = false)
    {
        if(is_string($container))
            $container = $this->_categoriesModel->getCategory($container);
        else
        {
            $containerClass = get_class($container);

            if($containerClass == "Application_Model_DbTable_Categorie")
            {
            }
            else if ($containerClass == "Application_Model_DbTable_Marche")
            {
            }
        }
        
        $articles = $container->getArticles(null, null, null, $forNavigation);

        if($articles != null && count($articles))
            foreach($articles as $article)
                $this->addArticleToNavigationEntry($article, false);
    }

    protected function addArticleToNavigationEntry($article, $setActive = false)
    {        
        $articleEntry['id'] = 'art'.$article->idArticoloSpecifico;
        $articleEntry['label'] = $article->Marca." ".$article->getNome();
        $articleEntry['title'] = strip_tags($article->getDescrizione());
        $articleEntry['class'] = 'catalogo';
        $articleEntry['visible'] = false;
        $articleEntry['active'] = $setActive;

        $categoryEntry = $this->_catalogNavigation->findOneById($article->idCategoria);

        if($categoryEntry !== null)
        {
            $articleEntry['uri'] = $categoryEntry->uri.'/'.preg_replace("/[^\w]/","_",$articleEntry['label']).".html";
            $categoryEntry->addPage($articleEntry);
        }
    }

    public function getPaginatorAdapter($container, $order = NULL)
    {
        $selectArticles = $container->getArticlesSelect($order);
        return new Zend_Paginator_Adapter_DbSelect($selectArticles);
    }

    public function findArticleByName($article_name, $category_id)
    {
        $articlesViewDb = new Application_Model_DbTable_ArticoliView();
        return $articlesViewDb->findArticleByName($article_name, $category_id);
    }
}
?>
