<?php
class Frontend_View_Helper_ShowQuantity extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function showQuantity($article)
    {
        $config = Zend_Registry::get('config');

        $quantity = $article->{'Quantità'};
        $discontinued = $article->{'Fuori Produzione'};
        $exactQuantity = $article->{'QuantitàEsatte'};
        
        if($exactQuantity)
            return $quantity;
        else
        {
            if($quantity==0 && $discontinued==='1')
                return $config->articles->discontinued;
            else
                foreach($config->articles->quantityTexts as $quantityText)
                    if($quantity >= $quantityText->min && ($quantity <= $quantityText->max || $quantityText->max == -1))
                        return "<span class='".str_replace(" ", "_",$quantityText->class)."'>".$quantityText->name."</span>";
        }
        return "?";
    }
}
?>
