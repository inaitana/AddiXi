<?php
class MicOffMenu_RadioButton extends MicOffMenu_AbstractButton {
    protected $_property;
    protected $_label;
    protected $_forActions;
    protected $_options;
    protected $_defaultValue;

    function __construct($property, $label = null, $forActions = null)
    {
        $this->_property = $property;
        
        if($label == null)
            $this->_label = 'Scegli '.$property;
        else
            $this->_label = $label;
        	
        $this->_options = array();

        $this->_forActions = $forActions;
    }

    function addOption($value, $label, $default = false)
    {
        if($default !== false)
            $this->_defaultValue = $value;
            
        $this->_options[$value] = $label;
    }

    function setDefault($defaultValue)
    {
        $this->_defaultValue = $defaultValue;
    }
    
    function getJavascript()
    {
        $this->_javascript = "
            $(function() {
            	$('#radio".$this->_property."Options').buttonset();
            	if($.address.parameter('".$this->_property."') == null || $.address.parameter('".$this->_property."') == '')
            	{
                	$('#radio".$this->_property.$this->_defaultValue."').attr('checked',true);
                	$('#label".$this->_property.$this->_defaultValue."').addClass('ui-state-active');
        		}
        ";
        
        if($this->_forActions != null)
        	$this->_javascript .= "
            	actions = '".implode(",",$this->_forActions)."';
//            	window.inputActions['".$this->_property."'] = actions.split(',');
            ";
        	
       	$this->_javascript .= "         
            });
            
            $('.radio".$this->_property."').live('click',
                function() {
                    $.address.parameter('".$this->_property."', $(this).val());
                    updateHash();
                }
            );
            
            function set".$this->_property."(value)
            {
                $('.radio".$this->_property."').attr('checked',false);
                $('.radio".$this->_property."[value=\"'+value+'\"]').attr('checked',true);
            }
            ";

        return $this->_javascript;
    }

    function getOptions()
    {        
    	$output = "";
        foreach($this->_options as $value => $label)
        	$output .= "<input class='radio".$this->_property."' id='radio".$this->_property.$value."' name='".$this->_property."' type='radio' value='".$value."'/><label id='label".$this->_property.$value."' for='radio".$this->_property.$value."'>".$label."</label>";
        
        return $output;
    }

    function __toString()
    {
        $output = "
            <div id='buttonRadio".$this->_property."' class='momRadio momInput'>
                <div id='".$this->_property."Label' class='momInputLabel'>".$this->_label."</div>
                <div id='radio".$this->_property."Options' class='momInputFields'>
        ";

        $output .= $this->getOptions();
        
        $output .= "
        		</div>
            </div>
        ";
        
        return $output;
    }
}
?>