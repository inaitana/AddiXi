<?php
abstract class MicOffMenu_AbstractButton {
    protected $_name;
    protected $_javascript;
    
    function getName()
    {
        return $this->_name;
    }

    function getJavascript()
    {
        return $this->_javascript;
    }
}
?>