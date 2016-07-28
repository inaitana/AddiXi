<?php
class MicOffMenu_Tab {
    protected $_name;
    protected $_label;
    protected $_href;
    protected $_active;

    protected $_buttonsGroupsArray;
    protected $_javascript;

    function __construct($name, $label, $href)
    {
        $this->_name = $name;
        $this->_label = $label;
        $this->_href = $href;
        
        $this->_javascript = "";
        $this->_buttonsGroupsArray = array();
    }

    function newButtonsGroup($name, $label = null)
    {
        $buttonsGroup = new MicOffMenu_ButtonsGroup($name, $label);
        $this->_buttonsGroupsArray[$name] = $buttonsGroup;

        return $buttonsGroup;
    }

    function addButtonGroup($groupName, MicOffMenu_ButtonsGroup $buttonsGroup)
    {
        $this->_buttonsGroupsArray[$buttonGroup->getName()] = $buttonsGroup;
    }

    function newButton($groupName, $name, $label, $href, $img, $javascriptAction = null, $needs = null)
    {
        return $this->_buttonsGroupsArray[$groupName]->newButton($name, $label, $href, $img, $javascriptAction, $needs);
    }

    function addButton($groupName, MicOffMenu_AbstractButton $button)
    {
        $this->_buttonsGroupsArray[$groupName]->addButton($button);
    }

    function newSelectButton($groupName, $property, $label = null, $action = 'List')
    {
        return $this->_buttonsGroupsArray[$groupName]->newSelectButton($property, $label, $action);
    }

    function getName()
    {
        return $this->_name;
    }

    function getButtonsGroup($name)
    {
        if (isset($this->_buttonsGroupsArray[$name]))
            return $this->_buttonsGroupsArray[$name];
        else
            return null;
    }

    function getJavascript()
    {
        if($this->_active)
        {
            foreach ($this->_buttonsGroupsArray as $buttonsGroup)
                $this->_javascript .= $buttonsGroup->getJavascript();
        }
        return $this->_javascript;
    }

    function setActive()
    {
        $this->_active = true;
    }

    function __toString()
    {
        $class = "ui-state-default ui-corner-top";
        if($this->_active)
            $class .= ' ui-state-active';

        $output = "
                <li>
                    <a class='".$class."' id='tab".$this->_name."' href='".$this->_href."'>".$this->_label."</a>
                 ";
        if($this->_active)
        {
            $output .= "<ul class='submenu'>";
            if(count($this->_buttonsGroupsArray))
                foreach($this->_buttonsGroupsArray as $buttonsGroup)
                    $output .= $buttonsGroup;
            else
                $output .= "<li style='display:none'>&nbsp;</li>";
            $output .= "
                </ul>";
        }

        $output .= "
                </li>
            ";

        return $output;
    }
}
?>
