<?php
class Frontend_Model_Carrello {
    protected $_carrello;
    protected $_articlesModel;
    protected $_config;

    public function __construct()
    {
        $this->_articlesModel = new Application_Model_Articoli();
        $this->_carrello = new Zend_Session_Namespace('carrello');
        $this->_config = Zend_Registry::get('config');
    }

    public function getCart()
    {
        return $this->_carrello;
    }

    public function addCartArticle($idArticolo, $quantità)
    {
        if(!isset($this->_carrello->{"art".$idArticolo}))
        {
            $articoloCarrello = $this->_articlesModel->findArticle($idArticolo);
            if($articoloCarrello['fuoriProduzione'] === '1' && $quantità > $articoloCarrello['Quantità'])
                return false;
            
            $articoloCarrello['Quantità Carrello'] = $quantità;
            
            $this->_carrello->{"art".$idArticolo} = $articoloCarrello;
        }
        else
        {
            $articoloCarrello = $this->_carrello->{"art".$idArticolo};

            if($articoloCarrello['fuoriProduzione'] === '1' && ($quantità + $articoloCarrello['Quantità Carrello'] > $articoloCarrello['Quantità']))
                return false;
            
            $articoloCarrello['Quantità Carrello'] += $quantità;
            
            $this->_carrello->{"art".$idArticolo} = $articoloCarrello;
        }
    }

    public function editCartArticle($idArticolo, $quantità)
    {
        $articoloCarrello = $this->_carrello->{"art".$idArticolo};

        if($quantità == 0)
            $this->removeCartArticle($idArticolo);
        else if($articoloCarrello['fuoriProduzione'] === '0' || $quantità <= $articoloCarrello['Quantità'])
        {
            $articoloCarrello['Quantità Carrello'] = $quantità;
            $this->_carrello->{"art".$idArticolo} = $articoloCarrello;
        }
        else
            return false;
    }

    public function removeCartArticle($idArticolo)
    {
        unset($this->_carrello->{"art".$idArticolo});
    }

    public function resetCart()
    {
        $this->_carrello->unsetAll();
    }

    public function count()
    {
        $count = 0;
        foreach($this->_carrello as $label => $key)
        {
            if(strpos($label,'art')===0)
                $count += $key['Quantità Carrello'];
        }
        return $count;
    }

    public function getTotal($asFloat = false)
    {
        $total = 0;
        foreach($this->_carrello as $label => $key)
        {
            if(strpos($label,'art')===0)
                $total += $key['Prezzo']*$key['Quantità Carrello'];
        }
        if($asFloat)
            return $total;
        else
            return new Zend_Currency(array('value' => $total));
    }

    public function sendMail($sale)
    {
        $auth = Zend_Registry::get('User_Auth');
        $identity = $auth->getIdentity();
        $mailDb = new Application_Model_DbTable_Email();

        $view = Zend_Layout::getMvcInstance()->getView();
        $view->registerHelper(new Application_View_Helper_RiassuntoOrdine(), 'RiassuntoOrdine');

        $view->nomeNegozio = $this->_config->shopName;
        $view->idVendita = $sale->idVendita;
        $view->cliente = $identity;

        $htmlMailUtente = $view->render('mails/mail_order_user.phtml');

        $mailUtente = array(
            'Mittente' => array('mail' => $this->_config->mail->systemSenderAddress, 'name' => $view->nomeNegozio),
            'Destinatario' => array('mail' => $identity->Email, 'name' => $identity->Nome.' '.$identity->Cognome),
            'Oggetto' => 'Ordine #'.$sale->Numero.' Effettuato',
            'Body' => $htmlMailUtente
        );
        $mailDb->insert($mailUtente);

        $htmlMailAdmin = $view->render('mails/mail_order_admin.phtml');

        $mailAdmin = array(
            'Mittente' => array('mail' => $this->_config->mail->systemSenderAddress, 'name' => 'AddiXi'),
            'Destinatario' => array('mail' => $this->_config->mail->adminAddress, 'name' => $view->nomeNegozio),
            'Oggetto' => 'Nuovo Ordine: #'.$sale->Numero,
            'Body' => $htmlMailAdmin
        );
        $mailDb->insert($mailAdmin);
    }

    public function addCartJavascript($jsCarrelloAperto = 'false')
    {
        $layout = Zend_Layout::getMvcInstance();
        $view = $layout->getView();

        $view->jQuery()->addJavascript("
            $('.svuotaCarrello').live('submit',function(){
                toggleCarrello(false);
                $('#carrello').load('".$view->url(array('module' => 'frontend', 'controller' => 'carrello', 'action' => 'empty'),'default')."',{ajax: true});
                return false;
            });
        ");

         $view->jQuery()->addJavascript("
            $('.eliminaArticolo').live('submit',function(){
                $('#carrello').load('".$view->url(array('module' => 'frontend', 'controller' => 'carrello', 'action' => 'remove'),'default')."',{articolo: $(this).find('#articolo').val(), ajax: true, cart: carrelloAperto});
                return false;
            });
        ");

        $view->jQuery()->addJavascript("
            $('.addToCart').live('submit',function(){
                $('#articoloAggiunto').remove();
                $('<span id=\'articoloAggiunto\'>Articolo aggiunto</span>').insertAfter($('.addToCart :submit')).fadeOut(1500);
                $('#carrello').load('".$view->url(array('module' => 'frontend', 'controller' => 'carrello', 'action' => 'add'),'default')."',{articolo: $(this).find('#articolo').val(), ajax: true, cart: carrelloAperto});
                return false;
            });
        ");

        $view->jQuery()->addJavascript("
            function toggleCarrello(status)
            {
                if(status == undefined)
                    status = !carrelloAperto;

                if(status)
                    $.post('".$view->url(array('module' => 'frontend', 'controller' => 'carrello', 'action' => 'show'),'default')."',{ajax: true}, function(data){ if($('.dettaglioCarrello').length == 0) { $('#carrelloArticoli').after(data); setCarrelloAperto(true); cambiaImmagine(true); $('.dettaglioCarrello input[type=submit]').button(); $('.dettaglioCarrello').hide().slideDown(300); } });
                else
                {
                    $('.dettaglioCarrello').slideUp(200,function(){ $('#dettaglioCarrello').remove()});
                    setCarrelloAperto(false);
                    cambiaImmagine(false);
                }
            }

            function cambiaImmagine(status)
            {
                if(status)
                {
                    $('#imgApriCarrello').attr('src','/css/images/frecciasu.png');
                    $('#imgApriCarrello').attr('title','Chiudi Carrello');
                    $('#imgApriCarrello').attr('alt','Chiudi Carrello');
                }
                else
                {
                    $('#imgApriCarrello').attr('src','/css/images/frecciagiu.png');
                    $('#imgApriCarrello').attr('title','Apri Carrello');
                    $('#imgApriCarrello').attr('alt','Apri Carrello');
                }
            }

            function setCarrelloAperto(status)
            {
                carrelloAperto = status;

                //$.address.parameter('cart',status);

                //updateHash();
            }

            $('#linkApriCarrello').live('click',function(){
                toggleCarrello();
                return false;
            });
        ");

        $view->jQuery()->addOnLoad("setCarrelloAperto(".$jsCarrelloAperto.");");
    }
}

?>
