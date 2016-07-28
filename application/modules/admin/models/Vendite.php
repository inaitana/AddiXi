<?php
class Admin_Model_Vendite extends Application_Model_Vendite {
    public function updateSale($sale, $spedizione, $quantità, $prezzi)
    {
        $saleLines = $sale->getSaleLines();

        if($spedizione !== null)
            $sale->updateShipping($spedizione);

        foreach($saleLines as $saleLine)
        {
            if(isset($quantità[$saleLine->idLinea]))
                $saleLine->updateQuantity($quantità[$saleLine->idLinea]);

            if(isset($prezzi[$saleLine->idLinea]))
                $saleLine->updatePrice($prezzi[$saleLine->idLinea]);
        }

        $sale->check();
    }

    public function sendNotification($sale, $type = 'modify')
    {
        $mailDb = new Application_Model_DbTable_Email();
        $cliente = $sale->getCliente();

        $view = Zend_Layout::getMvcInstance()->getView();

        $view->nomeNegozio = $this->_config->shopName;
        $view->idVendita = $sale->idVendita;
        $view->type = $type;
        $view->cliente = $cliente;

        $htmlMailUtente = $view->render('mails/mail_order_notification.phtml');
        switch ($type)
        {
            case 'modify':
                $subject = 'Modifica Ordine #'.$sale->getNumero();
                break;
            case 'confirm':
                $subject = 'Ordine #'.$sale->getNumero().' Accettato';
                break;
            case 'ship':
                $subject = 'Ordine #'.$sale->getNumero().' Spedito';
                break;
        }

        $mail = array(
            'Mittente' => array('mail' => $this->_config->mail->systemSenderAddress, 'name' => $view->nomeNegozio),
            'Destinatario' => array('mail' => $cliente->Email, 'name' => $cliente->Nome.' '.$cliente->Cognome),
            'Oggetto' => $subject,
            'Body' => $htmlMailUtente
        );
        $mailDb->insert($mail);
    }
}

?>
