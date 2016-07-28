<?php

class Application_Model_DbTable_Utenti extends Zend_Db_Table_Abstract
{
    protected $_name = 'Utenti';
    protected $_rowClass = 'UtentiRow';

    protected $_referenceMap = array(
            'ClientePrincipale' => array(
                'columns'	=> array('idClientePrincipale'),
                'refTableClass'	=> 'Application_Model_DbTable_Clienti',
                'refColumns'	=> array('idCliente')
            )
    );

    protected $_dependentTables = array('Application_Model_DbTable_Clienti');

    public function getNonActivatedUsers() {
        $config = Zend_Registry::get('config');
        return $this->fetchAll($this->select()->where("Attivazione != ?", "")->where("`Data Registrazione` < ?", new Zend_Db_Expr("DATE_SUB(NOW(), INTERVAL ".$config->accounts->activationExpire." HOUR)")));
    }
}

class UtentiRow extends Zend_Db_Table_Row_Abstract
{
    public function lostPassword($confirmationCode)
    {
        $this->Attivazione = $confirmationCode;
        $this->save();
    }

    public function changePassword($newPassword = null)
    {
        if($newPassword == null)
            $newPassword = substr(md5(uniqid(rand(),true)),22);

        $this->Attivazione = '';
        $this->Password = sha1($newPassword);
        $this->save();

        return $newPassword;
    }

    public function getCustomers()
    {
        return $this->findDependentRowset('Application_Model_DbTable_Clienti');
    }
}
