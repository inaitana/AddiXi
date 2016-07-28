<?php
class Admin_View_Helper_KCFinder extends Zend_View_Helper_Abstract
{
    protected $_view;
    protected $_configAdmin;
    protected $_config;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
        $this->_config = Zend_Registry::get('config');
        $this->_configAdmin = Zend_Registry::get('configAdmin');
    }

    public function KCFinder()
    {
        if($this->_configAdmin->plugins->kcfinder)
        {
            $KCFinder = new Zend_Session_Namespace('KCFINDER');

            if(Zend_Registry::get('Admin_Auth')->hasIdentity())
            {
                $KCFinder->disabled = false;
                $KCFinder->maxImageWidth = $this->_config->images->maxWidth;
                $KCFinder->maxImageHeight = $this->_config->images->maxHeight;
                $KCFinder->thumbWidth = $this->_config->images->thumbnails->maxWidth;
                $KCFinder->thumbHeight = $this->_config->images->thumbnails->maxHeight;

                $this->_view->jQuery()->addJavascript("
                    function openKCFinder() {
                        window.KCFinder = {};
                        var input = window.KCFinderURL;
                        window.KCFinderURL = null;
                        $('#orbPopupimmagini iframe').attr('src','/js/kcfinder/browse.php?type=immagini&lang=it');
                        if(input) {
                            window.KCFinder.callBack = function(url) {
                                window.KCFinder = null;
                                $('#orbPopupimmagini').dialog('close');
                                $(input).val(url);
                                if($(input).attr('type')=='hidden')
                                    $(input).change();
                                $.post('".$this->_view->url(array('module' => 'admin', 'controller' => 'immagini', 'action' => 'find'), 'default', true)."', {url: $('#pathImmagine').val()}, function (data) { if(!isNaN(data) && data != '' && data != 0) { $('#idImmagine').val(data); } } );
                            }
                        }
                        
                        $('#orbPopupimmagini').bind('dialogbeforeclose', function() {
                            $('#orbPopupimmagini iframe').attr('src','');
                            $.post('".$this->_view->url(array('module' => 'admin', 'controller' => 'immagini', 'action' => 'index'), 'default', true)."', {sync: 1});
                        });

                        return false;
                    }
                ");
                /*
                        var width  = 800;
                        var height = 600;
                        var left   = (screen.width  - width)/2;
                        var top    = (screen.height - height)/2;
                        var params = 'width='+width+', height='+height;
                        params += ', top='+top+', left='+left;
                        params += ', directories=no';
                        params += ', location=no';
                        params += ', menubar=no';
                        params += ', resizable=yes';
                        params += ', scrollbars=no';
                        params += ', status=no';
                        params += ', toolbar=no';

                        window.open('/js/kcfinder/browse.php?type=immagini&lang=it','kcfinder_single', params);
                    }
                */

                $output = "<iframe name='kcfinder_iframe' style='border:0; width:800px; height:600px' scrolling='no'/>";

                return $output;
            }
            else
                $KCFinder->disabled = true;
        }
    }
}
?>
