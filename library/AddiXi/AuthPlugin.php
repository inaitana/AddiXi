<?php
class AddiXi_AuthPlugin extends Zend_Controller_Plugin_Abstract {
    protected $_authModule;
    protected $_loginPage;

    public function __construct($authModule)
    {
        $this->_authModule = $authModule;
        $this->_loginPage = array('module' => $authModule, 'controller' => 'login', 'action' => 'index');
    }
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if($this->_authModule == 'admin' && $request->getControllerName() == 'upload' && $request->getParam('AddiXi') != null)
            Zend_Session::setId($request->getParam('AddiXi'));
        
        $auth = Zend_Auth::getInstance();
        $auth->setStorage(new Zend_Auth_Storage_Session('Zend_Auth_'.ucfirst($this->_authModule)));

        Zend_Registry::set(ucfirst($this->_authModule).'_Auth',$auth);
        
        if($request->getModuleName() == $this->_authModule)
            if(!$auth->hasIdentity() && $request->getControllerName() != 'login')
            {
                $request->setModuleName($this->_loginPage['module']);
                $request->setControllerName($this->_loginPage['controller']);
                $request->setActionName($this->_loginPage['action']);
            }
    }
}
?>