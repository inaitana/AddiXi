<?php
class Application_Model_Utenti {
    protected $_customersViewDb;
    protected $_usersDb;
    protected $_config;
    
    function __construct()
    {
        $this->_customersViewDb = new Application_Model_DbTable_ClientiView();
        $this->_usersDb = new Application_Model_DbTable_Utenti();
        $this->_config = Zend_Registry::get('config');
    }
    
    function addUser($data)
    {
        $mailDb = new Application_Model_DbTable_Email();
        $activationCode = md5(uniqid(rand(), true));
        $now = new DateTime();

        $view = Zend_Layout::getMvcInstance()->getView();

        $view->nomeNegozio = $this->_config->shopName;
        $view->activationCode = $activationCode;
        $view->now = $now;
        $viewdata['Nome Utente'] = $data['NomeUtente'];
        $viewdata = array_merge($viewdata, $data);
        unset($viewdata['NomeUtente']);
        $view->data = $viewdata;
        
        $htmlMailUtente = $view->render('mails/mail_registration.phtml');

        $mail = array(
            'Mittente' => array('mail' => $this->_config->mail->systemSenderAddress, 'name' => $view->nomeNegozio),
            'Destinatario' => array('mail' => $data['Email'], 'name' => $data['Nome'].' '.$data['Cognome']),
            'Oggetto' => 'Conferma Registrazione',
            'Body' => $htmlMailUtente
        );
        $mailDb->insert($mail);

        $data['ActivationCode'] = $activationCode;
        $data['Now'] = $now;
        
        $this->_customersViewDb->insert($data);
    }

    function activateUser($userName, $userCode)
    {
        $config = Zend_Registry::get('config');
        $user = $this->_usersDb->fetchRow(array('`Nome Utente` = ?' => $userName))->toArray();

        if($user['Attivo'])
            return false;

        $correctCode = $user['Attivazione'];
        if($correctCode!==NULL && strlen($correctCode) && $userCode === $correctCode && strtotime($user['Data Registrazione']) >= strtotime("-".$config->accounts->activationExpire." hours"))
        {
            $user['Attivazione'] = '';
            $user['Attivo'] = TRUE;
            $this->_usersDb->update($user,array('`Nome Utente` = ?' => $userName));
            return true;
        }
        else
            return false;
    }

    function getCustomer($id)
    {
        return $this->_customersViewDb->find($id)->current();
    }

    function getMainCustomer($user)
    {
        return $this->getCustomer($user->idClientePrincipale);
    }

    function getUser($id)
    {
        return $this->_usersDb->find($id)->current();
    }

    function getCustomerByMail($email)
    {
        return $this->_customersViewDb->fetchRow(array('Email = ?' => $email));
    }

    function addCustomerToUser($data, $userId)
    {
        return $this->_customersViewDb->addCustomerToUser($data, $userId);
    }
}
?>
