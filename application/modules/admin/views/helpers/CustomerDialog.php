<?php
class Admin_View_Helper_CustomerDialog extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function customerDialog()
    {
        return "
        <script type='text/javascript'>   
            $('.dettagliCliente').click(
                function(e){
                    if(!$(e.currentTarget).hasClass('disabled'))
                    {
                        window.stopUpdate = true;
                        $.address.parameter('Cliente', $(this).attr('id').replace('Cliente',''));
                        $.address.parameter('dialog','dettagliCliente');
                        updateHash();
                        window.stopUpdate = false;
                    }
                    return false;
                }
            )

            function opendettagliCliente()
            {
                idCliente = $.address.parameter('Cliente');
                $('<div id=cliente-dialog-container class=dialog-container></div>').hide().appendTo('body').load($('.dettagliCliente').attr('href'),{ajax: true, idCliente : idCliente}, function()
                {
                    $('#cliente-dialog-container').dialog({
                        modal: true,
                        position: 'center',
                        width: 'auto',
                        title: 'Dettagli Utente',
                        close: function(){ $(this).dialog('destroy'); $('#cliente-dialog-container').remove(); $.address.parameter('Cliente', ''); closeDialog();},
                        buttons: {
                            'Storico Ordini': function() {
                                $.address.parameter('FiltroCliente', $.address.parameter('Cliente'));
                                $(this).dialog('close');
                                updateHash();
                            },
                            'Chiudi': function() {
                                $(this).dialog('close');
                            }
                        }
                    });
                });
            }
        </script>";
    }
}
?>