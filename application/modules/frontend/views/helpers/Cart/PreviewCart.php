<?php
class Frontend_View_Helper_Cart_PreviewCart extends Zend_View_Helper_Abstract
{
    protected $_view;
    protected $_carrello;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function __construct()
    {
        $this->_carrello = new Frontend_Model_Carrello();
    }

    protected function urlToggleCart($url)
    {        
        if(strpos($url,'cart=1'))
            return str_replace(array('?cart=1','&cart=1'),'',$url);
        else
        {
            if(strpos($url,'?'))
                return $url."&cart=1";
            else
                return $url."?cart=1";
        }
    }
    
    public function previewCart($mostraDettaglioCarrello = false)
    {
        if($mostraDettaglioCarrello)
        {
            $img = 'frecciasu.png';
            $imgtxt = 'Chiudi Carrello';
        }
        else
        {
            $img = 'frecciagiu.png';
            $imgtxt = 'Apri Carrello';
        }

        $content = "
             <a id='linkApriCarrello' href='".htmlentities($this->UrlToggleCart($this->_view->serverUrl(true)))."'>
             <img src='/css/images/cart.png' title='carrello' alt='carrello' /><span id='carrelloArticoli'>
            (".$this->_carrello->Count().") ";
        $content .= new Zend_Currency(array('value' => $this->_carrello->getTotal(true)));
        $content .= "
                <img id='imgApriCarrello' src='/css/images/".$img."' title='".$imgtxt."' alt='".$imgtxt."'/>
            </span>
             </a>";

            if($mostraDettaglioCarrello)
                $content .= $this->_view->viewCart();

        return $content;
    }	
}
?>
