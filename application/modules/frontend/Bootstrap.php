<?php

class Frontend_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected function _initAutoLoad()
    {
        $autoloader = new Zend_Application_Module_Autoloader(
        array(
        'namespace' => 'Frontend',
        'basePath' => APPLICATION_PATH . '/modules/frontend')
        );

        $autoloader->addResourceType('cron', 'cron', 'Cron');
        return $autoloader;
    }
    
    protected function _initRoutes()
    {
        $config = Zend_Registry::get('config');
        $frontController = Zend_Controller_Front::getInstance();

        $router = $frontController->getRouter();

        $router->addRoute(
                'sitemap',
                new Zend_Controller_Router_Route(
                        'sitemap.xml',
                        array(
                              'module' => 'frontend',
                              'controller' => 'catalogo',
                              'action' => 'sitemap'
                             )
                )
        );

        $router->addRoute(
                'categorie',
                new Zend_Controller_Router_Route_Regex(
                        'catalogo/(.*)(\?page=\d)?',
                        array(
                              'module' => 'frontend',
                              'controller' => 'catalogo',
                              'action' => 'categoria'
                             ),
                        array(1 => 'categoryPath',
                              2 => 'page'),
                        'catalogo/%s?page=%s'
                )
        );

        $router->addRoute(
                'marche',
                new Zend_Controller_Router_Route_Regex(
                        'catalogo/'.strtolower($config->language->brandNamePlur).'/(.*)(\?page=\d)?',
                        array(
                              'module' => 'frontend',
                              'controller' => 'catalogo',
                              'action' => 'marca'
                             ),
                        array(1 => 'brand',
                              2 => 'page'),
                        'catalogo/'.strtolower($config->language->brandNamePlur).'/%s?page=%s'
                )
        );

        $router->addRoute(
                'articolo',
                new Zend_Controller_Router_Route_Regex(
                        'catalogo/(.*)/(\w*).html',
                        array(
                              'module' => 'frontend',
                              'controller' => 'catalogo',
                              'action' => 'articolo'
                             ),
                        array(1 => 'categoryPath',
                              2 => 'articleName'),
                        'catalogo/%s/%s.html'
                )
        );

        $router->addRoute(
                'updateBreadcrumb',
                new Zend_Controller_Router_Route(
                        'catalogo/updateBreadcrumb',
                        array(
                              'module' => 'frontend',
                              'controller' => 'catalogo',
                              'action' => 'updatebreadcrumb'
                             )
                )
        );
    }
    
    public function _loadNavigation()
    {
        $nav = new Zend_Navigation();

        $nav->addPage(array(
            'id' => 'home',
            'label' => 'Home',
            'module' => 'frontend',
            'controller' => 'index',
            'action' => 'index',
            'route' => 'default',
            'title' => 'Homepage',
        ));

        $this->addStaticSitemap($nav);

        $nav->addPage(array(
            'id' => 'CatalogoHome',
            'label' => 'Catalogo',
            'module' => 'frontend',
            'controller' => 'catalogo',
            'action' => 'index',
            'route' => 'default',
            'title' => 'Catalogo',
            'class' => 'catalogo'
        ));

        $nav->addPage(
            new Zend_Navigation_Page_Mvc(
                array(
                    'id' => 'carrello',
                    'label' => 'Carrello',
                    'module' => 'frontend',
                    'controller' => 'carrello',
                    'action' => 'index',
                    'route' => 'default',
                    'title' => 'Carrello',
                    'pages' => array(
                        new Zend_Navigation_Page_Mvc(
                            array(
                                'id' => 'confermaCarrello',
                                'label' => 'Conferma Ordine',
                                'module' => 'frontend',
                                'controller' => 'carrello',
                                'action' => 'conferma',
                                'route' => 'default',
                                'title' => 'Conferma Ordine',
                            )
                        )
                    )
                )
            )
        );

        Zend_Registry::set('mainNav', $nav);
    }

    public function _loadLayout()
    {
        $config = Zend_Registry::get('config');
        $layout = Zend_Layout::getMvcInstance();

        $layout->setLayoutPath(APPLICATION_PATH .'/modules/frontend/layouts/scripts');
        $layout->setLayout($config->shopPath);
    }

    public function _loadViewHelpers()
    {
        $layout = Zend_Layout::getMvcInstance();

        $view = $layout->getView();
        // bug jQuery UI
        $view->jQuery()->uiEnable();
        $view->registerHelper(new Frontend_View_Helper_Cart_AddToCart(), 'AddToCart');
        $view->registerHelper(new Frontend_View_Helper_Cart_PreviewCart(), 'PreviewCart');
        $view->registerHelper(new Frontend_View_Helper_Cart_ViewCart(), 'ViewCart');
        $view->registerHelper(new Frontend_View_Helper_ShowQuantity(), 'ShowQuantity');
        $view->registerHelper(new User_View_Helper_LoginBox(), 'LoginBox');
        $view->registerHelper(new Application_View_Helper_FormAjaxify(), 'FormAjaxify');
    }
    
    protected function addStaticSitemap($nav)
    {
        $config = Zend_Registry::get('config');
        if(file_exists(APPLICATION_PATH . '/configs/'.strtolower($this->_shopName).'/static-sitemap.xml'))
        {
            $staticSitemap = new Zend_Config_Xml(APPLICATION_PATH . '/configs/'.strtolower($this->_shopName).'/static-sitemap.xml', 'nav');
            if(count($staticSitemap) > 0)
            {
                if(count($staticSitemap) != 1 || gettype($staticSitemap->current()) != 'string')
                {
                    $staticNavigation = new Zend_Navigation($staticSitemap);
                    $nav->addPages($staticNavigation->getPages());
                }
            }
        }
    }

    public function _loadAuth()
    {
        Zend_Controller_Front::getInstance()->registerPlugin(new AddiXi_AuthPlugin('user'));
    }
}

