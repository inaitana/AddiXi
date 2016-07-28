<?php
class MicOffMenu_ComboboxButton extends MicOffMenu_AbstractButton {
    protected $_property;
    protected $_label;
    protected $_showAllLabel;
    protected $_options;
    protected $_forActions;

    function __construct($property, $label = null, $showAllLabel = null, $forActions = null)
    {
        $this->_property = $property;
        
        if($label == null)
            $this->_label = 'Seleziona '.$property;
        else
            $this->_label = $label;
            
        if($showAllLabel == null)
        	$this->_showAllLabel = 'Annulla';
        else
        	$this->_showAllLabel = $showAllLabel;
        	
        $this->_options = array();

        $this->_forActions = $forActions;
    }

    function addOption($value, $label)
    {
        $this->_options[$value] = $label;
    }
    
    function addOptionsArray($optionsArray)
    {
		$this->_options = $this->_options + $optionsArray;
    }

    function getJavascript()
    {
        $this->_javascript = "
            (function( $ ) {
                $.widget( 'ui.combobox', {
                    _create: function() {
                        var self = this;
                        var select = this.element.hide(),
                            selected = select.children( ':selected' ),
                            value = selected.val() ? selected.text() : '';
                        var input = $( '<input>' )
                            .insertAfter( select )
                            .val( value )
                            .autocomplete({
                                delay: 0,
                                minLength: 0,
                                source: function( request, response ) {
                                    var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), 'i' );
                                    response( select.children( 'option' ).map(function() {
                                        var text = $( this ).text();
                                        if ( this.value && ( !request.term || matcher.test(text) ) )
                                            return {
                                                label: text.replace(
                                                    new RegExp(
                                                        '(?![^&;]+;)(?!<[^<>]*)(' +
                                                        $.ui.autocomplete.escapeRegex(request.term) +
                                                        ')(?![^<>]*>)(?![^&;]+;)', 'gi'
                                                    ), '<strong>$1</strong>' ),
                                                value: text,
                                                option: this
                                            };
                                    }) );
                                },
                                select: function( event, ui ) {
                                    ui.item.option.selected = true;
                                    self._trigger( 'selected', event, {
                                        item: ui.item.option
                                    });
                                    
                                    property = $(this).parent().children('select').attr('name');
                                    
                                    $('#showAll'+property).show();
                                    $.address.parameter(property,ui.item.option.value);
                                    updateHash();
                                },
                                change: function( event, ui ) {
                                    if ( !ui.item ) {
                                        var matcher = new RegExp( '^' + $.ui.autocomplete.escapeRegex( $(this).val() ) + '$', 'i' ),
                                            valid = false;
                                        select.children( 'option' ).each(function() {
                                            if ( this.value.match( matcher ) ) {
                                                this.selected = valid = true;
                                                return false;
                                            }
                                        });
                                        if ( !valid ) {
                                            // remove invalid value, as it didn't match anything
                                            $( this ).val( '' );
                                            select.val( '' );
                                            return false;
                                        }
                                    }
                                }
                            })
                            .addClass( 'ui-widget ui-widget-content ui-corner-left' );

                        input.data( 'autocomplete' )._renderItem = function( ul, item ) {
                            return $( '<li></li>' )
                                .data( 'item.autocomplete', item )
                                .append( '<a>' + item.label + '</a>' )
                                .appendTo( ul );
                        };

                        $( '<button>&nbsp;</button>' )
                            .attr( 'tabIndex', -1 )
                            .insertAfter( input )
                            .button({
                                icons: {
                                    primary: 'ui-icon-triangle-1-s'
                                },
                                text: false
                            })
                            .removeClass( 'ui-corner-all' )
                            .addClass( 'ui-corner-right ui-button-icon' )
                            .click(function() {
                                // close if already visible
                                if ( input.autocomplete( 'widget' ).is( ':visible' ) ) {
                                    input.autocomplete( 'close' );
                                    return;
                                }

                                // pass empty string as value to search for, displaying all results
                                input.autocomplete( 'search', '' );
                                input.focus();
                            });
                    }
                });
            })(jQuery);

            $(function() {                
                $('#combobox".$this->_property."').combobox();
                	
                $('#showAll".$this->_property."').click(function() {
                    $.address.parameter('".$this->_property."','');
                    set".$this->_property."();
                    updateHash();
                    return false;
                });
            });
            
            function set".$this->_property."(value)
            {
            	if(value == null)
            		$('#showAll".$this->_property."').hide();
            	else
            		$('#showAll".$this->_property."').show();            		
            		
                $('#combobox".$this->_property."').val(value);
                $('#combobox".$this->_property."Container').children(':text').val($('#combobox".$this->_property."').children('[value='+value+']').html());
            }
        ";

        return $this->_javascript;
    }

    function getOptions()
    {        
    	$output = "";
        foreach($this->_options as $value => $label)
            $output .= "<option value='".$value."'>".$label."</option>";;
        
        return $output;
    }

    function __toString()
    {
        $output = "
            <div id='buttonCombobox".$this->_property."' class='momCombobox momInput'>
                <div id='".$this->_property."Label' class='momInputLabel'>".$this->_label." <a href='#' id='showAll".$this->_property."' style='display: none; text-decoration: underline'>(".$this->_showAllLabel.")</a></div>
                <div id='combobox".$this->_property."Container' class='momInputFields'>
                	<select id='combobox".$this->_property."' name='".$this->_property."'>
                	<option value=''>&nbsp;</option>
        ";

        $output .= $this->getOptions();
        
        $output .= "
                	</select>
        		</div>
            </div>
        ";
        
        return $output;
    }
}
?>