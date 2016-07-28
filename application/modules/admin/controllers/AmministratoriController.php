<?php

class Admin_AmministratoriController extends Zend_Controller_Action
{
    protected $_adminsModel;
    protected $_auth;
    
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
        $this->_adminsModel = new Admin_Model_Amministratori();
        $this->_auth = Zend_Registry::get('Admin_Auth');
    }

    public function indexAction()
    {
        $this->_helper->redirector('index','index');
    }

    public function cambiopasswordAction()
    {
        $this->view->headTitle('Cambio Password | ');
        $request = $this->getRequest();
        $this->view->success = false;
        $form = new Admin_Form_CambioPassword();

        $form->setName('formCambioPassword');
        $form->setAction($this->_helper->url->url());

        if ($request->isPost() && $request->getParam('conferma') && $form->isValid($request->getPost())) {
            if($this->_adminsModel->changePassword($this->_auth->getIdentity()->idAmministratore, $this->_request->getParam('VecchiaPassword'), $this->_request->getParam('Password')))
                $this->view->success = true;
            else
                $form->setDescription('Password corrente errata.');
        }

        $this->view->form = $form;
    }
}

