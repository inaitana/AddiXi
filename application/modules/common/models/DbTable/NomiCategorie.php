<?php

class Application_Model_DbTable_NomiCategorie extends Zend_Db_Table_Abstract
{

    protected $_name = 'Nomi Categorie';

    protected $_referenceMap = array(
            'Categoria' => array(
                'columns'	=> array('idCategoria'),
                'refTableClass'	=> 'Application_Model_DbTable_Categorie',
                'refColumns'	=> array('idCategoria')
            )
    );
}

