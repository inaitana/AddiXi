<?php

class Admin_IndexController extends Zend_Controller_Action
{
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
        $this->view->registerHelper(new Admin_View_Helper_ArticlesReminder(), 'ArticlesReminder');
        $this->view->registerHelper(new Admin_View_Helper_OrdersReminder(), 'OrdersReminder');
    }

    public function indexAction()
    {
    	exit;
    	
    	$imagesModel = new Admin_Model_Immagini();
    	    	
    	$dbImages = $imagesModel->getPathsFromDb();    	
    	$fsImages = $imagesModel->getPathsFromFs();
    	
    	$okImages = array_intersect($dbImages, $fsImages);
    	
    	$fsExtra = array_diff($fsImages, $okImages);
    	echo "<pre>";
    	var_dump($okImages);
    	
    	$dbExtra = array_diff($dbImages, $okImages);
    	
    	foreach ($dbExtra as $dbImage)
    		$imagesModel->deleteImage($dbImage['Path']);
    	
    	foreach($fsExtra as $fsImage)
    		$imagesModel->addImage($fsImage['Path']);
    		
    	
    	
    	
    	
    	
    	
    	
        $imagesModel = new Admin_Model_Immagini();
    	
        if(!$this->view->ajax)
            $this->getHelper('viewRenderer')->setNoRender();
    }    

    public function infoAction()
    {
        $articlesModel = new Admin_Model_Articoli();
        $categoriesModel = new Admin_Model_Categorie();
        $salesModel = new Admin_Model_Vendite();

        $this->view->countArticoli = $articlesModel->countArticles();
        $this->view->countCategorie = $categoriesModel->countCategories();
        $this->view->countVendite = $salesModel->countSales();
    }
}

