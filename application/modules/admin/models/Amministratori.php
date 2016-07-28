<?php
class Admin_Model_Amministratori {
    protected $_adminsDb;
    
    function __construct()
    {
        $this->_adminsDb = new Admin_Model_DbTable_Amministratori();
    }

    function getAdmin($id)
    {
        return $this->_adminsDb->find($id)->current();
    }
    
    function changePassword($adminId, $oldPassword, $newPassword)
    {
        $admin = $this->getAdmin($adminId);
        if($admin->Password === sha1($oldPassword))
        {
            $admin->changePassword($newPassword);
            return true;
        }
        else
            return false;
    }
}
?>
