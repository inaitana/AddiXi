<?php

class Application_Model_DbTable_LineeVendita extends Zend_Db_Table_Abstract
{
    protected $_name = 'Linee Vendita';
    protected $_rowClass = 'LineeVenditaRow';

    protected $_referenceMap = array(
            'Vendita' => array(
                'columns'	=> array('idVendita'),
                'refTableClass'	=> 'Application_Model_DbTable_Vendite',
                'refColumns'	=> array('idVendita')
            ),
            'Movimento Articolo' => array(
                'columns'	=> array('idMovimentoArticolo'),
                'refTableClass'	=> 'Application_Model_DbTable_MovimentiArticoli',
                'refColumns'	=> array('idMovimentoArticolo')
            )
    );
}

class LineeVenditaRow extends Zend_Db_Table_Row_Abstract
{
    protected function getNomeCliente()
    {
        $cliente = $this->findParentRow('Application_Model_DbTable_Clienti');
        return $cliente->Nome.' '.$cliente->Cognome;
    }

    public function getArticleFlow()
    {
        return $this->findParentRow('Application_Model_DbTable_MovimentiArticoli');
    }

    public function updateQuantity($quantity)
    {
        $flow = $this->getArticleFlow();
        
        if($quantity == 0)
        {
            $this->delete();
            $flow->delete();
        }
        else
        {
            $flow->{'Quantità'} = $quantity;
            $this->Importo = $flow->{'Quantità'} * $flow->{'Prezzo vendita unitario'};
            $flow->save();
            $this->save();
        }
    }

    public function updatePrice($price)
    {
        $flow = $this->getArticleFlow();

        $flow->{'Prezzo vendita unitario'} = $price;
        $this->Importo = $flow->{'Quantità'} * $flow->{'Prezzo vendita unitario'};
        $flow->save();
        $this->save();
    }

    public function toArray() {
        $movimentoArticolo = $this->getArticleFlow();

        $arrayLineaVendita = array_merge(parent::toArray(),$movimentoArticolo->toArray());

        $articoliView = new Application_Model_DbTable_ArticoliView();
        $articolo = $articoliView->find($movimentoArticolo->idArticoloSpecifico)->current();

        if(is_null($arrayLineaVendita['Descrizione']) || $arrayLineaVendita['Descrizione']=='')
            $arrayLineaVendita['Descrizione'] = $articolo->Marca.' '.$articolo->Nome;

        $arrayLineaVendita['Disponibile'] = $articolo->{'Quantità'};

        return $arrayLineaVendita;
    }
}