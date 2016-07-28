<?php
class User_Model_Utenti extends Application_Model_Utenti {
    function sendLostPasswordMail($email)
    {
        $customer = $this->getCustomerByMail($email);
        
        if($customer == null || !$customer->Attivo)
            return false;
        else
        {
            $mailDb = new Application_Model_DbTable_Email();
            $customer = $customer->toArray();
            $confirmationCode = md5(uniqid(rand(), true));

            $view = Zend_Layout::getMvcInstance()->getView();

            $view->nomeNegozio = $this->_config->shopName;
            $view->confirmationCode = $confirmationCode;
            $view->username = $customer['Nome Utente'];
            $view->nome = $customer['Nome'];
            $view->cognome = $customer['Cognome'];
            $view->Email = $email;

            $htmlMailUtente = $view->render('mails/mail_lostPassword.phtml');

            $mail = array(
                'Mittente' => array('mail' => $this->_config->mail->systemSenderAddress, 'name' => $view->nomeNegozio),
                'Destinatario' => array('mail' => $customer['Email'], 'name' => $customer['Nome'].' '.$customer['Cognome']),
                'Oggetto' => 'Conferma Richiesta Password Smarrita',
                'Body' => $htmlMailUtente
            );
            $mailDb->insert($mail);
            
            $this->getUser($customer['idUtente'])->lostPassword($confirmationCode);

            return true;
        }
    }

    function checkLostPasswordCode($email, $userCode)
    {
        $customer = $this->getCustomerByMail($email);

        if($customer == null || !$customer->Attivo)
            return false;
        else
        {
            $customer = $customer->toArray();
            $correctCode = $customer['Attivazione'];
            if($correctCode!==NULL && strlen($correctCode) && $userCode === $correctCode)
            {
                return $this->getUser($customer['idUtente'])->changePassword();
            }
            else
                return false;
        }
    }

    function changePassword($userId, $oldPassword, $newPassword)
    {
        $user = $this->getUser($userId);
        if($user->Password === sha1($oldPassword))
        {
            $user->changePassword($newPassword);
            return true;
        }
        else
            return false;
    }

    function getCustomersForUser($userId)
    {
        $customers = $this->getUser($userId)->getCustomers();
        foreach($customers as $customer)
            $customersIds[] = $customer->idCliente;

        return $customersIds;
    }
}
?>
