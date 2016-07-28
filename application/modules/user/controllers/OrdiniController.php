<?php

class User_OrdiniController extends Zend_Controller_Action
{
    protected $_salesModel;
    protected $_userModel;
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
        $this->_salesModel = new Application_Model_Vendite();
        $this->_userModel = new User_Model_Utenti();

        $this->_auth = Zend_Registry::get('User_Auth');

        $navigation = Zend_Registry::get('userNav');

        $this->view->navigation($navigation);
    }

    public function indexAction()
    {
        $idUtente = $this->_auth->getIdentity()->idUtente;

        $this->view->title = "Lista Ordini";
        $this->view->headTitle($this->view->title." | ");

        $listaClienti = $this->_userModel->getCustomersForUser($idUtente);
        $this->view->vendite = $this->_salesModel->getSalesList(false, false, $listaClienti);
    }

    public function dettagliAction()
    {
        $idUtente = $this->_auth->getIdentity()->idUtente;
        
        $this->view->title = "Dettagli Ordine";
        $this->view->headTitle($this->view->title);

        $sale = $this->_salesModel->getSale($this->_request->getParam('ordine'), $idUtente);
        if($sale == false || $sale == null)
            throw new Zend_Controller_Action_Exception('',404);

        $this->view->vendita = $sale->toArray();
        $this->view->lineeVendita = $sale->getSaleLines();
    }
}

