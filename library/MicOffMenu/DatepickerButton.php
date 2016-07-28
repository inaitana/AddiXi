<?php
class MicOffMenu_DatepickerButton extends MicOffMenu_AbstractButton {
    protected $_property;
    protected $_label;
    protected $_interval;
    protected $_forActions;

    function __construct($property, $label = null, $interval = false, $showAllLabel = null, $forActions = null)
    {
        $this->_property = $property;
        
        if($label == null)
            $this->_label = 'Scegli '.$property;
        else
            $this->_label = $label;
            
        if($showAllLabel == null)
        	$this->_showAllLabel = 'Annulla';
        else
        	$this->_showAllLabel = $showAllLabel;
            
        $this->_interval = $interval;
        	
        $this->_forActions = $forActions;
    }

    function getJavascript()
    {
        $this->_javascript = "
            $(function() {
				$.datepicker.regional['it'] = {
					closeText: 'Chiudi',
					prevText: '&#x3c;Prec',
					nextText: 'Succ&#x3e;',
					currentText: 'Oggi',
					monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno',
					'Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
					monthNamesShort: ['Gen','Feb','Mar','Apr','Mag','Giu',
					'Lug','Ago','Set','Ott','Nov','Dic'],
					dayNames: ['Domenica','Luned&#236','Marted&#236','Mercoled&#236','Gioved&#236','Venerd&#236','Sabato'],
					dayNamesShort: ['Dom','Lun','Mar','Mer','Gio','Ven','Sab'],
					dayNamesMin: ['Do','Lu','Ma','Me','Gio','Ve','Sa'],
					dateFormat: 'dd/mm/yy', firstDay: 1,
					isRTL: false};
				$.datepicker.setDefaults($.datepicker.regional['it']);
        ";
        if($this->_interval)
	        $this->_javascript .= "
            	$('#datepicker".$this->_property."From').datepicker({
					onSelect: function() {
	        			$('#datepicker".$this->_property."To').datepicker('option','minDate',$('#datepicker".$this->_property."From').datepicker('getDate'));
					   	$.address.parameter('".$this->_property."From', $(this).val());
		                updateHash();
                        $('#showAll".$this->_property."').show();     	
	                }
            	});
            	
            	$('#datepicker".$this->_property."To').datepicker({
					onSelect: function() {
						$('#datepicker".$this->_property."From').datepicker('option','maxDate',$('#datepicker".$this->_property."To').datepicker('getDate'));
					  	$.address.parameter('".$this->_property."To', $(this).val());
		                updateHash();
                        $('#showAll".$this->_property."').show();  
		            }
            	});
            	
                $('#showAll".$this->_property."').click(function() {
                    set".$this->_property."From('');
                    set".$this->_property."To('');
                    $.address.parameter('".$this->_property."From','');
                    $.address.parameter('".$this->_property."To','');
                    updateHash();
                    return false;
                });
        	";
	    else
		    $this->_javascript .= "
            	$('#datepicker".$this->_property."').datepicker({
					onSelect: function() {
						$.address.parameter('".$this->_property."', $(this).val());
		                updateHash();
                        $('#showAll".$this->_property."').show();
		            }
            	});
                	
                $('#showAll".$this->_property."').click(function() {
                    set".$this->_property."('');
                    $.address.parameter('".$this->_property."','');
                    updateHash();
                    return false;
                });
		    ";
        	
       	$this->_javascript .= "         
            });
        ";
       	if($this->_interval)
	       	$this->_javascript .= "
	            function set".$this->_property."From(value)
	            {
	            	if(value == '' && $('#datepicker".$this->_property."To').val() == '')
	            		$('#showAll".$this->_property."').hide();
	            	else
	            		$('#showAll".$this->_property."').show();
	            		 
	       			$('#datepicker".$this->_property."From').val(value);
	       			$('#datepicker".$this->_property."To').datepicker('option','minDate',$('#datepicker".$this->_property."From').datepicker('getDate'));
	            }
	            
	            function set".$this->_property."To(value)
	            {
	            	if(value == '' && $('#datepicker".$this->_property."From').val() == '')
	            		$('#showAll".$this->_property."').hide();
	            	else
	            		$('#showAll".$this->_property."').show();
	            		
	       			$('#datepicker".$this->_property."To').val(value);
	       			$('#datepicker".$this->_property."From').datepicker('option','maxDate',$('#datepicker".$this->_property."To').datepicker('getDate'));
	            }
            ";
       	else
	       	$this->_javascript .= "
	            function set".$this->_property."(value)
	            {
	            	if(value == '')
	            		$('#showAll".$this->_property."').hide();
	            	else
	            		$('#showAll".$this->_property."').show();         
	       			$('#datepicker".$this->_property."').val(value);
	            }
            ";
			
        return $this->_javascript;
    }

    function __toString()
    {
        $output = "
            <div id='buttonDatePicker".$this->_property."' class='momDatePicker momInput'>
                <div id='".$this->_property."Label' class='momInputLabel'>".$this->_label." <a href='#' id='showAll".$this->_property."' style='display: none; text-decoration: underline'>(".$this->_showAllLabel.")</a></div>
                <div id='datepicker".$this->_property."Container' class='momInputFields'>";
        
        if($this->_interval)
	        $output .= "
				<label for='datepicker".$this->_property."From'>Da:</label>
				<input type='text' id='datepicker".$this->_property."From' name='".$this->_property."From' class='datepicker".$this->_property." ui-widget ui-widget-content ui-corner-all'/>
				<label for='datepicker".$this->_property."To'>A:</label>
				<input type='text' id='datepicker".$this->_property."To' name='".$this->_property."To' class='datepicker".$this->_property." ui-widget ui-widget-content ui-corner-all'/>
	        ";
        else
	        $output .= "
				<input type='text' id='datepicker".$this->_property."' name='".$this->_property."' class='datepicker".$this->_property." ui-widget ui-widget-content ui-corner-all'/>
	        ";
	   
	    $output .= "
        		</div>
            </div>
        ";
        
        return $output;
    }
}
?>