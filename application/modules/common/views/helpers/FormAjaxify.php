<?php
class Application_View_Helper_FormAjaxify extends Zend_View_Helper_Abstract
{
    protected $_view;
    protected $_configAdmin;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
        $this->_configAdmin = Zend_Registry::get('configAdmin');
    }

    public function formAjaxify($formName, $returnAttribute = null, $popupName = null, $updateAfter = null, $updateUrl = null, $noAction = false)
    {
        $output = "
            <script type='text/javascript'>
                function submit".$formName."()
                {
                    formValues = $('#".$formName."').serializeArray();

                    if(typeof(buildParametersArray)=='function')
                        parameters = formValues.concat(buildParametersArray());
                    else
                    {
                        parameterAjax = new Object();
                        parameterAjax['name'] = 'ajax';
                        parameterAjax['value'] = true;
                        parameters = formValues;
                        parameters.push(parameterAjax);
                    }

                    $.post($('#".$formName."').attr('action'),parameters,
                        function(data){
                            if(data == '')
                                returnValue = 0;
                            else
                                returnValue = parseInt(data);
                            if(!isNaN(returnValue)) {
                                $('#".$formName."').parents('.orbPopup, .dialog-container').dialog('close');
                            ";
        if(!$noAction)
        {
            if($returnAttribute !== null)
                $output .= "
                                    if(returnValue == 0)
                                        returnValue = '';

                                    $.address.parameter('".$returnAttribute."',returnValue);
                                ";
            if($popupName == null)
                $output .= "
                                    listAction = 'List';
                                    action = $.address.parameter('action');
                                    separatore = action.indexOf('-');
                                    if(separatore != -1)
                                        listAction += action.substring(separatore);

                                    $.address.parameter('action',listAction);
                    ";
            $output .= "
                                    updateHash();";

            if($updateAfter !== null)
                $output .= "        Update".$updateAfter."('".$updateUrl."');
                           ";
        }

        $output .= "                                
                            }
                            else
                            {
                                $('#".$formName."').parents('.main').html(data);
                            }
                        }
                    );
                }
                $('#".$formName." :submit').button();

                $('#".$formName."').submit(function(){
                    if(window.uploaded==false)
                    {
                        alert('Prego attendere il caricamento del file');
                    }
                    else
                    {
                    ";

                    if($popupName !== null)
                        $output .= "$('#".$popupName."').dialog('open');";
                    else
                        $output .= "submit".$formName."();";
                    
        $output .= "
                    }

                    return false;
                });
            </script>";
        
		if ($this->_configAdmin->plugins->kcfinder)
		{
            $output .= "
                <script type='text/javascript'>
                    fileElement = $('#".$formName." input[type=file][name*=immagine]');
                    fileElement.before('<button id=selectImage>Seleziona</button>');
            ";
            
            if (!$this->_configAdmin->plugins->uploadify)
	        	$output .= "fileElement.remove();";
	       	
	        $output .= "
                    $('#selectImage').button().click(function() {
                        window.KCFinderURL = $('#pathImmagine');
                        $('#pathImmagine').bind('change',function() {
                                    url = $('#pathImmagine').val();
                                    imgName = url.substring(url.lastIndexOf('/') + 1,url.lastIndexOf('.'));
                                    $('#form-immagine').attr('src',url);
                                    $('#form-immagine').attr('title',imgName);
                                    $('#form-immagine').show();
                                //    $('#form-immagine').css('width',$(imagePreview).attr('width')).css('height',$(imagePreview).attr('height'));
                        });
                        openimmagini();
                        return false;
                    });
                </script>
            ";
		}
		if ($this->_configAdmin->plugins->uploadify)
            $output .= $this->_view->uploadify($formName, true, "
                                            $('#idImmagine').val(idImmagine);
                                            $('#form-immagine').attr('src',fileObj['filePath']);
                                            $('#form-immagine').attr('alt',fileObj['name']);
                                            $('#form-immagine').attr('title',fileObj['name']);
                                            $('#form-immagine').show();
                                        ");

        return $output;
    }
}
?>
