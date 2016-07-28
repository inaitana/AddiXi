<?php

class Admin_CategorieController extends Zend_Controller_Action
{
    protected $_categoriesModel = null;

    public function preDispatch()
    {
        if($this->_request->getParam('ajax'))
        {
            $this->view->layout()->disableLayout();
            $this->view->ajax = true;
        }
        else
        {
            $this->_helper->redirector('index','articoli');
        }
    }

    public function init()
    {
        $this->_categoriesModel = new Admin_Model_Categorie();

        $this->view->config = Zend_Registry::get('config');
        $this->view->configAdmin = Zend_Registry::get('configAdmin');
    }

    public function indexAction()
    {
    }

    public function listAction()
    {
        $this->view->title = "Lista Categorie";

        $categoryId = $this->_request->getParam('categoria');

        if($categoryId != null)
        {
            $category = $this->_categoriesModel->getCategory($categoryId);
            $this->view->categoria = $category->toArray();
            $this->view->title .= " ".$this->view->categoria['Nome'];
            $this->view->sottoCategorie = $category->getSubCategories();
        }
        else
        {
            $this->view->title = "Categorie Padri";
            $this->view->sottoCategorie = $this->_categoriesModel->getCategories(true);
        }

        $this->view->headTitle($this->view->title);
    }

    public function addAction()
    {
        $fatherCategoryId = $this->_request->getParam('categoria');
        $fatherCategory = $this->_categoriesModel->getCategory($fatherCategoryId);
        
        $this->view->title = "Aggiungi una nuova categoria";
        $this->view->headTitle($this->view->title);
        $this->view->categoriaPadre = $fatherCategory;

        $form = new Admin_Form_Categoria('add');
        $form->setAction($this->_helper->url->url(array('module' => 'admin','controller' => 'categorie', 'action' => 'add'),'default',true));
        $form->idCategoriaPadre->setValue($fatherCategoryId);

        $form->QuantitàEsatte->setValue('0');
        $form->Attivo->setValue('1');

        $this->view->form = $form;

        if($this->getRequest()->isPost() && $this->getRequest()->getParam('conferma')) {
            $request = $this->getRequest()->getPost();

            if($form->isValid($request)) {
                $this->_categoriesModel->addCategory($form->getValues());
                $this->_helper->cacheCleaner();
                $this->getHelper('viewRenderer')->setNoRender();
                echo $fatherCategoryId;
            }
            else
                $form->populate($request);
        }
    }

    public function editAction()
    {
        $categoryId = $this->_request->getParam('gestiscicategoria');
        $category = $this->_categoriesModel->getCategory($categoryId);

        $this->view->title = "Edita una categoria";
        $this->view->headTitle($this->view->title);
        
        $this->view->categoria = $category->toArray();

        $form = new Admin_Form_Categoria('edit',$categoryId);
        $form->setAction($this->_helper->url->url(array('module' => 'admin','controller' => 'categorie', 'action' => 'edit'),'default',true));

        $form->idCategoriaPadre->addMultiOption(0,'Nessuna');
        $form->idCategoriaPadre->addMultiOptions($this->_categoriesModel->getSelectOptions($categoryId));
        $form->idCategoriaPadre->setValue($category->idCategoriaPadre);
       
        if($this->getRequest()->isPost() && $this->getRequest()->getParam('conferma')) {
            $request = $this->getRequest()->getPost();
            
            if($form->isValid($request)) {
                $this->_categoriesModel->editCategory($form->getValues());
                $this->_helper->cacheCleaner();
                
                $this->getHelper('viewRenderer')->setNoRender();
                echo $this->_request->getParam('idCategoriaPadre');
            }
            else
            {
                $form->populate($request);
            }
        }
        else
        {
            $form->populate($category->toArray());
        }
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $categoryId = $this->_request->getParam('gestiscicategoria');
        $category = $this->_categoriesModel->getCategory($categoryId);
        
        $this->view->categoria = $category->toArray();

        $this->view->title = "Cancella una categoria";
        $this->view->headTitle($this->view->title);

        $form = new Admin_Form_Categoria('delete',$categoryId);
        $form->setAction($this->_helper->url->url(array('module' => 'admin','controller' => 'categorie', 'action' => 'delete'),'default',true));

        $this->view->conferma = false;

        $this->view->canDelete = $category->CanDelete();

        if($this->getRequest()->isPost() && $this->getRequest()->getParam('conferma'))
        {
            $request = $this->getRequest()->getPost();
            if($form->isValid($request)) {

                if($this->view->canDelete)
                    $this->_categoriesModel->deleteCategory($categoryId);
                else
                    $category->activate(false);

                $this->_helper->cacheCleaner();
                $this->view->conferma = true;
                
                $this->getHelper('viewRenderer')->setNoRender();
                echo $this->view->categoria['idCategoriaPadre'];
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
            $categoryId = $this->_request->getParam('gestiscicategoria');
            $category = $this->_categoriesModel->getCategory($categoryId);
            $category->activate(true);
            $this->_helper->cacheCleaner();
        }
    }

    public function deactivateAction()
    {
        if($this->getRequest()->isPost())
        {
            $this->getHelper('viewRenderer')->setNoRender();
            $categoryId = $this->_request->getParam('gestiscicategoria');
            $category = $this->_categoriesModel->getCategory($categoryId);
            $category->activate(false);
            $this->_helper->cacheCleaner();
        }
    }

    public function neworderAction()
    {
        if($this->getRequest()->isPost())
        {
            $this->getHelper('viewRenderer')->setNoRender();
            $order = $this->_request->getParam('order');
            $this->_categoriesModel->orderCategories($order);
            $this->_helper->cacheCleaner();
        }
    }

    public function getselectoptionsAction()
    {
        if($this->getRequest()->isPost())
        {
            $this->getHelper('viewRenderer')->setNoRender();
            $selectCategoria = new MicOffMenu_SelectButton('Categoria');
            $this->_categoriesModel->addCategoriesToSelectButton($selectCategoria);
            echo $selectCategoria->getOptions();
        }
    }
}