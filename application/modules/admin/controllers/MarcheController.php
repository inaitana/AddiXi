<?php

class Admin_MarcheController extends Zend_Controller_Action
{
    protected $_brandsModel = null;

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
        $this->_brandsModel = new Admin_Model_Marche();

        $this->view->config = Zend_Registry::get('config');
        $this->view->configAdmin = Zend_Registry::get('configAdmin');
    }

    public function indexAction()
    {
    }

    public function listAction()
    {
        $this->view->title = "Lista ".$this->view->config->language->brandNamePlur;
        $this->view->headTitle($this->view->title);
        $this->view->marche = $this->_brandsModel->getBrandsList();
    }

    public function addAction()
    {
        $this->view->title = "Aggiungi ".$this->view->config->language->brandNameSing;
        $this->view->headTitle($this->view->title);

        $form = new Admin_Form_Marca('add');
        $form->setAction($this->_helper->url->url(array('module' => 'admin','controller' => 'marche', 'action' => 'add'),'default',true));

        $this->view->form = $form;

        if($this->getRequest()->isPost() && $this->getRequest()->getParam('conferma')) {
            $request = $this->getRequest()->getPost();

            if($form->isValid($request)) {
                $form->Sito->setValue($this->_brandsModel->ValidateURI($form->getValue('Sito')));
                $this->_brandsModel->addBrand($form->getValues());
                $this->_helper->cacheCleaner();
                
                $this->getHelper('viewRenderer')->setNoRender();
                echo 0;
            }
            else
                $form->populate($request);
        }
    }

    public function editAction()
    {
        $brandId = $this->_request->getParam('marca');
        $brand = $this->_brandsModel->getBrand($brandId);

        $this->view->title = "Edita".$this->view->config->language->brandNameSing;
        $this->view->headTitle($this->view->title);
        
        $this->view->marca = $brand->toArray();

        $form = new Admin_Form_Marca('edit',$brandId);
        $form->setAction($this->_helper->url->url(array('module' => 'admin','controller' => 'marche', 'action' => 'edit'),'default',true));
       
        if($this->getRequest()->isPost() && $this->getRequest()->getParam('conferma')) {
            $request = $this->getRequest()->getPost();
            
            if($form->isValid($request)) {
                $form->Sito->setValue($this->_brandsModel->ValidateURI($form->getValue('Sito')));
                $this->_brandsModel->editBrand($form->getValues());
                $this->_helper->cacheCleaner();
                $this->getHelper('viewRenderer')->setNoRender();
                echo 0;
            }
            else
            {
                $form->populate($request);
            }
        }
        else
        {
            $form->populate($brand->toArray());
        }
        
        $this->view->form = $form;
    }

    public function deleteAction()
    {
        $brandId = $this->_request->getParam('marca');
        $brand = $this->_brandsModel->getBrand($brandId);
        
        $this->view->marca = $brand->toArray();

        $this->view->title = "Cancella ".$this->view->config->language->brandNameSing;
        $this->view->headTitle($this->view->title);

        $form = new Admin_Form_Marca('delete',$brandId);
        $form->setAction($this->_helper->url->url(array('module' => 'admin','controller' => 'marche', 'action' => 'delete'),'default',true));

        $this->view->conferma = false;

        $this->view->canDelete = $brand->CanDelete();

        if($this->getRequest()->isPost() && $this->getRequest()->getParam('conferma'))
        {
            $request = $this->getRequest()->getPost();
            if($form->isValid($request)) {

                if($this->view->canDelete)
                    $brand->delete();

                $this->_helper->cacheCleaner();
                $this->view->conferma = true;
                
                $this->getHelper('viewRenderer')->setNoRender();
                echo 0;
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
}