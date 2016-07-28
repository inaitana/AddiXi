<?php
class MicOffMenu_OrbButton extends MicOffMenu_AbstractButton {
    protected $_label;
    protected $_href;
    protected $_img;
    protected $_popup;

    function __construct($name, $label, $href, $img, $popup = false, $submits = false, $customJavascript = null)
    {
        $this->_name = $name;
        $this->_label = $label;
        $this->_href = $href;
        $this->_img = $img;
        $this->_popup = $popup;
        
        $this->_javascript = "";
        if($this->_popup)
        {
            $this->_javascript .= "
                    function open".$this->_name." ()
                    {
                        $('<div id=\"orbPopup".$this->_name."\" class=\"orbPopup\"></div>').hide().appendTo('body').load('".$this->_href."',{ajax: true}, function()
                                {
                                    $('#orbPopup".$this->_name."').dialog({
                                        resizable: false,
                                        modal: true,
                                        title: '".$this->_label."',
                                        position: 'center',
                                        width: 'auto',
                                        close: function(){ $(this).dialog('destroy'); $('#orbPopup".$this->_name."').remove(); closeDialog();},
                                        buttons: {
                                    ";
                        if($submits)
                            $this->_javascript .= "
                                        'OK' : function() {
                                                $('#form".$this->_name."').submit();
                                            },
                                        'Annulla' : function() {
                                                $(this).dialog('close');
                                            }
                                ";
                        else
                            $this->_javascript .= "
                                        'Chiudi' : function() {
                                                $(this).dialog('close');
                                            }";

                        $this->_javascript .= "
                                        }
                                    });
                                }
                            );
                        return false;
                    }
                ";
        }
        
        if($customJavascript != null)
            $this->_javascript .= "
            $(function(){
                $('#orb".$this->_name."').click(function(){
                    ".$customJavascript."
                });
            });";
    }

    function __toString()
    {
        if($this->_popup)
            $class = "class='dialogButton'";
        else
            $class = '';

        return "
                <li>
                    <a href='".$this->_href."' id='orb".$this->_name."' rel='".$this->_name."' ".$class.">
                        <img src='".$this->_img."' alt='".$this->_label."' />
                        <span>".$this->_label."</span>
                    </a>
                </li>
        ";
    }
}
?>
