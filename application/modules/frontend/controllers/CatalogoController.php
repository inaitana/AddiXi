<?php

class CatalogoController extends Zend_Controller_Action
{
    protected $_catalogModel = null;
    protected $_categoryModel = null;
    protected $_brandModel = null;
    protected $_catalogNavigation = null;
    protected $_activeCategoryPage = null;
    protected $_cartModel = null;

    public function preDispatch()
    {
        if($this->_request->getParam('ajax'))
        {
            $this->view->layout()->disableLayout();
            $this->view->ajax = true;
        }
        else
            $this->view->ajax = false;
    }

    public function init()
    {
        $config = Zend_Registry::get('config');
	
        $this->_catalogModel = new Frontend_Model_Catalogo();

        $this->_categoryModel = new Application_Model_Categorie();

        $this->_brandModel = new Application_Model_Marche();

        $this->_cartModel = new Frontend_Model_Carrello();
        
        $categoryPath = $this->_request->getParam('categoryPath');
        if(substr($categoryPath,0,strpos($categoryPath,'/')) == 'index')
        {
        	$this->_request->setParam('categoryPath', '');
        	
        	if($this->_request->getParam('page') == null && strpos($categoryPath,'page') != -1)
        		$this->_request->setParam('page',substr($categoryPath,strrpos($categoryPath,'/') +1));
        		
        	$this->_request->setActionName('index');
        }

        $this->_catalogNavigation = $this->_catalogModel->getCatalogNavigation();
        Zend_Registry::set('catalogNavigation', $this->_catalogNavigation);
        
        $this->view->mostraCarrello = true;
        
        if($this->_request->getParam('cart')==='true' || $this->_request->getParam('cart')==='1')
        {
            $this->view->mostraDettaglioCarrello = true;
            $this->_cartModel->addCartJavascript('true');
        }
        else
        {
            $this->view->mostraDettaglioCarrello = false;
            $this->_cartModel->addCartJavascript('false');
        }

        $this->view->layout()->config = $config;

        Zend_Registry::set('defaultImage',"/immagini/default300.jpg");
        Zend_Registry::set('defaultThumb', "/immagini/thumbs/default300.jpg");

        $browserSession = new Zend_Session_Namespace('Browser');
        
        if($browserSession->IE)
            Zend_Registry::set('useBase64', false);
        else
            Zend_Registry::set('useBase64', true);
    }

    public function indexAction()
    {
        $this->view->headTitle("Catalogo | ");
        
        $category = $this->_categoryModel->getCategory('CatalogoHome');

        $this->view->categoria = $category;

        $this->_catalogNavigation = $this->_catalogModel->getCategoryNavigation('CatalogoHome');

        $paginatorAdapter = $this->_catalogModel->getPaginatorAdapter($category,array("Marca","Nome"));

        $paginator = new Zend_Paginator($paginatorAdapter);
 //       $paginator->setCache($this->_cache);
        $paginator->setCurrentPageNumber($this->_request->getParam('page'));
        $this->view->paginator = $paginator;

        $paginatorArticles = $paginator->getCurrentItems();
        $this->view->articoli = $paginatorArticles;

        $this->view->navigation($this->_catalogNavigation);
    }

    public function categoriaAction()
    {
        $this->_activeCategoryPage = $this->_catalogNavigation->findOneByUri('/catalogo/'.rtrim($this->_request->getParam('categoryPath'),'/'));

        if($this->_activeCategoryPage==null)
            throw new Zend_Controller_Action_Exception('',404);

        $this->_activeCategoryPage->active = true;

        $category = $this->_categoryModel->getCategory($this->_activeCategoryPage->id);

        $category->setNome($this->_activeCategoryPage->label);
        $category->setDescrizione($this->_activeCategoryPage->title);

        $this->view->categoria = $category;

        $this->_catalogNavigation = $this->_catalogModel->getCategoryNavigation($category);

        $paginatorAdapter = $this->_catalogModel->getPaginatorAdapter($category,array("Marca","Nome"));

        $paginator = new Zend_Paginator($paginatorAdapter);
//        $paginator->setCache(Zend_Registry::get('cache_core'));
        $paginator->setCurrentPageNumber($this->_request->getParam('page'));
        
        $this->view->paginator = $paginator;

        $paginatorArticles = $paginator->getCurrentItems();
        $this->view->articoli = $paginatorArticles;

        $this->view->navigation($this->_catalogNavigation);

        $this->view->headTitle($category->getNome()." | ");
    }

    public function marcaAction()
    {
        $brand = $this->_brandModel->getBrandByName($this->_request->getParam('brand'));

        if($brand==null)
            throw new Zend_Controller_Action_Exception('',404);

        $this->view->marca = $brand;

        $this->_catalogNavigation = $this->_catalogModel->getBrandNavigation($brand);

        $paginatorAdapter = $this->_catalogModel->getPaginatorAdapter($brand,array("Nome"));

        $paginator = new Zend_Paginator($paginatorAdapter);
 //       $paginator->setCache($this->_cache);
        $paginator->setCurrentPageNumber($this->_request->getParam('page'));
        $this->view->paginator = $paginator;

        $paginatorArticles = $paginator->getCurrentItems();
        $this->view->articoli = $paginatorArticles;

        $this->view->navigation($this->_catalogNavigation);

        $this->view->headTitle($brand->Nome." | ");
    }

    public function articoloAction()
    {
        $this->_activeCategoryPage = $this->_catalogNavigation->findOneByUri('/catalogo/'.rtrim($this->_request->getParam('categoryPath'),'/'));

        if($this->_activeCategoryPage==null)
            throw new Zend_Controller_Action_Exception('',404);
        
        $article = $this->_catalogModel->findArticleByName($this->_request->getParam('articleName'),$this->_activeCategoryPage->id);
        
        if($article == null)
            throw new Zend_Controller_Action_Exception('',404);
        
        $this->view->articolo = $article;

        $this->_catalogNavigation = $this->_catalogModel->getArticleNavigation($article, true);
/*
        $category = $this->_categoryModel->getCategory($this->_activeCategoryPage->id);
        $this->view->categoria = $category;
*/
        $this->view->navigation($this->_catalogNavigation);

        $this->view->headTitle($article->Marca." ".$article->Nome." | ".$this->_activeCategoryPage->label." | ");
    }

    public function updatebreadcrumbAction()
    {
        if(preg_match("|/catalogo/(.*)/(\w*).html|",$this->_request->getParam('step'),$matches_array))
        {
            $this->_activeCategoryPage = $this->_catalogNavigation->findOneByUri("/catalogo/".$matches_array[1]);
            $article = $this->_catalogModel->findArticleByName($matches_array[2],$this->_activeCategoryPage->id);

            $this->_catalogNavigation = $this->_catalogModel->getArticleNavigation($article, true);
        }
        else
        {
            $this->_activeCategoryPage = $this->_catalogNavigation->findOneByUri(rtrim($this->_request->getParam('step'),'/'));
            $this->_activeCategoryPage->active = true;
        }

        $this->view->navigation($this->_catalogNavigation);
    }

    public function sitemapAction()
    {
        $this->view->layout()->disableLayout();
        $navigation = $this->_catalogModel->getCompleteNavigation();
        $this->view->navigation($navigation);
    }
}
