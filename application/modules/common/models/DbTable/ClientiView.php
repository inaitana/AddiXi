<?php

class Application_Model_DbTable_ClientiView extends Zend_Db_Table_Abstract
{
    protected $_name = 'Clienti View';
    protected $_primary = 'idCliente';

    protected $_customersDb;
    protected $_usersDb;

    public function init()
    {
        $this->_customersDb = new Application_Model_DbTable_Clienti();
        $this->_usersDb = new Application_Model_DbTable_Utenti();
        parent::init();
    }
    
    public function insert($data)
    {        
        $customerData['Nome'] = $data['Nome'];
        $customerData['Cognome'] = $data['Cognome'];
        $customerData['Indirizzo'] = $data['Indirizzo'];
        $customerData['CAP'] = $data['CAP'];
        $customerData['Città'] = $data['Città'];
        $customerData['Provincia'] = $data['Provincia'];
        $customerData['Email'] = $data['Email'];
        $customerData['Principale'] = true;

        $customerId = $this->_customersDb->insert($customerData);
        $customer = $this->_customersDb->find($customerId)->current();

        $userData['idClientePrincipale'] = $customerId;
        $userData['Nome Utente'] = $data['NomeUtente'];
        $userData['Password'] = sha1($data['Password']);
        $userData['Attivazione'] = $data['ActivationCode'];
        $userData['Attivo'] = 'false';
        $userData['Data Registrazione'] = $data['Now']->format('Y-m-d H:i:s');
        $userData['idLingua'] = 1;

        $customer->idUtente = $this->_usersDb->insert($userData);

        $customer->save();
    }

    public function addCustomerToUser($data, $userId)
    {
        $customerData['Nome'] = $data['Nome'];
        $customerData['Cognome'] = $data['Cognome'];
        $customerData['Indirizzo'] = $data['Indirizzo'];
        $customerData['CAP'] = $data['CAP'];
        $customerData['Città'] = $data['Città'];
        $customerData['Provincia'] = $data['Provincia'];
        $customerData['Email'] = $data['Email'];
        $customerData['Principale'] = false;
        $customerData['idUtente'] = $userId;
        
        return $this->_customersDb->insert($customerData);
    }
}

