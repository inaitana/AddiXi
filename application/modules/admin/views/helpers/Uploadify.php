<?php
class Admin_View_Helper_Uploadify extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function uploadify($formName, $withScriptTags = true, $onComplete)
    {
        if($withScriptTags)
            $output = "<script type='text/javascript'>";
        else
            $output = "";

        $output .= "
                $('#".$formName." input[type=file]').uploadify({
                    'uploader': '/js/uploadify/uploadify.swf',
                    'script':   '".$this->_view->url(array('controller' => 'upload', 'action' => 'immagine'))."',
                    'cancelImg': '/css/images/cancel.png',
                    'folder': '/immagini',
                    'auto': true,
                    'fileDesc': 'Immagini',
                    'fileExt': '*.png;*.PNG;*.jpg;*.JPG;*.jpeg;*.JPEG;*.gif;*.GIF',
                    'sizeLimit': 1048576,
                    'buttonText': 'SFOGLIA',
                    'scriptData': {'AddiXi': '".Zend_Session::getId()."'},
                    'onOpen':       function()
                                    {
                                        window.uploaded = false;
                                    },
                    'onComplete':   function(event, queueID, fileObj, response, data)
                                    {
                                        idImmagine = parseInt(response);
                                        if(isNaN(idImmagine))
                                           alert('Errore durante il caricamento del file');
                                        else
                                        {
                                            ".$onComplete."
                                        }
                                        window.uploaded = true;
                                    }
                });

                $('#".$formName." object').each(
                        function() {
                            $(this).css('width',$(this).attr('width')).css('height',$(this).attr('height'));
                        }
                );
                ";

        if($withScriptTags)
            $output .= "</script>";
        
        return $output;
    }
}
?>
