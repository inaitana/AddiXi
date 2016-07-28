<?php
class Admin_View_Helper_CKEditor extends Zend_View_Helper_Abstract
{
    protected $_view;
    protected $_config;
    protected $_configAdmin;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
        $this->_config = Zend_Registry::get('config');
        $this->_configAdmin = Zend_Registry::get('configAdmin');
    }

    public function CKEditor()
    {
        if($this->_configAdmin->plugins->ckeditor)
        {
            if(!$this->_configAdmin->plugins->kcfinder)
            {
                $formUpload = new Zend_Form();

                $formUpload->setName('CKEditorImageUpload');

                $formUpload->addElement('file','immagineCKEditor', array(
                    'destination' => realpath(APPLICATION_PATH.'/../public'.$this->_config->paths->images),
                    'MaxFileSize' => 1048576,
                    )
                );

                $formUpload->getElement('immagineCKEditor')->removeDecorator('label');
            }

            $output = "
                <script type='text/javascript'>
                    $('textarea.ckeditor').each(
                        function(){
                            if(CKEDITOR.instances[$(this).attr('name')])
                                delete CKEDITOR.instances[$(this).attr('name')];
                            $(this).ckeditor();
                        }
                    );
";

            if(!$this->_configAdmin->plugins->kcfinder)
            {
                $output .= "
                    CKEDITOR.on( 'dialogDefinition', function( ev )
                    {
                        var dialogName = ev.data.name;
                        var dialogDefinition = ev.data.definition;

                        if ( dialogName == 'link' )
                        {
                            dialogDefinition.removeContents( 'advanced' );

                            var targetTab = dialogDefinition.getContents( 'target' );
                            var targetField = targetTab.get( 'linkTargetType' );
                            targetField[ 'default' ] = '_blank';
                        }
                        if ( dialogName == 'image' )
                        {
                            dialogDefinition.removeContents('advanced');
                            dialogDefinition.minWidth = 450;
                            dialogDefinition.onLoad =   function(){
                                                            $('a[title=\'Cerca sul server\']').after('".addslashes(str_replace(array("\n","\r"),"",$formUpload))."');
                                                            ".$this->_view->uploadify('CKEditorImageUpload', false,
                                                                "$.getJSON('".$this->_view->url(array('module' => 'admin','controller' => 'immagini', 'action' => 'info'))."', {'id': idImmagine}, function(data) {
                                                                        $('input.cke_dialog_ui_input_text').each(
                                                                            function (index) {
                                                                                if(index == 0)
                                                                                    $(this).val(data.path).change();
                                                                                else if(index == 2)
                                                                                    $(this).val(data.width);
                                                                                else if(index == 3)
                                                                                    $(this).val(data.height);
                                                                            }
                                                                        );
                                                                    }
                                                                );"
                                                            )."
                                                        };
                        }
                    });
                ";
            }
            
            $output .= "
                </script>";
            return $output;
        }
    }
}
?>
