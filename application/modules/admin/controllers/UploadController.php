<?php

class Admin_UploadController extends Zend_Controller_Action
{
    protected $_imagesModel;
    protected $_config;
    protected $_path;
    
    public function preDispatch()
    {
        $this->view->layout()->disableLayout();
        $this->getHelper('viewRenderer')->setNoRender();
    }
    
    public function init()
    {
        $this->_imagesModel = new Admin_Model_Immagini();
        $this->_config = Zend_Registry::get('config');
        $this->_path = $this->_config->paths->images;
    }

    public function indexAction()
    {
        $this->_helper->redirector('index','index');
    }

    public function immagineAction()
    {
        if (!empty($_FILES)) {
            $tmp = $_FILES['Filedata']['tmp_name'];
            $nomeFile = $_FILES['Filedata']['name'];
            $pathImmagine = realpath(APPLICATION_PATH.'/../public'.$this->_path)."/".$nomeFile;
            
            move_uploaded_file($tmp,$pathImmagine);

            if($this->createThumbnail($pathImmagine))
                echo $this->_imagesModel->addImage($this->_path.$nomeFile);
        }
    }

    protected function createThumbnail($filename)
    {
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        switch(strtolower($ext))
        {
            case "jpg":
            case "jpeg":
                $openFunction = "imagecreatefromjpeg";
                $saveFunction = "imagejpeg";
            break;
            case "png":
                $openFunction = "imagecreatefrompng";
                $saveFunction = "imagepng";
            break;
            case "gif":
                $openFunction = "imagecreatefromgif";
                $saveFunction = "imagegif";
            break;
        }

        $image = $openFunction($filename);
        
        if($image === false)
            return false;

        $height = imagesy($image);
        $width = imagesx($image);
        $new_height = $this->_config->images->thumbnails->maxHeight;
        $new_width = $this->_config->images->thumbnails->maxWidth;

        if(($height > $width) > ($new_height > $new_width))
            $new_width = floor($width / $height * $new_height);
        else
            $new_height = floor($height / $width * $new_width);

		$new_path = str_replace("/immagini","/.thumbs/immagini",$filename);
        $new_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($new_image , $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        $saveFunction($new_image, $new_path);

        if($new_image == false)
            return false;
        else
            return $new_path;
    }
}

