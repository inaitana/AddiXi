<?php
class Admin_View_Helper_CustomerFilterBox extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function customerFilterBox($selectedCustomer = null)
    {
        $customersDb = new Admin_Model_DbTable_Clienti();
        $customersArray = $customersDb->getNamesArray();
        
        $output = "
            <style type='text/css'>
                .ui-button { margin-left: -1px; }
                .ui-button-icon-only .ui-button-text { padding: 0.35em; }
                .ui-autocomplete-input { margin: 0; padding: 0.48em 0 0.47em 0.45em; }
            </style>
            <script type='text/javascript'>
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
                                    //select.val( ui.item.option.value );
                                    self._trigger( 'selected', event, {
                                        item: ui.item.option
                                    });
                                    
                                    $.address.parameter('FiltroCliente',ui.item.option.value);
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
                ";
                if($selectedCustomer != null)
                    $output .= "$('#selectCliente').val(".$selectedCustomer.")";

                $output .= "
                
                $('#selectCliente').combobox();
                $('#toggle').click(function() {
                    $('#combobox').toggle();
                });
                $('#showAll').click(function() {
                    $.address.parameter('FiltroCliente','');
                    updateHash();
                    return false;
                });
            });
            </script>

            <div class='ordini-filtro-cliente'>
            	<label for='selectCliente'>Filtra per Cliente: </label>
                <select id='selectCliente' name='selectCliente'>
                <option value=''>Seleziona un cliente...</option>";

        foreach($customersArray as $id => $customer)
            $output .= "<option value='".$id."'>".$customer."</option>";

        $output .= "
                </select>
                <a href='#' id='showAll'>Mostra Tutti</a>
            </div>
            ";

        return $output;
    }
}
?>