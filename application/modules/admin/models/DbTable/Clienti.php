<?php

class Admin_Model_DbTable_Clienti extends Application_Model_DbTable_Clienti
{
    function getNamesArray()
    {
        $customers = $this->fetchAll(null, array('Cognome','Nome'));
        $namesArray = array();
        foreach($customers as $customer)
        {
            $namesArray[$customer->idCliente] = $customer->Nome." ".$customer->Cognome;
        }
        return $namesArray;
    }
}
?>
