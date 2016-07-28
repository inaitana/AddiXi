<?php
class Admin_View_Helper_TabMenu extends Zend_View_Helper_Abstract
{
    protected $_view;
    protected $_controllerName;
    protected $_configAdmin;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
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

        if($this->_configAdmin->images->kcfinder)
            $orb->newOrbButton('immagini', 'Gestione Immagini', '#', '/css/images/mom/icon_images.png', false, false, 'openKCFinder()');

        $orb->newOrbButton('SEO', 'Pannello SEO', $this->_view->url(array('module' => 'admin', 'controller' => 'SEO', 'action' => 'index'),'default'), '/css/images/mom/icon_SEO.png', true, true);

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
                $gruppoCategorie = $tabArticoli->newButtonsGroup('Categorie','Anagrafica Categorie');

                $hrefListaCategorie = $this->_view->url(array('controller' => 'categorie', 'action' => 'list', 'module' => 'admin'),'default',true);
                $gruppoCategorie->newButton('List-Categorie', 'Lista', $hrefListaCategorie, '/css/images/list.png');
               
                $hrefAggiungiCategoria = $this->_view->url(array('controller' => 'categorie', 'action' => 'add', 'module' => 'admin'),'default',true);
                $gruppoCategorie->newButton('Add-Categorie', 'Aggiungi', $hrefAggiungiCategoria, '/css/images/add.png');


                $gruppoArticoli = $tabArticoli->newButtonsGroup('Articoli','Anagrafica Articoli');
                
                $selectCategoria = new MicOffMenu_SelectButton('Categoria', 'Seleziona una categoria');

                $categoriesModel = new Admin_Model_Categorie();
                $categoriesModel->addCategoriesToSelectButton($selectCategoria);
                
                $gruppoArticoli->addButton($selectCategoria);

                $hrefListaArticoli = $this->_view->url(array('controller' => 'articoli', 'action' => 'list', 'module' => 'admin'),'default',true);
                $gruppoArticoli->newButton('List', 'Lista', $hrefListaArticoli, '/css/images/list.png',null,'Categoria');
               
                $hrefAggiungiArticolo = $this->_view->url(array('controller' => 'articoli', 'action' => 'add', 'module' => 'admin'),'default',true);
                $gruppoArticoli->newButton('Add', 'Aggiungi', $hrefAggiungiArticolo, '/css/images/add.png',null,'Categoria');


                $gruppoMarche = $tabArticoli->newButtonsGroup('Marche','Anagrafica Marche');

                $hrefListaMarche = $this->_view->url(array('controller' => 'marche', 'action' => 'list', 'module' => 'admin'),'default',true);
                $gruppoMarche->newButton('List-Marche', 'Lista', $hrefListaMarche, '/css/images/list.png');

                $hrefAggiungiMarca = $this->_view->url(array('controller' => 'marche', 'action' => 'add', 'module' => 'admin'),'default',true);
                $gruppoMarche->newButton('Add-Marche', 'Aggiungi', $hrefAggiungiMarca, '/css/images/add.png');

                $container->setActiveTab('Articoli');
                break;
            case 'ordini':
                $gruppoOrdini = $tabOrdini->newButtonsGroup('Ordini');

                $selectFiltro = new MicOffMenu_SelectButton('Filtro');
                $selectFiltro->addOption('xx', 'Tutti gli ordini');
                $selectFiltro->addOption('0x', 'Ordini non accettati', true);
                $selectFiltro->addOption('10', 'Ordini accettati non evasi');
                $selectFiltro->addOption('11', 'Ordini evasi');

                $gruppoOrdini->addButton($selectFiltro);

                $gruppoOrdini->newButton('List', 'Lista', $this->_view->url(array('controller' => 'ordini', 'action' => 'lista', 'module' => 'admin'),'default',true), '/css/images/list.png');
                
                $container->setActiveTab('Ordini');
                break;
            default:
                $container->setActiveTab('Index');
                break;
        }
        
        $container->setPrintJavascript(false);
        
        $this->_view->jQuery()->addJavascript($container->getJavascript());

        return $container;
    }
}
?>
