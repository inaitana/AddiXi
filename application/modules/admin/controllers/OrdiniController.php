<?php

class Admin_OrdiniController extends Zend_Controller_Action
{
    protected $_salesModel;
    
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
        $this->_salesModel = new Admin_Model_Vendite();
        $this->view->registerHelper(new Admin_View_Helper_CustomerDialog(), 'CustomerDialog');
        $this->view->registerHelper(new Application_View_Helper_RiassuntoOrdine(), 'RiassuntoOrdine');
        
        $this->view->headTitle('Gestione Ordini | ');
    }

    public function indexAction()
    {
        if(!$this->view->ajax)
            $this->getHelper('viewRenderer')->setNoRender();
        $this->view->registerHelper(new Admin_View_Helper_OrdersReminder(), 'OrdersReminder');
    }

    public function listaAction()
    {
        $filtroConfermati = $this->getRequest()->getParam('accettati');
        $filtroEvasi = $this->getRequest()->getParam('evasi');

        $filtroCliente = $this->getRequest()->getParam('filtrocliente');
        
        $filtroDataDa = $this->getRequest()->getParam('datafrom');
        $filtroDataA = $this->getRequest()->getParam('datato');

        $this->view->filtroCliente = $filtroCliente;
        $this->view->title = "Lista Ordini";
        $this->view->headTitle($this->view->title);
        
        if($filtroCliente != '')
        {
	        $usersModel = new Application_Model_Utenti();
	        $this->view->cliente = $usersModel->getCustomer($filtroCliente);
        }
        
        $this->view->vendite = $this->_salesModel->getSalesList($filtroConfermati, $filtroEvasi, $filtroCliente, $filtroDataDa, $filtroDataA);
    }

    public function dettagliAction()
    {
        $this->view->title = "Dettagli Ordine";
        $this->view->headTitle($this->view->title);

        $sale = $this->_salesModel->getSale($this->_request->getParam('ordine'));
        if(!$sale->Evasa)
            $sale->check();

        $this->view->vendita = $sale->toArray();
        $this->view->lineeVendita = $sale->getSaleLines();
    }

    public function modifyAction()
    {
        $sale = $this->_salesModel->getSale($this->_request->getParam('ordine'));
        $spedizione = $this->_request->getParam('spedizione');
        $params = $this->_request->getParams();

        $quantità = array();
        $prezzi = array();
        foreach($params as $paramName => $paramValue)
        {
            if(strpos($paramName, 'quantità') == 0)
            {
                $articleId = str_replace('quantità','',$paramName);
                $quantità[$articleId] = $paramValue;
            }
            if(strpos($paramName, 'prezzo') == 0)
            {
                $articleId = str_replace('prezzo','',$paramName);
                $prezzi[$articleId] = $paramValue;
            }
        }

        $spedizione = $this->_request->getParam('spedizione');

        $this->_salesModel->updateSale($sale, $spedizione, $quantità, $prezzi);

        if($this->_request->getParam('sendMail'))
            $this->_salesModel->sendNotification($sale);

        $this->_helper->cacheCleaner();
        
        $this->getHelper('viewRenderer')->setNoRender();
        echo "0";
    }

    public function confirmAction()
    {
        $sale = $this->_salesModel->getSale($this->_request->getParam('ordine'));
        
        $sale->confirm();
        $this->_salesModel->sendNotification($sale, 'confirm');
        $this->_helper->cacheCleaner();
        $this->getHelper('viewRenderer')->setNoRender();
        echo "0";
    }

    public function shipAction()
    {
        $sale = $this->_salesModel->getSale($this->_request->getParam('ordine'));

        $sale->ship();
        $this->_salesModel->sendNotification($sale, 'ship');
        $this->_helper->cacheCleaner();
        $this->getHelper('viewRenderer')->setNoRender();
        echo "0";
    }

    public function deleteAction()
    {
        $sale = $this->_salesModel->getSale($this->_request->getParam('ordine'));

        $sale->delete();
        $this->_helper->cacheCleaner();
        $this->getHelper('viewRenderer')->setNoRender();
        echo "0";
    }
}

