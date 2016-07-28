<?php
class Admin_View_Helper_OrdersReminder extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function ordersReminder()
    {
        $ordersModel = new Admin_Model_Vendite();

        $ordersReminder = "<ul id='promemoria-ordini' class='promemoria'>";
        
        $unconfirmedOrders = $ordersModel->getSalesList('0');
        $ordersReminder .= "<li id='promemoria-ordini-nonAccettati'>";
        if($unconfirmedOrders)
            $ordersReminder .= "Sono presenti <a href='".htmlentities($this->_view->url(array('module' => 'admin', 'controller' => 'ordini'))."#/?Filtro=0x&action=List")."' class='promemoria-numero'>".count($unconfirmedOrders)."</a> ordini in attesa di accettazione.";
        else
            $ordersReminder .= "Non sono presenti nuovi ordini.";
        $ordersReminder .= "</li>";

        $unsentOrders = $ordersModel->getSalesList('1','0');
        $ordersReminder .= "<li id='promemoria-ordini-nonEvasi'>
                            Sono presenti <a href='".htmlentities($this->_view->url(array('module' => 'admin', 'controller' => 'ordini'))."#/?Filtro=10&action=List")."' class='promemoria-numero'>".count($unsentOrders)."</a> ordini accettati ancora da evadere.
                       </li>";
        $ordersReminder .= "</ul>";
        return $ordersReminder;
    }
}
?>