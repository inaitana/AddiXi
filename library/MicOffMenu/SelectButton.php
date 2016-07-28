<?php
class MicOffMenu_SelectButton extends MicOffMenu_AbstractButton {
    protected $_property;
    protected $_label;
    protected $_action;
    protected $_options;
    protected $_defaultValue;

    function __construct($property, $label = null, $action = 'List')
    {
        $this->_property = $property;
        
        if($label == null)
            $this->_label = 'Seleziona '.$property;
        else
            $this->_label = $label;

        $this->_action = $action;
    }

    function addOption($value, $label, $default = false)
    {
        if($default !== false)
            $this->_defaultValue = $value;
        
        $this->addSubOption(null, $value, $label);
    }

    function addSubOption($fatherValue, $value, $label, $default = false)
    {
        if($default !== false)
            $this->_defaultValue = $value;
        $this->_options[$fatherValue][$value] = $label;
    }

    function setDefault($defaultValue)
    {
        $this->_defaultValue = $defaultValue;
    }

    function getJavascript()
    {
        $this->_javascript = "
            $(function() {
                $('.Select".$this->_property."').hide();
                disableButtons('".$this->_property."');
            });
            
            $('#buttonSelect".$this->_property."').live('click',
                function(){
                    if($('.Select".$this->_property."').is(':visible'))
                        $('.Select".$this->_property."').slideUp();
                    else
                        $('.Select".$this->_property."').slideDown();
            });
            
            $('.optionSelect".$this->_property."').live('click',
                function() {
                    $.address.parameter('".$this->_property."', $(this).parent().attr('id').replace('".$this->_property."',''));
                    if($.address.parameter('action') == null)
                        $.address.parameter('action','".$this->_action."');
                    updateHash();
                    return false;
                }
            );
            
            function set".$this->_property."(value)
            {
                $('.optionSelect".$this->_property."').removeClass('selected');
                if(value == null)
                {
                    $('#".$this->_property."Label').html('".$this->_label."');
                    disableButtons('".$this->_property."');
                }
                else
                {
                    $('#".$this->_property."' + value + ' > a').addClass('selected');
                    label = $('#".$this->_property."' + value + ' > a').html();
                    $('#".$this->_property."Label').html(label);
                    enableButtons('".$this->_property."');
                }
                $('.Select".$this->_property."').slideUp();
            }

            function Update".$this->_property."(updateUrl)
            {
                $.post(updateUrl,{ajax: true},function(data){visible = $('.Select".$this->_property."').is(':visible'); $('.Select".$this->_property."').replaceWith(data); if(!visible) $('.Select".$this->_property."').hide();});
            }

            ";
        
        if($this->_defaultValue !== null)
            $this->_javascript .= "
                $(function() {
                    if($.address.parameter('".$this->_property."') == null)
                        $.address.parameter('".$this->_property."', '".$this->_defaultValue."');
                });
                ";

        return $this->_javascript;
    }

    function getOptions($father = null)
    {
        $output = "<ul";
        if($father == null)
             $output .= " class='Select".$this->_property."'";
        $output .= ">";
        
        foreach($this->_options[$father] as $value => $label)
        {
            $output .= "
                        <li id='".$this->_property.$value."'>
                            <a href='#' class='optionSelect".$this->_property."'>".$label."</a>";

                if(isset($this->_options[$value]))
                    $output .= $this->getOptions($value);

            $output .= " </li>";
        }

        $output .= "</ul>";
        
        return $output;
    }

    function __toString()
    {
        $output = "
            <div id='buttonSelect".$this->_property."' class='momSelect'>
                <span id='".$this->_property."Label'>".$this->_label."</span>
        ";

        $output .= $this->getOptions();
        
        $output .= "
            </div>
        ";
        
        return $output;
    }
}
?>