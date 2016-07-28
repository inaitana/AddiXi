<?php

class User_IndexController extends Zend_Controller_Action
{
    protected $_usersModel;
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
        $this->_usersModel = new User_Model_Utenti();

        $this->_auth = Zend_Registry::get('User_Auth');

        $navigation = Zend_Registry::get('userNav');

        $this->view->navigation($navigation);
    }

    public function indexAction()
    {
    }

    public function cambiopasswordAction()
    {
        $this->view->headTitle('Cambio Password | ');
        
        $request = $this->getRequest();
        $this->view->success = false;
        $form = new User_Form_CambioPassword();
        
        if ($request->isPost() && $form->isValid($request->getPost())) {
            if($this->_usersModel->changePassword($this->_auth->getIdentity()->idUtente, $this->_request->getParam('VecchiaPassword'), $this->_request->getParam('Password')))
                $this->view->success = true;
            else
                $form->setDescription('Password corrente errata.');
        }

        $this->view->form = $form;
    }
}

