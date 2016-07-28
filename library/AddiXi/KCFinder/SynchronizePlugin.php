<?php
class AddiXi_KCFinder_SynchronizePlugin extends Zend_Controller_Plugin_Abstract {
    protected $_actions;
    protected $_imagesModel;

    public function __construct()
    {
        $this->_imagesModel = new Admin_Model_Immagini();
    }
    
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
    	if(!($request->getModuleName() == 'admin' && $request->getControllerName() == 'upload' && $request->getParam('AddiXi') != null))
    	{
    		$this->_actions = new Zend_Session_Namespace('KCActions');
    		
            foreach($this->_actions as $action)
            {
                switch($action['type'])
                {
                    case 'upload':
                        $this->_imagesModel->addImage($action['source']);
                    break;
                    case 'rename':
                        $this->_imagesModel->renameImage($action['source'], $action['dest']);
                    break;
                    case 'copy':
                        $this->_imagesModel->copyImage($action['source'], $action['dest']);
                    break;
                    case 'delete':
                        $this->_imagesModel->deleteImage($action['source']);
                    break;
                    case 'renameDir':
                        $this->_imagesModel->renameDir($action['source'], $action['dest']);
                    break;
                    case 'deleteDir':
                        $this->_imagesModel->deleteDir($action['source']);
                    break;
                }
            }
            $this->_actions->unsetAll();
    	}
    }
}
?>