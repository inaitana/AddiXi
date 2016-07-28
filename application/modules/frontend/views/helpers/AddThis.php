<?php
class Frontend_View_Helper_AddThis extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function addThis($url, $title)
    {
        $config = Zend_Registry::get('config');

        $addthis = "
        	<script type='text/javascript'>
        		$(function() {
	        		if(typeof(addthis) !== 'undefined')
	        			addthis.toolbox('.addthis_toolbox')
        		});
        	</script>
        	
			<div class='addthis_toolbox addthis_default_style' addthis:url='".$url."' addthis:title='".$title."'>
				<a class='addthis_button_facebook'></a>
				<a class='addthis_button_twitter'></a>
				<a class='addthis_button_print'></a>
				<a class='addthis_button_email'></a>
				<a class='addthis_button_favorites'></a>
			</div>
			<script type='text/javascript' src='http://s7.addthis.com/js/250/addthis_widget.js#pubid=ra-4e5baaae60cf5780'></script>
		";
        
        return $addthis;
    }
}
?>
