<?php

class Admin_Model_DbTable_ImmaginiTagliate extends Application_Model_DbTable_ImmaginiTagliate
{
    protected $_imagesDb;

    public function init()
    {
        $this->_imagesDb = new Application_Model_DbTable_Immagini();
        parent::init();
    }

    public function insert($nomeFile, $image_path, $thumbnail_path, $thumbnail_base64enc)
    {
        $imageData['Nome'] = $nomeFile;
        $imageData['Path'] = $image_path;
        
        $idImmagine = $this->_imagesDb->insert($imageData);

        $cutImageData['idImmagine'] = $idImmagine;
        $cutImageData['Path'] = $image_path;
        $cutImageData['Thumbnail Path'] = $thumbnail_path;
        $cutImageData['Thumbnail Base64'] = $thumbnail_base64enc;
        return parent::insert($cutImageData);
    }
}