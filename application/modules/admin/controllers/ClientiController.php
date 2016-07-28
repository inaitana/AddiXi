<?php

class Admin_ClientiController extends Zend_Controller_Action
{
    protected $_usersModel;
    
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
        $this->_usersModel = new Application_Model_Utenti();
    }

    public function indexAction()
    {
        // action body
    }

    public function dettagliAction()
    {
        $usersModel = new Application_Model_Utenti();
        $cliente = $usersModel->getCustomer($this->_request->getParam('idCliente'));
        $this->view->cliente = $cliente;
        $user = $usersModel->getUser($cliente->idUtente);

        if(!$cliente->Principale)
        {
            $clientePrincipale = $usersModel->getMainCustomer($user);
            $this->view->clientePrincipale = $clientePrincipale;
        }
    }
}

