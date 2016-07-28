<?php
class MicOffMenu_Container {
    protected $_name;
    protected $_class;
    protected $_printJavascript = true;

    protected $_orb;
    protected $_tabsArray;
    protected $_activeTabName;
    protected $_javascript;

    function __construct($name = 'mom', $class = 'momContainer')
    {
        $this->_name = $name;
        $this->_class = $class;

        $this->_javascript = "";
        $this->_orb = new MicOffMenu_Orb();
        $this->_tabsArray = array();
    }

    function newTab($name, $label, $href)
    {
        $tab = new MicOffMenu_Tab($name, $label, $href);
        $this->_tabsArray[$name] = $tab;
        return $tab;
    }

    function addTab(MicOffMenu_Tab $tab)
    {
        $this->_tabsArray[$tab->getName()] = $tab;
    }

    function newOrbButton($name, $label, $href, $img, $popup = false, $submits = false, $customJavascript = null, $customHtml = null, $customOnClose = null)
    {
        $orbButton = new MicOffMenu_OrbButton($name, $label, $href, $img, $popup, $submits, $customJavascript, $customHtml, $customOnClose);
        return $this->_orb->newOrbButton($name, $label, $href, $img, $popup, $submits);
    }

    function addOrbButton(MicOffMenu_OrbButton $orbButton)
    {
        $this->_orb->addOrbButton($orbButton);
    }

    function addOrbSeparator()
    {
        $this->_orb->addSeparator();
    }
    
    function newButtonsGroup($tabName, $name, $label = null)
    {
        return $this->_tabsArray[$tabName]->newButtonsGroup($name, $label);
    }

    function addButtonsGroup($tabName, MicOffMenu_ButtonsGroup $buttonsGroup)
    {
        $this->_tabsArray[$tabName]->addButtonsGroup($buttonsGroup);
    }

    function newButton($tabName, $buttonsGroupName, $name, $label, $href, $img, $javascriptAction = null, $needs = null)
    {
        return $this->_tabsArray($tabName)->newButton($buttonsGroupName,$name, $label, $href, $img, $javascriptAction, $needs);
    }

    function newSelectButton($tabName, $buttonsGroupName, $property, $label = null, $action = 'List')
    {
        return $this->_tabsArray[$tabName]->newSelectButton($buttonsGroupName, $property, $label, $action);
    }

    function newRadioButton($tabName, $buttonsGroupName, $property, $label = null, $forActions = null)
    {
        return $this->_tabsArray[$tabName]->newRadioButton($buttonsGroupName, $property, $label, $forActions);
    }

    function newCheckboxButton($tabName, $buttonsGroupName, $property, $label = null, $forActions = null, $defaultOn = false)
    {
        return $this->_tabsArray[$tabName]->newCheckboxButton($buttonsGroupName, $property, $label, $forActions, $defaultOn);
    }

    function newComboboxButton($tabName, $buttonsGroupName, $property, $label = null, $showAllLabel = null, $forActions = null)
    {
        return $this->_tabsArray[$tabName]->newComboboxButton($buttonsGroupName, $property, $label, $showAllLabel, $forActions);
    }

    function newDatepickerButton($tabName, $buttonsGroupName, $property, $label = null, $interval = false, $showAllLabel = null, $forActions = null)
    {
        return $this->_tabsArray[$tabName]->newDatepickerButton($buttonsGroupName, $property, $label, $interval, $showAllLabel, $forActions);
    }

    function addButton($tabName, $buttonsGroupName, MicOffMenu_AbstractButton $button)
    {
        $this->_tabsArray($tabName)->addButton($buttonsGroupName,$button);
    }

    function getOrb()
    {
        return $this->_orb;
    }

    function getTab($name)
    {
        if (isset($this->_tabsArray[$name]))
            return $this->_tabsArray[$name];
        else
            return null;
    }

    function setActiveTab($name)
    {
        if(isset($this->_tabsArray[$name]))
            $this->_tabsArray[$name]->setActive();

        $this->_activeTabName = $name;
    }

    function setPrintJavascript($value)
    {
        $this->_printJavascript = $value;
    }

    function getJavascript()
    {
        $this->_javascript = "
            $(function(){
                $().MoM('tab".$this->_activeTabName."');
                $('.mom .menu > li > a').live('mouseover',function(){ $(this).addClass('ui-state-hover');});
                $('.mom .menu > li > a').live('mouseout',function(){ $(this).removeClass('ui-state-hover');});
            });
        ";

        $this->_javascript .= $this->_orb->getJavascript();
        
        foreach($this->_tabsArray as $tab)
            $this->_javascript .= $tab->getJavascript();
        
        $this->_javascript .= "
                    function disableButtons(propertyName)
                    {
                        $('.needs' + propertyName).addClass('disabled');
                    }

                    function enableButtons(propertyName)
                    {
                        $('.needs' + propertyName).removeClass('disabled');
                    }
               ";

        $hashNavigation = new MicOffMenu_HashNavigation();
        $this->_javascript .= $hashNavigation->getJavascript();
        
        return $this->_javascript;
    }
    
    function __toString()
    {
        if ($this->_printJavascript)
        {
            $output = "<script type='text/javascript'>
                            ".$this->getJavascript()."
                       </script>";
        }
        else
            $output = "";
        
        $output .= "
        <div id='".$this->_name."Container' class='".$this->_class."'>
            <ul class='mom'>
                <li>";

        $output.= $this->_orb;
        
        $output .= "
                </li>
                <li>
                    <ul class='menu'>
                  ";

        foreach ($this->_tabsArray as $tab)
            $output .= $tab;

        $output .= "
                    </ul>
                </li>
            </ul>
        </div>";

        return $output;
    }
}
?>
