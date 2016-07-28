<?php
class Application_View_Helper_DisplayMail extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function displayMail()
    {
      	$displayMail = new Zend_Session_Namespace('DisplayMail');
      	
      	if(count($displayMail->mails))
      	{
    		$output = "
		    	<div id='displayMail'>
		    		<div id='displayMail-disclaimer'>
				    	In questa versione dimostrativa, AddiXi non procede all'effettivo invio di email.<br/>
				    	Le seguenti email sarebbero dovute essere inviate nel frattempo:
				    </div>
			";
    		
    		foreach($displayMail->mails as $mail)
    		{
    			$output .= "
    			<div id='mail".$mail['idEmail']."' class='displayMail-mail'>
    				<p><strong>Da: </strong><em>".$mail['Mittente']['name']." (".$mail['Mittente']['mail'].")</em></p>
    				<p><strong>A: </strong><em>".$mail['Destinatario']['name']." (".$mail['Destinatario']['mail'].")</em></p>
    				<p><strong>Oggetto: </strong><em>".$mail['Oggetto']."</em></p>
    				<p>".$mail['Body']."</p>
    			</div>";
    		}
	    	
	    	$output .= "</div>";
	    	
	    	$output .= "<script type='text/javascript'>
	    		$('#displayMail').dialog(
	    			{
			            modal: true,
			            title: 'Email',
			            position: 'center',
			            width: 'auto',
			            buttons: {
				            	'Chiudi' : function() {
				            		$(this).dialog('close');
				            	}
				        }
				    }
		    	);
	    	</script>";
    	
    		unset($displayMail->mails);
    		return $output;
      	}
    }
}
?>
