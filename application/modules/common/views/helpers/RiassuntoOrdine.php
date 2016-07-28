<?php
class Application_View_Helper_RiassuntoOrdine extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function riassuntoOrdine($id, $admin = false)
    {
        $saleModel = new Application_Model_Vendite();
        $sale = $saleModel->getSale($id);
        $saleLines = $sale->getSaleLines();
        $customer = $sale->getCliente();

        $output = "<p>Questo è il contenuto dell'ordine numero ".$sale->getNumero().":</p>";

        $output .= "<ul>";
        $unknownPrice = false;
        $noticeOverQuantity = false;
        foreach($saleLines as $saleLine)
        {
            $saleLine = $saleLine->toArray();

            if(floatval($saleLine['Prezzo vendita unitario']) <= 0)
                $unknownPrice = true;

            if($saleLine['Quantità'] > $saleLine['Disponibile'])
            {
                $noticeOverQuantity = true;
                $overQuantity = true;
            }
            else
                $overQuantity = false;

            $output .= "
            <li>
                <strong>".$saleLine['Quantità']."X:</strong> <em>".$saleLine['Descrizione']."</em>, ";
            
            $price = new Zend_Currency(array('value' => $saleLine['Prezzo vendita unitario']));
            $output .= $price->mul($saleLine['Quantità']);
            
            if($overQuantity)
                $output .= "*";
            $output .= "</li>";
        }
        $output .= "</ul>";

        $output .= "<p><strong>Importo totale dell'ordine: ";
        $output .= new Zend_Currency(array('value' => $sale->Totale));
        $output .= "</strong></p>";
        
        if($sale->Note !== '')
        {
            $output .= "
                <p>Ulteriori comunicazioni:<br/>
                <em>".$sale->Note."</em></p>";
        }

        $output .= "
            <p>L'indirizzo di spedizione indicato è il seguente:</p>
            <p></p>
            <p>".$customer->Nome." ".$customer->Cognome."<br/>
            ".$customer->Indirizzo."<br/>
            ".$customer->CAP." ".$customer->{'Città'}." ".$customer->Provincia."</p>
            <p></p>
            <p>Indirizzo email: <a href='mailto:".$customer->Email."'>".$customer->Email."</a></p>
            <p></p>
        ";

        if($admin)
        {
            $output .= "<p>La invitiamo a contattare il cliente per accordare dettagli e costi di spedizione.</p>";
            if($unknownPrice)
                $output .= "<p id='unknownPrice'>N.B: Alcuni degli articoli richiesti dal cliente non hanno un prezzo specificato. La invitiamo a comunicare al più presto il loro prezzo al cliente.</p>";
            if($noticeOverQuantity)
                $output .= "<p id='noticeOverQuantity'>N.B: Gli articoli indicati da un asterisco sono disponibili in quantità minore di quella desiderata dal cliente. La invitiamo a comunicare al più presto al cliente le modalità e tempistiche per il soddisfacimento della quantità desiderata.</p>";
        }
        else
        {
            $output .= "<p>Verrà al più presto contattato da un nostro operatore per la conferma e la discussione dei dettagli relativi a modalità e costi di spedizione.</p>";
            if($unknownPrice)
                $output .= "<p id='unknownPrice'>N.B: Alcuni degli articoli richiesti non hanno un prezzo specificato. Il loro prezzo le verrà comunicato al più presto, dopo di che potrà scegliere se proseguire con l'ordine o meno.</p>";
            if($noticeOverQuantity)
                $output .= "<p id='noticeOverQuantity'>N.B: Gli articoli indicati da un asterisco sono disponibili in quantità minore di quella desiderata. Verrà al più presto informato sulla loro effettiva disponibilità e sulle tempistiche.</p>";
        }

        return $output;
    }
}
?>
