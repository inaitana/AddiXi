<?php
class MicOffMenu_ButtonsGroup {
    protected $_name;
    protected $_label;

    protected $_buttonsArray;
    protected $_javascript;

    function __construct($name, $label = null)
    {
        $this->_name = $name;
        if($this->_label == null)
            $this->_label = $name;
        else
            $this->_label = $label;

        $this->_javascript = "";
        $this->_buttonsArray = array();
    }

    function newButton($name, $label, $href, $img, $javascriptAction = null, $needs = null)
    {
        $button = new MicOffMenu_Button($name, $label, $href, $img, $javascriptAction, $needs);
        $this->_buttonsArray[$name] = $button;
        return $button;
    }

    function newSelectButton($property, $label = null, $action = 'List')
    {
        return $this->_buttonsArray[$property]->newSelectButton($property, $label, $action);
    }

    function addButton(MicOffMenu_AbstractButton $button)
    {
        $this->_buttonsArray[$button->getName()] = $button;
    }

    function getName()
    {
        return $this->_name;
    }

    function getJavascript()
    {
        foreach ($this->_buttonsArray as $button)
            $this->_javascript .= $button->getJavascript();

        return $this->_javascript;
    }

    function __toString()
    {
        $output = "
            <li id='group".$this->_name."'>
                ";
        foreach($this->_buttonsArray as $button)
            $output .= $button;
        $output .= "
                <h2>
                    <span>".$this->_label."</span>
                </h2>
            </li>
            ";
        return $output;
    }
}
?>