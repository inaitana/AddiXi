<?php

class User_LoginController extends Zend_Controller_Action
{
    protected $_auth;
    protected $_usersModel;
    
    public function init()
    {
        $this->_usersModel = new User_Model_Utenti();

        $this->_auth = Zend_Registry::get('User_Auth');

        if($this->_auth->hasIdentity() && $this->_request->getParam('action') !== 'logout')
            $this->_helper->redirector('index','index');
        
        $navigation = Zend_Registry::get('userNav');

        $this->view->navigation($navigation);
    }

    public function indexAction()
    {
        $this->view->headTitle("Login | ");
        $request = $this->getRequest();

        $loginForm = new User_Form_Login();
        $lostPasswordForm = new User_Form_PasswordSmarrita();
        $lostPasswordForm->setAction($this->_helper->url->url(array('module' => 'user', 'controller' => 'login', 'action' => 'lostPassword'),'default',true));
        
        if($request->getParam('loginFrom') != null)
            $loginForm->loginFrom->setValue($request->getParam('loginFrom'));
        else
            $loginForm->loginFrom->setValue($this->_helper->url->url(array('module' => 'user', 'controller' => 'index', 'action' => 'index')));
        
        if ($request->isPost() && $loginForm->isValid($request->getPost())) {
            if ($this->authenticate($loginForm))
                $this->_redirect($request->getParam('loginFrom'));
            else
                $loginForm->setDescription('Nome Utente o Password errati.');
        }

        $this->view->loginForm = $loginForm;
        $this->view->lostPasswordForm = $lostPasswordForm;

        if($this->_request->getParam('lostPassword') == '1')
            $this->view->lostPasswordShow = true;
        else
            $this->view->lostPasswordShow = false;
    }

    public function logoutAction()
    {
        $this->_auth->clearIdentity();
        
        $this->_helper->redirector('index');
    }

    public function registerAction()
    {
        $this->view->headTitle("Registrazione | ");
        $form = new User_Form_Registrazione();
        $this->view->form = $form;

        if($this->getRequest()->isPost()) {
            $request = $this->getRequest()->getPost();
            if($form->isValid($request)) {
                $this->_usersModel->addUser($form->getValues());
                $this->_helper->redirector('activate','login','user',array('user'=>$form->getValue('NomeUtente')));
            }
            else
                $form->populate($request);
        }
    }

    public function activateAction()
    {
        $this->view->headTitle("Attiva Account | ");
        $this->view->showForm = false;
        if($this->_request->getParam('user')==NULL)
            $this->_helper->redirector('index');
        else
        {
            if($this->_request->getParam('code')!==NULL)
                $this->view->success = $this->_usersModel->activateUser($this->_request->getParam('user'),$this->_request->getParam('code'));
            else
            {
                $form = new User_Form_Attivazione();
                $this->view->form = $form;
                $this->view->showForm = true;
            }
        }
    }


    public function lostpasswordAction()
    {
        $this->view->headTitle("Password Smarrita | ");
        $this->view->showForm = false;
        $this->view->noMail = false;
        $this->view->newPassword = false;
        $this->view->wrongCode = false;
            
        if($this->_request->getParam('email')==NULL)
            $this->_helper->redirector('index');
        else
        {
            if($this->_request->getParam('code')!==NULL)
            {
                if(($this->view->newPassword = $this->_usersModel->checkLostPasswordCode($this->_request->getParam('email'),$this->_request->getParam('code'))) == false)
                {
                    $this->view->wrongCode = true;
                    $form = new User_Form_ConfermaPasswordSmarrita();
                    $form->email->setValue($this->_request->getParam('email'));
                    $this->view->form = $form;
                    $this->view->showForm = true;
                }
            }
            else if($this->_request->isPost())
            {
                if($this->_usersModel->sendLostPasswordMail($this->_request->getParam('email')))
                {
                    $form = new User_Form_ConfermaPasswordSmarrita();
                    $form->email->setValue($this->_request->getParam('email'));
                    $this->view->form = $form;
                    $this->view->showForm = true;
                    $this->view->lostPasswordSent = true;
                }
                else
                    $this->view->noMail = true;
            }
        }
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
        $authAdapter->setTableName('Clienti View');
        $authAdapter->setIdentityColumn('Nome Utente');
        $authAdapter->setCredentialColumn('Password');

        $authAdapter->setIdentity($form->getValue('username'));
        $authAdapter->setCredential(sha1($form->getValue('password')));
        $authAdapter->getDbSelect()->where('Attivo = TRUE')->where("Principale = TRUE");

        return $authAdapter;
    }
}