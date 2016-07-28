<?php

class Admin_SEOController extends Zend_Controller_Action
{
    protected $_settingsModel;
    protected $_SEOModel;
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
        $this->_settingsModel = new Admin_Model_Impostazioni();
        $this->_SEOModel = new Admin_Model_SEO();
        $this->_auth = Zend_Registry::get('Admin_Auth');
        
        $this->view->headTitle('Pannello SEO | ');
        $this->view->configAdmin = Zend_Registry::get('configAdmin');
    }

    public function indexAction()
    {
        $this->view->TitoloBase = $this->_settingsModel->get('Titolo Base');
        $this->view->MotoriDiRicerca = $this->_SEOModel->getSearchEngines();
        $this->view->AddThis = $this->_settingsModel->get('AddThis');
    }

    public function pingAction()
    {
        $searchEngineId = $this->_request->getParam('idMotore');
        $newDate = $this->_SEOModel->pingSearchEngine($searchEngineId, "http://".$this->_request->getHttpHost().$this->_helper->url->url(array('module' => 'frontend', 'controller' => 'catalogo', 'action' => 'sitemap'),'sitemap'));
        $this->getHelper('viewRenderer')->setNoRender();
        echo $newDate;
    }

    public function confirmAction()
    {
        $this->_settingsModel->set('Titolo Base', $this->_request->getParam('titoloBase'));
        if($this->_request->getParam('addthis') == 'on')
        	$this->_settingsModel->set('AddThis', 1);
        else
        	$this->_settingsModel->set('AddThis', 0);
        $this->getHelper('viewRenderer')->setNoRender();
        echo "0";
    }
}

