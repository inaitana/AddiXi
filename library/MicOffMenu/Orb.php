<?php
class MicOffMenu_Orb {
    protected $_javascript;
    protected $_orbButtonsArray;

    function __construct()
    {
        $this->_orbButtonsArray = array();
        $this->_javascript = "";
    }

    function newOrbButton($name, $label, $href, $img, $popup = false, $submits = false, $customJavascript = null, $customHtml = null, $customOnClose = null)
    {
        $orbButton = new MicOffMenu_OrbButton($name, $label, $href, $img, $popup, $submits, $customJavascript, $customHtml, $customOnClose);
        $this->_orbButtonsArray[$name] = $orbButton;
        return $orbButton;
    }

    function addOrbButton(MicOffMenu_OrbButton $orbButton)
    {
        $this->_orbButtonsArray[$orbButton->getName()] = $orbButton;
    }

    function addSeparator()
    {
        $this->_orbButtonsArray[] = "<li><hr/></li>";
    }

    function getJavascript()
    {
        foreach ($this->_orbButtonsArray as $orbButton)
            if (gettype($orbButton) != 'string')
                $this->_javascript .= $orbButton->getJavascript();

        return $this->_javascript;
    }

    function __toString()
    {
        $output = "
                    <ul class='orb'>
                        <li><a href='javascript:void(0);' class='orbButton'><img src='/css/images/mom/orb_button.png' alt='orb'/></a>
                            <div class='orb-menu'>
                                <div class='orb-header'></div>
                                <div class='orb-left'>
                                    <ul>
                        ";

                foreach ($this->_orbButtonsArray as $orbButton)
                    $output .= $orbButton;

                $output .= "
                                    </ul>
                                </div>
                                <div class='orb-right'>&nbsp;
                                </div>
                            </div>
                        </li>
                    </ul>";

        return $output;
    }
}
?>
