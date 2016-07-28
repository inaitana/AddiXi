<?php
class AddiXi_Cron_CronPlugin extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        if($request->getControllerName() == 'cron')
        {
            if($_SERVER['SERVER_ADDR'] == $_SERVER['REMOTE_ADDR'])
            {
                $userAgent = new Zend_Http_UserAgent();
                if($userAgent->getDevice()->getBrowser() == 'Wget')
                {
                    $request->setDispatched(true);
                    $cronRunner = new AddiXi_Cron_CronRunner();
                    $cronRunner->start();
                    exit;
                }
            }
        }
    }
}
?>
