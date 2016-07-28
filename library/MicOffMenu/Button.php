<?php
class MicOffMenu_Button extends MicOffMenu_AbstractButton {
    protected $_label;
    protected $_href;
    protected $_img;
    protected $_needs;

    function __construct($name, $label, $href, $img, $javascriptAction = null, $needs = null)
    {
        $this->_name = $name;
        $this->_label = $label;
        $this->_href = $href;
        $this->_img = $img;
        $this->_needs = $needs;
        
        if ($javascriptAction !== null)
            $this->_javascript = "
                                function do".$this->_name."()
                                {
                                    ".$javascriptAction."
                                }";
    }

    function __toString()
    {
        $class = 'momButton';

        if($this->_needs != null)
            $class .= ' needs'.$this->_needs;
        
        $output = "
              <div id='button".$this->_name."'>
                <a href='".$this->_href."' rel='".$this->_name."' class='".$class."'>
                    ";

        if($this->_img != null)
            $output .= "
                    <img src='".$this->_img."' alt='".$this->_label."' />
                ";

        $output .= $this->_label."
                </a>
              </div>
        ";
        
        return $output;
    }
}
?>