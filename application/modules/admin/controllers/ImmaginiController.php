<?php

class Admin_ImmaginiController extends Zend_Controller_Action
{
    protected $_imagesModel;
    
    public function preDispatch()
    {
        $this->view->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();
    }
    
    public function init()
    {
        $this->_imagesModel = new Admin_Model_Immagini();
    }

    public function indexAction()
    {
        if(!$this->_request->getParam('sync'))
            $this->_helper->redirector('index','index');
    }

    public function infoAction()
    {
        $idImmagine = $this->_request->getParam('id');

        $imagePath = $this->_imagesModel->getImage($idImmagine)->Path;
        $size = getImageSize(realpath(APPLICATION_PATH ."/../public/".$imagePath));

        $infoImmagine = array('path' => $imagePath, 'width' => $size[0], 'height' => $size[1]);

        echo Zend_Json::encode($infoImmagine);
    }
    
    public function findAction()
    {
    	$url = $this->_request->getParam('url');
        if($url != null)
        	echo $this->_imagesModel->findImageByPath(urldecode($url))->idImmagineTagliata;
    }
}

