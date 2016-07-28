<?php

class Admin_ArticoliController extends Zend_Controller_Action
{
    protected $_articlesModel = null;
    protected $_categoriesModel = null;
    protected $_brandsModel = null;
    protected $_categoryId = null;

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
        $this->_categoriesModel = new Admin_Model_Categorie();
        $this->_articlesModel = new Admin_Model_Articoli();
        $this->_brandsModel = new Admin_Model_Marche();
        
        $this->_categoryId = $this->_request->getParam('categoria');
        if($this->_categoryId==null && $this->_request->getActionName()!='index' && !$this->_request->getParam('ajax'))
        {
            $this->_forward('index','articoli','admin');
        }

        $this->view->config = Zend_Registry::get('config');
        $this->view->configAdmin = Zend_Registry::get('configAdmin');
        
        $this->view->headTitle('Gestione Articoli | ');
    }

    public function indexAction()
    {
        if(!$this->view->ajax)
            $this->getHelper('viewRenderer')->setNoRender();

        $this->view->registerHelper(new Admin_View_Helper_ArticlesReminder(), 'ArticlesReminder');
    }

    public function addAction()
    {
        $this->view->title = "Aggiungi un nuovo articolo";
        $this->view->headTitle($this->view->title);

        $category = $this->_categoriesModel->getCategory($this->_categoryId);
        $this->view->categoria = $category;
        
        $form = new Admin_Form_Articolo('add');
        $form->setAction($this->_helper->url->url(array('module' => 'admin','controller' => 'articoli', 'action' => 'add'),'default',true));
        $form->idCategoria->setValue($this->_categoryId);

        $form->idMarca->addMultiOptions(array($this->view->config->language->brandNamePlur => $this->_brandsModel->getSelectOptions()));
        $form->idMarca->addMultiOption('nuova','Nuova '.$this->view->config->language->brandNameSing);
        
        $form->Attivo->setValue('1');
        
        $this->view->form = $form;

        if($this->getRequest()->isPost() && $this->getRequest()->getParam('conferma')) {
            $request = $this->getRequest()->getPost();

            if($this->view->ajax)
                $form->removeElement('immagine');
            
            if($form->isValid($request)) {
                $this->_articlesModel->addArticle($form->getValues());
                $this->_helper->cacheCleaner();

                if($this->view->ajax)
                {
                    $this->getHelper('viewRenderer')->setNoRender();
                    echo $this->_request->getParam('categoria');
                }
                else
                    $this->_forward('list','articoli','admin',array('categoria' => $this->_request->getParam('categoria')));
            }
            else
                $form->populate($request);
        }
    }

    public function editAction()
    {
        if($this->_request->getParam('articolo')!= NULL)
            $articleId = $this->_request->getParam('articolo');
        else if(!$this->_request->getParam('ajax'))
            $this->_forward('list','articoli','admin',array('categoria' => $this->_request->getParam('categoria')));

        $articolo = $this->_articlesModel->findArticle($articleId);
        
        $this->view->title = "Edita un articolo";
        $this->view->headTitle($this->view->title);

        $category = $this->_categoriesModel->getCategory($this->_categoryId);
        $this->view->categoria = $category;
        $this->view->image = $articolo['Path Immagine'];
        $this->view->articolo = $articolo;
        
        $form = new Admin_Form_Articolo('edit',$articleId);
        $form->setAction($this->_helper->url->url(array('module' => 'admin','controller' => 'articoli', 'action' => 'edit'),'default',true));
        
        $form->idNuovaCategoria->addMultiOptions($this->_categoriesModel->getSelectOptions());
        $form->idNuovaCategoria->setValue($this->_categoryId);
        $form->idMarca->addMultiOptions(array($this->view->config->language->brandNamePlur => $this->_brandsModel->getSelectOptions()));
        $form->idMarca->addMultiOption('nuova','Nuova '.$this->view->config->language->brandNameSing);
        $form->idMarca->setValue($articolo['idMarca']);
        $form->idImmagine->setValue($articolo['idImmagineTagliata']);

        if($this->getRequest()->isPost() && $this->getRequest()->getParam('conferma')) {
            $request = $this->getRequest()->getPost();

            if($this->view->ajax)
                $form->removeElement('immagine');
            
            if($form->isValid($request)) {
                $this->_articlesModel->editArticle($form->getValues());
                $this->_helper->cacheCleaner();

                if($this->view->ajax)
                {
                    $this->getHelper('viewRenderer')->setNoRender();
                    echo $this->_request->getParam('idNuovaCategoria');
                }
                else
                    $this->_forward('list','articoli','admin',array('categoria' => $this->_request->getParam('idNuovaCategoria')));
            }
            else
            {
                $form->populate($request);
            }
        }
        else
        {
            $form->populate($articolo);
        }
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        if($this->_request->getParam('articolo')!= NULL)
            $articleId = $this->_request->getParam('articolo');
        else if(!$this->_request->getParam('ajax'))
            $this->_forward('list','articoli','admin',array('categoria' => $this->_request->getParam('categoria')));

        $article = $this->_articlesModel->findArticle($articleId);
        $this->view->articolo = $article;
        
        $this->view->title = "Cancella un articolo";
        $this->view->headTitle($this->view->title);;

        $form = new Admin_Form_Articolo('delete',$articleId);
        $form->setAction($this->_helper->url->url(array('module' => 'admin','controller' => 'articoli', 'action' => 'delete'),'default',true));

        $this->view->conferma = false;

        $this->view->canDelete = $this->_articlesModel->canDelete($articleId);
        
        if($this->getRequest()->isPost() && $this->getRequest()->getParam('conferma'))
        {
            $request = $this->getRequest()->getPost();
            if($form->isValid($request)) {
                
                if($this->view->canDelete)
                    $this->_articlesModel->deleteArticle($articleId);
                else
                    $this->_articlesModel->activateArticle($articleId, false);

                $this->_helper->cacheCleaner();
                $this->view->conferma = true;
                if($this->view->ajax)
                {
                    $this->getHelper('viewRenderer')->setNoRender();
                    echo $this->_request->getParam('categoria');
                }
                else
                    $this->_forward('list','articoli','admin',array('categoria' => $this->_request->getParam('categoria')));
            }
            else
            {
                $form->populate($request);
                $this->view->form = $form;
            }
        }
        else
        {
            $this->view->form = $form;
        }
    }

    public function activateAction()
    {
        if($this->getRequest()->isPost())
        {
            $this->getHelper('viewRenderer')->setNoRender();
            $articleId = $this->_request->getParam('articolo');
            $this->_articlesModel->activateArticle($articleId, true);
            $this->_helper->cacheCleaner();
        }
    }

    public function deactivateAction()
    {
        if($this->getRequest()->isPost())
        {
            $this->getHelper('viewRenderer')->setNoRender();
            $articleId = $this->_request->getParam('articolo');
            $this->_articlesModel->activateArticle($articleId, false);
            $this->_helper->cacheCleaner();
        }
    }

    public function listAction()
    {
        $this->view->title = "Lista Articoli";

        $category = $this->_categoriesModel->getCategory($this->_categoryId);
        $this->view->categoria = $category;
        $this->view->title .= " ".$category->getNome();
        
        $this->view->headTitle($this->view->title);

        $this->view->articoli = $this->_articlesModel->getArticles($category, array("Marca","Nome"), false, true);
    }
}









