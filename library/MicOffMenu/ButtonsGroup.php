<?php
class MicOffMenu_ButtonsGroup {
    protected $_name;
    protected $_label;

    protected $_buttonsArray;
    protected $_javascript;

    function __construct($name, $label = null)
    {
        $this->_name = $name;
        if($label == null)
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
        $selectButton = new MicOffMenu_SelectButton($property, $label, $action);
        $this->_buttonsArray["select".$property] = $selectButton;
        return $selectButton;
    }

    function newRadioButton($property, $label = null, $forActions = null)
    {
        $radioButton =  new MicOffMenu_RadioButton($property, $label, $forActions);
        $this->_buttonsArray["radio".$property] = $radioButton;
        return $radioButton;
    }

    function newCheckboxButton($property, $label = null, $forActions = null, $defaultOn = false)
    {
        $checkboxButton =  new MicOffMenu_CheckboxButton($property, $label, $forActions, $defaultOn);
        $this->_buttonsArray["checkbox".$property] = $checkboxButton;
        return $checkboxButton;
    }

    function newComboboxButton($property, $label = null, $showAllLabel = null, $forActions = null)
    {
        $comboboxButton =  new MicOffMenu_ComboboxButton($property, $label, $showAllLabel, $forActions);
        $this->_buttonsArray["combobox".$property] = $comboboxButton;
        return $comboboxButton;
    }

    function newDatepickerButton($property, $label = null, $interval = false, $showAllLabel = null, $forActions = null)
    {
        $datepickerButton =  new MicOffMenu_DatepickerButton($property, $label, $interval, $showAllLabel, $forActions);
        $this->_buttonsArray["datepicker".$property] = $datepickerButton;
        return $datepickerButton;
    }

    function addButton(MicOffMenu_AbstractButton $button)
    {
        $this->_buttonsArray[$button->getName()] = $button;
    }

    function addSeparator()
    {
        $this->_buttonsArray[] = "<li></li>";
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