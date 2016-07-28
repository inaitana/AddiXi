<?php

class Admin_Model_DbTable_Amministratori extends Zend_Db_Table_Abstract
{
    protected $_name = 'Amministratori';
    protected $_rowClass = 'AmministratoriRow';   
}

class AmministratoriRow extends Zend_Db_Table_Row_Abstract
{
    public function changePassword($newPassword = null)
    {
        if($newPassword == null)
            $newPassword = substr(md5(uniqid(rand(),true)),22);
        
        $this->Password = sha1($newPassword);
        $this->save();

        return $newPassword;
    }  
}
?>
