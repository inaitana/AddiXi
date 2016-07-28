<?php

class Admin_Model_DbTable_Marche extends Application_Model_DbTable_Marche
{
    protected $_rowClass = 'AdminBrandRow';
}

class AdminBrandRow extends BrandRow
{
    public function canDelete()
    {
        $genericArticlesDb = new Application_Model_DbTable_ArticoliGenerici();
        $genericArticles = $genericArticlesDb->fetchAll(array('idMarca = ?' => $this->idMarca));

        if(count($genericArticles))
            return false;
        else
            return true;
    }    
}

