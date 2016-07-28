<?php
class Admin_Model_Impostazioni extends Application_Model_Impostazioni {
    function set($settingName, $value)
    {
        if($this->get($settingName) !== null)
            $this->_settingsDb->update(array('Valore' => $value), array('Nome = ?' => $settingName));
        else
            $this->_settingsDb->insert(array('Nome' => $settingName, 'Valore' => $value));

        $this->_cache->clean();
    }
}
?>
