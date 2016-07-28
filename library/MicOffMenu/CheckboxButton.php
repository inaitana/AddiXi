<?php
class MicOffMenu_CheckboxButton extends MicOffMenu_AbstractButton {
    protected $_property;
    protected $_label;
    protected $_forActions;
    protected $_defaultOn;

    function __construct($property, $label = null, $forActions = null, $defaultOn = false)
    {
        $this->_property = $property;
        
        if($label == null)
            $this->_label = 'Scegli '.$property;
        else
            $this->_label = $label;

        $this->_forActions = $forActions;
        $this->_defaultOn = $defaultOn;
    }
    function setDefault($defaultOn)
    {
        $this->_defaultOn = $defaultOn;
    }
    
    function getJavascript()
    {
        $this->_javascript = "
            $(function() {
            	$('#checkbox".$this->_property."').button();
            	if($.address.parameter('".$this->_property."') == null || $.address.parameter('".$this->_property."') == '')
            	{
            	";
        if($this->_defaultOn)
        	$this->_javascript .= "
                	$('#checkbox".$this->_property."').attr('checked',true);
                	$('#label".$this->_property."').addClass('ui-state-active');
        	";
        else
        	$this->_javascript .= "
                	$('#checkbox".$this->_property."').attr('checked',false);
            ";
        $this->_javascript .= "
               	}
        ";
        
        if($this->_forActions != null)
        	$this->_javascript .= "
            	actions = '".implode(",",$this->_forActions)."';
//            	window.inputActions['".$this->_property."'] = actions.split(',');
            ";
        	
       	$this->_javascript .= "
            });
            
            $('#checkbox".$this->_property."').live('click',
                function() {
                    $.address.parameter('".$this->_property."', $(this).is(':checked').toString());
                    updateHash();
                }
            );
            
            function set".$this->_property."(value)
            {
                $('.radio".$this->_property."').attr('checked',value);
            }
            ";

        return $this->_javascript;
    }

    function __toString()
    {
        $output = "
            <div id='buttonCheckbox".$this->_property."' class='momCheckbox momInput'>
                <div id='".$this->_property."Label' class='momInputLabel'>&nbsp;</div>
                <div id='checkbox".$this->_property."container' class='momInputFields'>
                	<input id='checkbox".$this->_property."' name='".$this->_property."' type='checkbox'/><label id='label".$this->_property."' for='checkbox".$this->_property."'>".$this->_label."</label>
        		</div>
            </div>
        ";
        
        return $output;
    }
}
?>