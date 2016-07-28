<?php

class Admin_Form_Marca extends Zend_Form
{
    protected $_mode;
    protected $_brandId;
    protected $_config;
    
    public function __construct($mode, $brandId = 0)
    {
        $this->_mode = $mode;
        $this->_brandId = $brandId;
        $this->_config = Zend_Registry::get('config');
        $this->setAttrib('id', 'form'.ucfirst($mode));
        parent::__construct();
    }
    
    public function init()
    {
        if($this->_mode != 'delete')
        {
            $this->addElement('text', 'Nome', array(
                'filters'    => array('StringTrim','StripTags'),
                'validators' => array(
                    array('StringLength', false, array(3, 100)),
                    new Zend_Validate_Db_NoRecordExists(
                        array(
                            'table' => 'Marche',
                            'field' => 'Nome',
                            'exclude' => array(
                                'field' => 'idMarca',
                                'value' => $this->_brandId
                            )
                        )
                    )
                ),
                'required'   => true,
                'label'      => 'Nome:',
                'maxlength'  => 100,
                'size'       => 20,
                'class'      => 'ui-widget-content ui-corner-all'
            ));
            
            $this->addElement('text', 'Sito', array(
                'filters'    => array('StringTrim','StripTags'),
                'validators' => array(
                    array('StringLength', false, array(3, 100))
                ),
                'required'   => false,
                'label'      => 'Sito:',
                'maxlength'  => 100,
                'class'      => 'ui-widget-content ui-corner-all'
            ));
            
            if($this->_mode == 'add')
                $submitLabel = 'Aggiungi';
            else
            {
                $this->addElement('hidden','id',array('value' => $this->_brandId));
                $submitLabel = 'Conferma';
            }
            $this->addElement('submit','submit',array('label' => $submitLabel));
        }
        
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));

        $this->addElement('hash','csrf', array(
            'ignore' => true
        ));
        $this->addElement('hidden','conferma',array('value' => true, 'ignore' => true));

        $this->addDisplayGroup(array('conferma', 'id', 'csrf'), 'hidden',array('class' => 'hidden'));
    }
}

