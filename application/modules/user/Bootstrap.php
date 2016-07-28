<?php

class User_Bootstrap extends Zend_Application_Module_Bootstrap
{
    protected function _initAutoLoad()
    {
        $autoloader = new Zend_Application_Module_Autoloader(
        array(
        'namespace' => 'User',
        'basePath' => APPLICATION_PATH . '/modules/user')
        );

        $autoloader->addResourceType('cron', 'cron', 'Cron');
        return $autoloader;
    }
    
    public function _loadNavigation()
    {
        $nav = new Zend_Navigation();

        $nav->addPage(
            new Zend_Navigation_Page_Mvc(
                array(
                    'id' => 'user',
                    'label' => 'Area Utente',
                    'module' => 'user',
                    'controller' => 'index',
                    'action' => 'index',
                    'route' => 'default',
                    'title' => 'Area Utente',
                    'pages' => array(
                        new Zend_Navigation_Page_Mvc(
                            array(
                                'id' => 'login',
                                'label' => 'Login Utente',
                                'module' => 'user',
                                'controller' => 'login',
                                'action' => 'index',
                                'route' => 'default',
                                'title' => 'Login Utente',
                                'pages' => array(
                                    new Zend_Navigation_Page_Mvc(
                                        array(
                                            'id' => 'registrazione',
                                            'label' => 'Registrazione Utente',
                                            'module' => 'user',
                                            'controller' => 'login',
                                            'action' => 'register',
                                            'route' => 'default',
                                            'title' => 'Registrazione Utente',
                                        )
                                    ),
                                    new Zend_Navigation_Page_Mvc(
                                        array(
                                            'id' => 'conferma',
                                            'label' => 'Conferma Registrazione',
                                            'module' => 'user',
                                            'controller' => 'login',
                                            'action' => 'activate',
                                            'route' => 'default',
                                            'title' => 'Conferma Registrazione',
                                        )
                                    ),
                                    new Zend_Navigation_Page_Mvc(
                                        array(
                                            'id' => 'lostpassword',
                                            'label' => 'Password Persa',
                                            'module' => 'user',
                                            'controller' => 'login',
                                            'action' => 'lostPassword',
                                            'route' => 'default',
                                            'title' => 'Password Persa',
                                        )
                                    ),
                                    new Zend_Navigation_Page_Mvc(
                                        array(
                                            'id' => 'logout',
                                            'label' => 'Logout',
                                            'module' => 'user',
                                            'controller' => 'login',
                                            'action' => 'logout',
                                            'route' => 'default',
                                            'title' => 'Logout',
                                        )
                                    )
                                )
                            )
                        ),
                        new Zend_Navigation_Page_Mvc(
                            array(
                                'id' => 'ordini',
                                'label' => 'Lista Ordini',
                                'module' => 'user',
                                'controller' => 'ordini',
                                'action' => 'index',
                                'route' => 'default',
                                'title' => 'Lista Ordini',
                                'pages' => array(
                                    new Zend_Navigation_Page_Mvc(
                                        array(
                                            'id' => 'dettaglioOrdine',
                                            'label' => 'Dettaglio Ordine',
                                            'module' => 'user',
                                            'controller' => 'ordini',
                                            'action' => 'dettagli',
                                            'route' => 'default',
                                            'title' => 'Dettaglio Ordine',
                                        )
                                    )
                                )
                            )
                        ),
                        new Zend_Navigation_Page_Mvc(
                            array(
                                'id' => 'cambioPassword',
                                'label' => 'Cambio Password',
                                'module' => 'user',
                                'controller' => 'index',
                                'action' => 'cambioPassword',
                                'route' => 'default',
                                'title' => 'Cambio Password'
                            )
                       )
                    )
                )
            )
        );

        Zend_Registry::set('userNav', $nav);
    }

    public function _loadLayout()
    {
        $config = Zend_Registry::get('config');
        $layout = Zend_Layout::getMvcInstance();

        $layout->setLayoutPath(APPLICATION_PATH .'/modules/user/layouts/scripts');
        $layout->setLayout($config->shopPath);
    }
    
    public function _loadViewHelpers()
    {
        $layout = Zend_Layout::getMvcInstance();

        $view = $layout->getView();
        // BUG jQuery UI
        $view->jQuery()->uiEnable();
        $view->registerHelper(new User_View_Helper_LoginBox(), 'LoginBox');
    }

    public function _loadAuth()
    {
        Zend_Controller_Front::getInstance()->registerPlugin(new AddiXi_AuthPlugin('user'));
    }
}

