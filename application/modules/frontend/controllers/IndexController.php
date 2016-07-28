<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->navigation(Zend_Registry::get('mainNav'));
        $this->view->navigation()->findOneByUri($this->_request->getPathInfo())->active = true;
    }

    public function indexAction()
    {
    }
}

