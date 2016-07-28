<?php
class Application_Model_Marche {
    protected $_brandsDb;
    
    public function __construct()
    {
        $this->_brandsDb = new Application_Model_DbTable_Marche();
    }

    public function getBrand($brandId)
    {
        return $this->_brandsDb->find($brandId)->current();
    }

    public function getBrandByName($brandName)
    {
        return $this->_brandsDb->fetchRow(array("Nome = ?" => str_replace("+", " ",$brandName)));
    }
}
?>
