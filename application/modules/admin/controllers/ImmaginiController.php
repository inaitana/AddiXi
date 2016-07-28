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
}

