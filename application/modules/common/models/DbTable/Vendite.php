<?php

class Application_Model_DbTable_Vendite extends Zend_Db_Table_Abstract
{
    protected $_name = 'Vendite';
    protected $_rowClass = 'VenditeRow';

    protected $_referenceMap = array(
            'Movimento' => array(
                'columns'	=> array('idMovimento'),
                'refTableClass'	=> 'Application_Model_DbTable_Movimenti',
                'refColumns'	=> array('idMovimento')
            ),
            'Cliente' => array(
                'columns'	=> array('idCliente'),
                'refTableClass'	=> 'Application_Model_DbTable_Clienti',
                'refColumns'	=> array('idCliente')
            )
    );
    
    protected $_dependentTables = array('Application_Model_DbTable_LineeVendita');

}

class VenditeRow extends Zend_Db_Table_Row_Abstract
{
    public function getCliente()
    {
        return $this->findParentRow('Application_Model_DbTable_Clienti');
    }

    public function getSaleLines()
    {
        return $this->findDependentRowset('Application_Model_DbTable_LineeVendita');
    }

    public function getFlow()
    {
        return $this->findParentRow('Application_Model_DbTable_Movimenti');
    }

    public function delete()
    {
        foreach($this->getSaleLines() as $saleLine)
            $saleLine->delete();

        $flow = $this->getFlow();
        
        parent::delete();
        
        $flow->delete();
    }

    public function confirm()
    {
        $this->Confermata = true;
        $this->Evasa = false;
        $this->save();
    }

    public function ship()
    {
        $this->Evasa = true;
        $this->save();

        $flow = $this->getFlow();
        $flow->Provvisorio = false;
        $flow->save();

        $flow->updateAvailableQuantities();
    }

    public function updateShipping($shipping)
    {
        $this->{'Costo Spedizione'} = $shipping;
        $this->save();
    }

    public function check()
    {
        $subTotale = 0;
        foreach($this->getSaleLines() as $saleLine)
        {
            $articleFlow = $saleLine->getArticleFlow();
            if($articleFlow->checkAvailability() && floatval($articleFlow->{'Prezzo vendita unitario'}) > 0)
                $saleLine->Sospesa = false;
            else
                $saleLine->Sospesa = true;

            $subTotale += $saleLine->Importo;
            
            $saleLine->save();
        }

        $this->SubTotale = $subTotale;
        $this->Totale = $this->SubTotale + $this->{'Costo Spedizione'};
        $this->save();
    }

    public function hasAccess($userId)
    {
        if($userId == null)
            return true;
        else
        {
            if($userId == $this->getCliente()->idUtente)
                return true;
            else
                return false;
        }
    }

    public function getConfermabile()
    {
        foreach($this->getSaleLines() as $saleLine)
        {
            if($saleLine->Sospesa == true)
                return false;
        }
        return true;
    }

    public function getNumero()
    {
        $numero = date('ymd',strtotime($this->Data));
        $numero .= str_pad($this->idVendita,5,'0',STR_PAD_LEFT);
        return $numero;
    }

    public function toArray() {
        $array = parent::toArray();
        $array['Nome Cliente'] = $this->getCliente()->Nome.' '.$this->getCliente()->Cognome;
        $array['Confermabile'] = $this->getConfermabile();
        $array['Numero'] = $this->getNumero();
        return $array;
    }
}