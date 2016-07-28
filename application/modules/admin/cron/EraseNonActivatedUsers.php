<?php
class Admin_Cron_EraseNonActivatedUsers extends AddiXi_Cron_CronJobAbstract{
    protected $_usersDb;
    
    protected function setup()
    {
        $this->_usersDb = new Application_Model_DbTable_Utenti();
    }

    protected function run()
    {
        $users = $this->_usersDb->getNonActivatedUsers();
        foreach($users as $user)
        {
            $customers = $user->getCustomers();

            if($user->delete() < 0)
                return false;
            
            foreach($customers as $customer)
                if($customer->delete() < 0)
                    return false;
        }
        return true;
    }
}
?>
