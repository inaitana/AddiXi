<?php
class Application_Model_Vendite {
    protected $_flowsDb;
    protected $_articlesFlowsDb;
    protected $_salesDb;
    protected $_salesLinesDb;
    protected $_config;

    public function __construct()
    {
        $this->_flowsDb = new Application_Model_DbTable_Movimenti();
        $this->_articlesFlowsDb = new Application_Model_DbTable_MovimentiArticoli();
        $this->_salesDb = new Application_Model_DbTable_Vendite();
        $this->_salesLinesDb = new Application_Model_DbTable_LineeVendita();
        $this->_config = Zend_Registry::get('config');
    }

    public function getSalesList($filtroConfermati, $filtroEvasi = null, $filtroCliente = null)
    {
        $filtro = 'Online = TRUE';

        if($filtroConfermati === '1')
            $filtro .= ' AND Confermata = TRUE';
        else if ($filtroConfermati === '0')
            $filtro .= ' AND Confermata = FALSE';
        
        if($filtroEvasi === '1')
            $filtro .= ' AND Evasa = TRUE';
        else if ($filtroEvasi === '0')
            $filtro .= ' AND Evasa = FALSE';

        if($filtroCliente != null)
        {
            if(is_array($filtroCliente))
                $filtro .= ' AND idCliente IN ('.implode(",",$filtroCliente).')';
            else
                $filtro .= ' AND idCliente = '.$filtroCliente;
        }
        return $this->_salesDb->fetchAll($filtro);
    }

    public function countSales ()
    {
        return $this->_salesDb->fetchAll()->count();
    }

    public function getSale($id, $idUtente = null)
    {
        $sale = $this->_salesDb->find($id)->current();
        
        if($sale->hasAccess($idUtente))
            return $sale;
        else
            return false;
    }
}

?>
