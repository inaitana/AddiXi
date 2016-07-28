<?php
class MicOffMenu_CustomButton extends MicOffMenu_AbstractButton {
    protected $_buttonHtml;

    function __construct($name, $buttonHtml, $buttonJavascript = '')
    {
        $this->_name = $name;
        $this->_buttonHtml = $buttonHtml;
        $this->_javascript = $buttonJavascript;
    }

    function __toString()
    {
        return "
              <div id=button".$this->_name.">
                  ".$this->_buttonHtml."
              </div>
            ";
    }
}
?>