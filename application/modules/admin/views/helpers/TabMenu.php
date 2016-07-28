<?php
class Admin_View_Helper_TabMenu extends Zend_View_Helper_Abstract
{
    protected $_view;
    protected $_controllerName;
    protected $_config;
    protected $_configAdmin;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
        $this->_config = Zend_Registry::get('config');
        $this->_configAdmin = Zend_Registry::get('configAdmin');
    }

    public function tabMenu()
    {
        $this->_controllerName = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $auth = Zend_Registry::get('Admin_Auth');
        if(!$auth->hasIdentity())
            return;

        $container = new MicOffMenu_Container();

        $orb = $container->getOrb();

        if($this->_configAdmin->plugins->kcfinder)
            $orb->newOrbButton('immagini', 'Gestione Immagini', null, '/css/images/mom/icon_images.png', true, false, "openKCFinder(window.KCFinderURL)", $this->_view->KCFinder());

        $orb->newOrbButton('SEO', 'Pannello SEO & Marketing', $this->_view->url(array('module' => 'admin', 'controller' => 'SEO', 'action' => 'index'),'default'), '/css/images/mom/icon_SEO.png', true, true);

        $orb->newOrbButton('CambioPassword', 'Cambio Password', $this->_view->url(array('module' => 'admin', 'controller' => 'amministratori', 'action' => 'cambioPassword'),'default'), '/css/images/mom/icon_cambiopassword.png', true, true);

        $orb->newOrbButton('Informazioni', 'Informazioni', $this->_view->url(array('module' => 'admin', 'controller' => 'index', 'action' => 'info'),'default'), '/css/images/mom/icon_info.png', true);

        $orb->addSeparator();

        $container->newOrbButton('Esci', 'Esci', $this->_view->url(array('module' => 'admin', 'controller' => 'login', 'action' => 'logout'),'default'), '/css/images/mom/icon_exit.png');

        $tabHome = $container->newTab('Index', 'Home', $this->_view->url(array('controller' => 'index', 'action' => 'index', 'module' => 'admin'),'default',true));

        $tabArticoli = $container->newTab('Articoli','Articoli',$this->_view->url(array('controller' => 'articoli', 'action' => 'index', 'module' => 'admin'),'default',true));

        $tabOrdini = $container->newTab('Ordini', 'Ordini',$this->_view->url(array('controller' => 'ordini', 'action' => 'index', 'module' => 'admin'),'default',true));
        
        switch ($this->_controllerName)
        {
            case 'articoli':
                $gruppoSelezionaCategoria = $tabArticoli->newButtonsGroup('Categoria','Categoria selezionata');
                
                $selectCategoria = $gruppoSelezionaCategoria->newSelectButton('Categoria', 'Nessuna categoria');
                $categoriesModel = new Admin_Model_Categorie();
                $categoriesModel->addCategoriesToSelectButton($selectCategoria);    
                            
                
                $gruppoCategorie = $tabArticoli->newButtonsGroup('Categorie','Categorie');

                $hrefListaCategorie = $this->_view->url(array('controller' => 'categorie', 'action' => 'list', 'module' => 'admin'),'default',true);
                $gruppoCategorie->newButton('List-Categorie', 'Lista', $hrefListaCategorie, '/css/images/Categorie-Lista.png');
               
                $hrefAggiungiCategoria = $this->_view->url(array('controller' => 'categorie', 'action' => 'add', 'module' => 'admin'),'default',true);
                $gruppoCategorie->newButton('Add-Categorie', 'Aggiungi', $hrefAggiungiCategoria, '/css/images/Categorie-Aggiungi.png');


                $gruppoArticoli = $tabArticoli->newButtonsGroup('Articoli','Articoli');

                $hrefListaArticoli = $this->_view->url(array('controller' => 'articoli', 'action' => 'list', 'module' => 'admin'),'default',true);
                $gruppoArticoli->newButton('List', 'Lista', $hrefListaArticoli, '/css/images/Articoli-Lista.png',null,'Categoria');
               
                $hrefAggiungiArticolo = $this->_view->url(array('controller' => 'articoli', 'action' => 'add', 'module' => 'admin'),'default',true);
                $gruppoArticoli->newButton('Add', 'Aggiungi', $hrefAggiungiArticolo, '/css/images/Articoli-Aggiungi.png',null,'Categoria');


                $gruppoMarche = $tabArticoli->newButtonsGroup($this->_config->language->brandNamePlur,'Anagrafica '.$this->_config->language->brandNamePlur);

                $hrefListaMarche = $this->_view->url(array('controller' => 'marche', 'action' => 'list', 'module' => 'admin'),'default',true);
                $gruppoMarche->newButton('List-Marche', 'Lista', $hrefListaMarche, '/css/images/Marche-Lista.png');

                $hrefAggiungiMarca = $this->_view->url(array('controller' => 'marche', 'action' => 'add', 'module' => 'admin'),'default',true);
                $gruppoMarche->newButton('Add-Marche', 'Aggiungi', $hrefAggiungiMarca, '/css/images/Marche-Aggiungi.png');
                
                $container->setActiveTab('Articoli');
                break;
            case 'ordini':
                $gruppoFiltriStato = $tabOrdini->newButtonsGroup('FiltriStato','Filtra per stato ordine');
                
                $radioAccettati = $gruppoFiltriStato->newRadioButton('accettati','Mostra ordini accettati');
                $radioAccettati->addOption('x', 'Tutti', true);
                $radioAccettati->addOption('1', 'Solo accettati');
                $radioAccettati->addOption('0', 'Solo non accettati');
                
                $radioEvasi = $gruppoFiltriStato->newRadioButton('evasi','Mostra ordini evasi');
                $radioEvasi->addOption('x', 'Tutti', true);
                $radioEvasi->addOption('1', 'Solo evasi');
                $radioEvasi->addOption('0', 'Solo non evasi');
                
                
                $gruppoFiltroCliente = $tabOrdini->newButtonsGroup('FiltroCliente','Filtra per cliente');
                
		        $customersDb = new Admin_Model_DbTable_Clienti();
		        $customersArray = $customersDb->getNamesArray();
		        $comboboxCliente = $gruppoFiltroCliente->newComboboxButton('FiltroCliente','Seleziona cliente','annulla');
		        $comboboxCliente->addOptionsArray($customersArray);
		        
		        
                $gruppoFiltroData = $tabOrdini->newButtonsGroup('FiltroData','Filtra per data');
                
                $gruppoFiltroData->newDatepickerButton('Data','Seleziona un intervallo di date',true);
                
                
                $gruppoOrdini = $tabOrdini->newButtonsGroup('Ordini');

                $gruppoOrdini->newButton('List', 'Mostra', $this->_view->url(array('controller' => 'ordini', 'action' => 'lista', 'module' => 'admin'),'default',true), '/css/images/Ordini.png');
                
                $container->setActiveTab('Ordini');
                break;
            default:
            /*	
                $gruppoRadio = $tabHome->newButtonsGroup('ProvaRadio','Prova Radio');
                
                $radio1 = $gruppoRadio->newRadioButton('provaradio1','Prova Radio 1');
                $radio1->addOption('a', 'Opzione A', true);
                $radio1->addOption('b', 'Opzione B');
                $radio1->addOption('c', 'Opzione C');
                
                $radio2 = $gruppoRadio->newRadioButton('provaradio2','Prova Radio 2');
                $radio2->addOption('a', 'Opzione A');
                $radio2->addOption('b', 'Opzione B', true);
                $radio2->addOption('c', 'Opzione C');
                
                
                $gruppoCheckbox = $tabHome->newButtonsGroup('ProvaCheckbox','Prova Checkbox');
                
                $gruppoCheckbox->newCheckboxButton('Checkbox1','Checkbox 1', null);
                $gruppoCheckbox->newCheckboxButton('Checkbox2','Checkbox 2', null);
                
                
                $gruppoCombobox = $tabHome->newButtonsGroup('ProvaCombobox','Prova Combobox');
                
                $combobox1 = $gruppoCombobox->newComboboxButton('Combobox1','Combobox di prova 1','Boh!');
                $combobox1->addOption('1', 'Uno');
                $combobox1->addOption('2', 'Due');
                $combobox1->addOption('3', 'Tre');
                
                $combobox2 = $gruppoCombobox->newComboboxButton('Combobox2','Combobox di prova 2','Boh!');
                $combobox2->addOptionsArray(array('1' => 'Uno','2' => 'Due', '3' => 'Tre'));
                
                
                $gruppoDatepicker = $tabHome->newButtonsGroup('ProvaDatepicker','Prova Datepicker');
                
                $gruppoDatepicker->newDatepickerButton('datepicker1','Datepicker di prova 1',true);
                $gruppoDatepicker->newDatepickerButton('datepicker2','Datepicker di prova 2');
           */     
                
            	
                $container->setActiveTab('Index');
                break;
        }
        
        $container->setPrintJavascript(false);
        
        $this->_view->jQuery()->addJavascript($container->getJavascript());

        return $container;
    }
}
?>
