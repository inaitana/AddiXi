<?php
class Admin_Model_Marche {
    protected $_marcheDb;

    public function __construct()
    {
        $this->_marcheDb = new Admin_Model_DbTable_Marche();
    }

    public function getBrandsList()
    {
        return $this->_marcheDb->fetchAll(NULL, 'Nome');
    }

    public function getBrand($brandId)
    {
        return $this->_marcheDb->find($brandId)->current();
    }

    public function addBrand($data)
    {
        $this->_marcheDb->insert($data);
    }

    public function editBrand($data)
    {
        $brandId = $data['id'];
        unset($data['id']);
        $this->_marcheDb->update($data, array('idMarca = ?' => $brandId));
    }

    public function getSelectOptions()
    {
        $marche = $this->_marcheDb->fetchAll(NULL, 'Nome');

        $selectOptions = array();
        
        foreach($marche as $marca)
            $selectOptions[$marca['idMarca']] = $marca['Nome'];

        return $selectOptions;
    }

    public function ValidateURI($uri)
    {
        if(substr($uri,0,4)!='http')
            $uri = "http://".$uri;

        if(Zend_Uri::check($uri))
            return $uri;
        else
            return "";
    }
}
?>
