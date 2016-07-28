<?php

class CarrelloController extends Zend_Controller_Action
{
    protected $_cartModel;
    
    public function preDispatch()
    {
        if(($this->_request->getActionName()!='index' && $this->_request->getActionName()!='conferma') || $this->_request->getParam('ajax'))
            $this->view->layout()->disableLayout();
    }

    public function postDispatch()
    {
        if($this->_request->getActionName()!='index' && $this->_request->getActionName()!='conferma' && !$this->_request->getParam('ajax'))
            $this->_redirect(urldecode($this->_request->getParam('returnTo')));
    }

    public function init()
    {        
        $this->_cartModel = new Frontend_Model_Carrello();

        if($this->_request->getParam('cart')==='true' || $this->_request->getParam('cart')===true)
            $this->view->mostraDettaglioCarrello = true;
        else
            $this->view->mostraDettaglioCarrello = false;

        $config = Zend_Registry::get('config');

        $catalogModel = new Frontend_Model_Catalogo();
        $navigation = $catalogModel->getCatalogNavigation();
        $this->view->navigation($navigation);
    }

    public function indexAction()
    {
        $this->view->headTitle("Carrello | ");
        $this->view->carrello = $this->_cartModel->getCart();
        $this->view->numeroArticoli = $this->_cartModel->count();
        $this->view->prezzoTotale = $this->_cartModel->getTotal();
    }

    public function addAction()
    {
        $idArticolo = $this->_request->getParam('articolo');

        if($this->_request->getParam('quantità')>1)
            $quantità = $this->_request->getParam('quantità');
        else
            $quantità = 1;

        $this->_cartModel->addCartArticle($idArticolo, $quantità);
    }

    public function editAction()
    {
        $idArticolo = $this->_request->getParam('articolo');

        $quantità = $this->_request->getParam('quantità');

        $this->_cartModel->editCartArticle($idArticolo, $quantità);
    }

    public function removeAction()
    {
        $idArticolo = $this->_request->getParam('articolo');

        $this->_cartModel->removeCartArticle($idArticolo);
    }

    public function emptyAction()
    {
        $this->_cartModel->resetCart();
    }

    public function showAction()
    {
    }

    public function confermaAction()
    {
        $this->view->headTitle("Conferma Ordine | ");

        $this->_loginPage = array('module' => 'user', 'controller' => 'login', 'action' => 'index');

        $auth = Zend_Registry::get('User_Auth');

        if(!$auth->hasIdentity())
        {
            $this->_helper->redirector->gotoUrl(
                $this->_helper->url->url(array('module' => $this->_loginPage['module'],'controller' => $this->_loginPage['controller'],'action' => $this->_loginPage['action']))
                                        ."?loginFrom=".
                                        $this->_helper->url->url(array('module' => 'frontend','controller' => 'carrello','action' => 'conferma')
                )
            );
        }
        else
        {
            $identity = $auth->getIdentity();
            $form = new Frontend_Form_ConfermaOrdine();

            $form->Nome->setValue($identity->Nome);
            $form->Cognome->setValue($identity->Cognome);
            $form->Indirizzo->setValue($identity->Indirizzo);
            $form->Email->setValue($identity->Email);
            $form->CAP->setValue($identity->CAP);
            $form->Città->setValue($identity->Città);
            $form->Provincia->setValue($identity->Provincia);
            
            $this->view->form = $form;
            $this->view->success = false;

            if($this->getRequest()->isPost() && $this->getRequest()->getParam('conferma')) {
                $request = $this->getRequest()->getPost();
                if($form->isValid($request)) {

                    if($this->_request->getParam('NewAddress'))
                    {
                        $userModel = new Application_Model_Utenti();
                        $customer = $userModel->addCustomerToUser($this->_request->getParams(), $identity->idUtente);
                    }
                    else
                        $customer = null;
                    
                    $salesModel = new Frontend_Model_Vendite();
                    $sale = $salesModel->saveSale($customer, $this->_request->getParam('Note'));
                    
                    $this->_cartModel->sendMail($sale);
                    
                    $this->_cartModel->resetCart();
                    $this->view->success = true;
                }
                else
                    $form->populate($request);
            }
        }
    }
}