<?php
class Admin_Model_Immagini {
    protected $_cutImagesDb;
    protected $_config;

    public function __construct()
    {
        $this->_cutImagesDb = new Admin_Model_DbTable_ImmaginiTagliate();
        $this->_config = Zend_Registry::get('config');
    }

    public function getImage($id)
    {
        return $this->_cutImagesDb->find($id)->current();
    }
    
    public function addImage($nomeFile, $thumbnail_base64enc)
    {
        $image_path = $this->_config->paths->images.$nomeFile;
        $thumbnail_path = $this->_config->paths->images."/thumbs/".$nomeFile;

        // Non memorizzo base64 più lunghi di 32k
        if(strlen($thumbnail_base64enc) > pow(2,15))
            $thumbnail_base64enc = null;

        return $this->_cutImagesDb->insert($nomeFile, $image_path, $thumbnail_path, $thumbnail_base64enc);
    }
}
?>
