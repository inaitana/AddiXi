<?php
class Frontend_View_Helper_Cart_ViewCart extends Zend_View_Helper_Abstract
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
    
    public function viewCart()
    {
        $content = "
            <div id='dettaglioCarrello' class='dettaglioCarrello'>
                <div id='dettaglioCarrello-text'>
                    Articoli nel carrello:
                </div>
                <table id='tabellaCarrello'>
                    <tr>
                        <th>Nome:</th>
                        <th>Quantità:</th>
                        <th>Prezzo:</th>
                        <th></th>
                    </tr>";
        foreach($this->_carrello->getCart() as $articolo)
        {
            $formElimina = new Zend_Form();
            $formElimina->setDisableLoadDefaultDecorators(true);

            $formElimina->setName('eliminaArticolo');
            $formElimina->setAttrib('class','eliminaArticolo');

            $formElimina->setAction($this->_view->url(array('module' => 'frontend', 'controller' => 'carrello', 'action' => 'remove'),'default'));
            $formElimina->setMethod('post');

            $formElimina->addElement('hidden','articolo',array('value' => $articolo['idArticoloSpecifico']));

            $formElimina->addElement('hidden','returnTo',array('value' => $this->_view->ServerUrl(true)));
            
            $formElimina->addElement('submit','Rimuovi',array('label' => 'Rimuovi'));

            $formElimina->addElement('hash','csrf');

            $formElimina->clearDecorators()->addDecorators(array('FormElements','Form'))->setElementDecorators(array('ViewHelper'));

            $content .=
                    "<tr id='articoloCarrello".$articolo['idArticoloSpecifico']."' class='articoloCarrello'>
                        <td class='nomeArticoloCarrello'><a class='catalogo linkArticolo' href='".$this->_view->navigation()->findById($articolo['idCategoria'])->uri.'/'.preg_replace("/[^\w]/","_",$articolo['Marca']." ".$articolo['Nome']).".html"."'>".$articolo['Nome']."</a></td>
                        <td class='quantitàArticoloCarrello'>".$articolo['Quantità Carrello']."</td>
                        <td class='prezzoArticoloCarrello'>";
             $price = new Zend_Currency(array('value' => $articolo['Prezzo']));
             $content .= $price->mul($articolo['Quantità Carrello']);
             $content .= "</td>
                        <td class='azioniArticoloCarrello'>".$formElimina."</td>
                    </tr>";
        }
        $content.=
                    "<tr class='totaleCarrello'>
                        <td>Totale</td>
                        <td>".$this->_carrello->count()."</td>
                        <td>".$this->_carrello->getTotal()."</td>
                    </tr>
            </table>";
        if($this->_carrello->count())
        {
            $formSvuota = new Zend_Form();
            $formSvuota->setDisableLoadDefaultDecorators(true);
            $formSvuota->setDecorators(array(
                'FormElements',
                'Form'
            ));

            $formSvuota->setName('svuotaCarrello');
            $formSvuota->setAttrib('class','svuotaCarrello');
            $formSvuota->setAction($this->_view->url(array('module' => 'frontend', 'controller' => 'carrello', 'action' => 'empty'),'default'));
            $formSvuota->setMethod('post');

            $formSvuota->addElement('hidden','returnTo',array('value' => $this->_view->ServerUrl(true)));

            $formSvuota->addElement('submit','Svuota',array('label' => 'Svuota Carrello', 'class' => 'largeButton'));

            $formSvuota->addElement('hash','csrf');

            $formSvuota->clearDecorators()->addDecorators(array('FormElements','Form'))->setElementDecorators(array('ViewHelper'));
            $content .= $formSvuota;

            $formProcedi = new Zend_Form();
            $formProcedi->setDisableLoadDefaultDecorators(true);
            $formProcedi->setDecorators(array(
                'FormElements',
                'Form'
            ));

            $formProcedi->setName('procediCarrello');
            $formProcedi->setAttrib('class','procediCarrello');

            $formProcedi->setAction($this->_view->url(array('module' => 'frontend', 'controller' => 'carrello', 'action' => 'index'),'default'));
            $formProcedi->setMethod('post');

            $formProcedi->addElement('submit','Procedi',array('label' => 'Procedi', 'class' => 'largeButton'));

            $formProcedi->addElement('hash','csrf');

            $formProcedi->clearDecorators()->addDecorators(array('FormElements','Form'))->setElementDecorators(array('ViewHelper'));
            $content .= $formProcedi;
        }
        
        $content .=
        "</div>";

        return $content;
    }
}
?>