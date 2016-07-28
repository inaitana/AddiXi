<?php
class Frontend_Model_Vendite extends Application_Model_Vendite {
    protected $_cartModel;
    protected $_identity;

    public function __construct()
    {
        $this->_cartModel = new Frontend_Model_Carrello();

        $auth = Zend_Registry::get('User_Auth');
        $this->_identity = $auth->getIdentity();

        parent::__construct();
    }

    public function saveSale($customerId = null, $notes)
    {
        $flow['Data'] = date('Y-m-d');
        $flow['idCausale'] = 1;
        $flow['Manuale'] = false;
        $flow['Provvisorio'] = true;
        $flowId = $this->_flowsDb->insert($flow);
        
        if($customerId == null)
            $sale['idCliente'] = $this->_identity->idCliente;
        else
            $sale['idCliente'] = $customerId;
        
        $sale['idMovimento'] = $flowId;
        $sale['Data'] = date('Y-m-d');
        $sale['Online'] = true;
        $sale['Confermata'] = false;
        $sale['SubTotale'] = $this->_cartModel->getTotal(true);
        $sale['Costo Spedizione'] = 0;
        $sale['Totale'] = $sale['SubTotale'] + $sale['Costo Spedizione'];
        $sale['Note'] = $notes;
        $saleId = $this->_salesDb->insert($sale);

        $articleFlow['idMovimento'] = $flowId;
        $saleLine['idVendita'] = $saleId;
        
        foreach($this->_cartModel->getCart() as $label => $articoloCarrello)
        {
            if(strpos($label,'art')===0)
            {
                $articleFlow['idArticoloSpecifico'] = $articoloCarrello['idArticoloSpecifico'];
                $articleFlow['Direzione'] = 'u';
                $articleFlow['Quantità'] = $articoloCarrello['Quantità Carrello'];
                $articleFlow['Prezzo acquisto unitario'] = $articoloCarrello['Prezzo d\'acquisto'];
                $articleFlow['Prezzo vendita unitario'] = $articoloCarrello['Prezzo'];
                $articleFlowId = $this->_articlesFlowsDb->insert($articleFlow);

                $saleLine['idMovimentoArticolo'] = $articleFlowId;
                $saleLine['Importo'] = $articoloCarrello['Prezzo']*$articoloCarrello['Quantità Carrello'];

                $this->_salesLinesDb->insert($saleLine);
            }
        }

        $sale = $this->getSale($saleId);
        $sale->check();

        return $sale;
    }
}

?>
