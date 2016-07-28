<?php
class Frontend_View_Helper_Cart_AddToCart extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function addToCart($idArticolo)
    {
        $form = new Zend_Form();
        $form->setName('addToCart'.$idArticolo);
        $form->setAttrib('class','addToCart');
        $form->setAction($this->_view->url(array('module' => 'frontend', 'controller' => 'carrello', 'action' => 'add'),'default'));
        $form->setMethod('post');

        $form->addElement('submit','Aggiungi',array('label' => 'Aggiungi al Carrello', 'class' => 'largeButton'));
        
        $form->addElement('hidden','articolo',array('value' => $idArticolo));

        $form->addElement('hidden','returnTo',array('value' => $this->_view->ServerUrl(true)));

        $form->addDisplayGroup(array('articolo','returnTo'), 'hidden',array('class' => 'hidden'));


        return $form;
    }
}
?>