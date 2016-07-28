<?php

class Application_Model_DbTable_DescrizioniCategorie extends Zend_Db_Table_Abstract
{

    protected $_name = 'Descrizioni Categorie';

    protected $_referenceMap = array(
            'Categoria' => array(
                'columns'	=> array('idCategoria'),
                'refTableClass'	=> 'Application_Model_DbTable_Categorie',
                'refColumns'	=> array('idCategoria')
            )
    );
}

