<?php
class MicOffMenu_OrbButton extends MicOffMenu_AbstractButton {
    protected $_label;
    protected $_href;
    protected $_img;
    protected $_popup;

    function __construct($name, $label, $href, $img, $popup = false, $submits = false, $customJavascript = null, $customHtml = null, $customOnClose = null)
    {
        $this->_name = $name;
        $this->_label = $label;
        if($href == null)
            $this->_href = "javascript:void(0)";
        else
            $this->_href = $href;
        $this->_img = $img;
        $this->_popup = $popup;
        
        $this->_javascript = "";
        if($this->_popup)
        {
            $this->_javascript .= "
                    function open".$this->_name." ()
                    {
                        dialog".$this->_name." = $('<div id=\"orbPopup".$this->_name."\" class=\"orbPopup\"></div>').hide().appendTo('body');

                        function opendialog".$this->_name."() {
                            $('#orbPopup".$this->_name."').dialog({
                                resizable: false,
                                modal: true,
                                title: '".$this->_label."',
                                position: 'center',
                                width: 'auto',
                   ";
            if($customJavascript)
                $this->_javascript .= "
                                open: function() { ".$customJavascript."},
                ";
            $this->_javascript .= "
                                close: function() {
                                            $(this).dialog('destroy');
                                            $('#orbPopup".$this->_name."').remove();
                                            closeDialog();
                                ";
            if($customOnClose != null)
                $this->_javascript .= $customOnClose;
            $this->_javascript .= "
                                },
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
            ";
            if($customHtml == null)
                $this->_javascript .= "
                        dialog".$this->_name.".load('".$this->_href."',{ajax: true}, opendialog".$this->_name.");
                ";
            else
                $this->_javascript .= "
                    $('#orbPopup".$this->_name."').html(\"".$customHtml."\");
                    opendialog".$this->_name."();
                ";
            
            $this->_javascript .= "
                        return false;
                    }
            ";
        }
        else
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
