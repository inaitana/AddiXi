<?php

class Admin_LoginController extends Zend_Controller_Action
{
    protected $_auth;

    public function init()
    {
        $this->_auth = Zend_Registry::get('Admin_Auth');

        if($this->_auth->hasIdentity() && $this->_request->getParam('action') !== 'logout')
            $this->_helper->redirector('index','index');
    }

    public function indexAction()
    {
        $request = $this->getRequest();
        $form = new Admin_Form_Login();
        
        if ($request->isPost() && $request->getParam('login') && $form->isValid($request->getPost())) {
            if($this->authenticate($form))
                $this->_helper->redirector('index', 'index');
            else
                $this->view->datiErrati = true;
        }

        $this->view->form = $form;
    }

    public function logoutAction()
    {
        $this->_auth->clearIdentity();

        $this->_helper->redirector('index','index');
    }


    protected function authenticate($form)
    {
        $authAdapter = $this->getAuthAdapter($form);

        $result  = $this->_auth->authenticate($authAdapter);
        
        if($result->isValid())
        {
            $storage = $this->_auth->getStorage();
            $resultRow = $authAdapter->getResultRowObject(null,'password');
            $storage->write($resultRow);
            return true;
        }
        else
            return false;
    }

    protected function getAuthAdapter($form)
    {
        $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Registry::get("db"));
        $authAdapter->setTableName('Amministratori');
        $authAdapter->setIdentityColumn('Nome Utente');
        $authAdapter->setCredentialColumn('Password');

        $authAdapter->setIdentity($form->getValue('username'));
        $authAdapter->setCredential(sha1($form->getValue('password')));
        $authAdapter->setCredentialTreatment("? AND Attivo = TRUE");

        return $authAdapter;
    }
}