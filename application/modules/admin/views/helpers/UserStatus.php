<?php
class Admin_View_Helper_UserStatus extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function userStatus()
    {
        $auth = Zend_Registry::get('Admin_Auth');
        if($auth->hasIdentity())
        {
            $userStatus = "Utente Connesso: ".$auth->getIdentity()->Nome." ".$auth->getIdentity()->Cognome." - ";
            
            return $userStatus;
        }
    }
}
?>
