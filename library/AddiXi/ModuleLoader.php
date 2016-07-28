<?php
class AddiXi_ModuleLoader extends Zend_Controller_Plugin_Abstract
{
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
    {
        $frontController = Zend_Controller_Front::getInstance();
        $bootstrap =  $frontController->getParam('bootstrap');

        $activeModuleName = $request->getModuleName();
        $moduleBootstrapsList = $bootstrap->modules;

        if (isset($moduleBootstrapsList[$activeModuleName])) {
            $activeModuleBootstrap = $moduleBootstrapsList[$activeModuleName];

            $methodNames = get_class_methods($activeModuleBootstrap);

            foreach ($methodNames as $key => $method) {
                if (strpos($method,'_load')===0)
                    call_user_func(array($activeModuleBootstrap,$method));
            }
        }
    }
}
?>
