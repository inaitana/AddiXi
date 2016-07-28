<?php

class Admin_Form_Categoria extends Zend_Form
{
    protected $_mode;
    protected $_categoryId;
    protected $_config;
    protected $_configAdmin;
    
    public function __construct($mode, $categoryId = 0)
    {
        $this->_mode = $mode;
        $this->_categoryId = $categoryId;
        $this->_config = Zend_Registry::get('config');
        $this->_configAdmin = Zend_Registry::get('configAdmin');
        $this->setAttrib('id', 'form'.ucfirst($mode));
        parent::__construct();
    }
    
    public function init()
    {    	
        if($this->_mode == 'add')
            $this->addElement('hidden','idCategoriaPadre');
        else if($this->_mode == 'edit')
            $this->addElement('select', 'idCategoriaPadre', array(
                'label'      => 'Cambia categoria Padre:',
                'class'		 => 'ui-state-default ui-widget ui-corner-all',
            ));

        if($this->_mode != 'delete')
        {
            $checkName = Zend_Registry::get('db')->quoteInto('idCategoria IS NOT NULL AND idCategoria != ?', $this->_categoryId);

            $this->addElement('text', 'Nome', array(
                'filters'    => array('StringTrim','StripTags'),
                'validators' => array(
                    array('StringLength', false, array(3, 100)),
                    new Zend_Validate_Db_NoRecordExists(
                        array(
                            'table' => 'Nomi Categorie',
                            'field' => 'Nome',
                            'exclude' => $checkName
                        )
                    )
                ),
                'required'   => true,
                'label'      => 'Nome:',
                'maxlength'  => 100,
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

            $this->addElement('radio','QuantitàEsatte',array(
                'label'     => 'Quantità Articoli:',
                'separator' => '',
                'MultiOptions' => array('1' => 'Esatte (Numeriche: 0, 1, 2 ...)', '0' => 'Approssimative (Testuali: in arrivo, limitata, buona ...)')
            ));

            $this->addElement('radio','Attivo',array(
                'label'     => 'Attiva:',
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

        $this->addElement('hidden','id',array('value' => $this->_categoryId));
        $this->addElement('hidden','conferma',array('value' => true));
        $this->addElement('hash','csrf', array(
            'ignore' => true
        ));

        $hiddenArray = array('conferma','id','csrf');
        if($this->_mode != 'edit')
			$hiddenArray[] = 'idCategoriaPadre';
        $this->addDisplayGroup($hiddenArray, 'hidden',array('class' => 'hidden'));
    }
}

