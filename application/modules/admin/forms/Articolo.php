<?php

class Admin_Form_Articolo extends Zend_Form
{
    protected $_mode;
    protected $_articleId;
    protected $_config;
    protected $_configAdmin;
    
    public function __construct($mode, $articleId = 0)
    {
        $this->_mode = $mode;
        $this->_articleId = $articleId;
        $this->_config = Zend_Registry::get('config');
        $this->_configAdmin = Zend_Registry::get('configAdmin');
        $this->setAttrib('id', 'form'.ucfirst($mode));
        parent::__construct();
    }
    
    public function init()
    {        
        if($this->_mode != 'delete')
        {
            if($this->_mode == 'edit')
                $imgLabel = "Cambia immagine:";
            else
                $imgLabel = "Carica un'immagine:";

            $this->addElement('hidden','idImmagine');
            $this->addElement('hidden','pathImmagine');
            
            $this->addElement('file','immagine', array(
                'label' => $imgLabel,
                'destination' => realpath(APPLICATION_PATH.'/../public'.$this->_config->paths->images),
                'validators' => array(
                    array('Count',false,1),
                    array('Size', false, 1048576),
                    array('Extension', false, 'jpg,jpeg,png,gif')
                ),
                'MaxFileSize' => 1048576,
                )
            );

            $this->addElement('hidden','idCategoria');
            
            if($this->_mode == 'edit')
                $this->addElement('select', 'idNuovaCategoria', array(
                    'label'      => 'Cambia categoria:',
                	'class'		 => 'ui-state-default ui-widget ui-corner-all',
                ));

            $this->addElement('select', 'idMarca', array(
                'required'   => true,
                'label'      => $this->_config->language->brandNameSing.":",
                'class'		 => 'ui-state-default ui-widget ui-corner-all',
            ));

            $this->addElement('text', 'NuovaMarca', array(
                'filters'    => array('StringTrim','StripTags'),
                'validators' => array(
                    array('StringLength', false, array(3, 50)),
                    new Zend_Validate_Db_NoRecordExists(
                        array(
                            'table' => 'Marche',
                            'field' => 'Nome'
                        )
                    )
                ),
                'required'   => true,
                'label'      => 'Nuova '.$this->_config->language->brandNameSing.':',
                'maxlength'  => 100,
                'value'      => 'hidden',
                'class'      => 'ui-widget-content ui-corner-all'
            ));

            $checkName = Zend_Registry::get('db')->quoteInto('idArticoloSpecifico IS NOT NULL AND idArticoloSpecifico != ?', $this->_articleId);

            $this->addElement('text', 'Nome', array(
                'filters'    => array('StringTrim','StripTags'),
                'validators' => array(
                    array('StringLength', false, array(3, 100)),
                    new Zend_Validate_Db_NoRecordExists(
                        array(
                            'table' => 'Nomi Articoli',
                            'field' => 'Nome',
                            'exclude' => $checkName
                        )
                    )
                ),
                'required'   => true,
                'label'      => 'Nome:',
                'maxlength'  => 100,
                'size'       => 30,
                'class'      => 'ui-widget-content ui-corner-all'
            ));

            if($this->_configAdmin->plugins->ckeditor)
                $class = 'ckeditor';
            else
                $class = '';
            
            $this->addElement('textarea', 'Descrizione', array(
                'filters'    => array('StringTrim', new Zend_Filter_StripTags(array('allowTags' => array('p','span','strong','b','em','i','u','strike','ol','ul','li','a','sub','sup','img'), 'allowAttribs' => array('style','href','src','title','alt','id','class')))),
                'required'   => false,
                'label'      => 'Descrizione:',
                'class'      => $class
            ));

            $this->addElement('text', 'Prezzo', array(
                'filters'    => array('StringTrim', new Zend_Filter_LocalizedToNormalized(array('precision' => 2, 'locale' => 'it'))),
                'validators' => array(new Zend_Validate_Float('en')),
                'label'      => 'Prezzo:',
                'size'       => 5,
                'maxlength'  => 15,
                'class'      => 'ui-widget-content ui-corner-all'
            ));

            $this->addElement('checkbox', 'Offerta', array(
                'label'      => 'In offerta:'
            ));

            $this->addElement('text', 'Quantità', array(
                'filters'    => array('StringTrim'),
                'validators' => array('Digits'),
                'label'      => 'Quantità:',
                'size'       => 2,
                'maxlength'  => 4,
                'class'      => 'ui-widget-content ui-corner-all'
            ));

            $this->addElement('checkbox', 'fuoriProduzione', array(
                'label'      => 'Fuori Produzione:'
            ));

            $this->addElement('text', 'Codice', array(
                'filters'    => array('StringTrim','StripTags'),
                'validators' => array(
                    array('StringLength', false, array(0, 50)),
                    new Zend_Validate_Db_NoRecordExists(
                        array(
                            'table' => 'Articoli Specifici',
                            'field' => 'Codice Articolo',
                            'exclude' => array(
                                'field' => 'idArticoloSpecifico',
                                'value' => $this->_articleId
                            )
                        )
                    )
                ),
                'label'      => 'Codice:',
                'maxlength'  => 50,
                'class'      => 'ui-widget-content ui-corner-all'
            ));

            $this->addElement('radio','Attivo',array(
                'label'     => 'Attivo:',
                'separator' => '',
                'MultiOptions' => array('1' => 'Si', '0' => 'No')
            ));

            if($this->_mode == 'add')
                $submitLabel = 'Aggiungi';
            else
                $submitLabel = 'Conferma';

            $this->addElement('submit','submit',array('label' => $submitLabel));
        }
        
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));

        $this->addElement('hidden','id',array('value' => $this->_articleId));
        $this->addElement('hidden','conferma',array('value' => true));

        $this->addElement('hash','csrf', array(
            'ignore' => true
        ));

        $this->addDisplayGroup(array('idCategoria', 'idImmagine', 'pathImmagine', 'conferma', 'id', 'csrf'), 'hidden',array('class' => 'hidden'));
    }
}

