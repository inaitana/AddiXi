<?php

class Frontend_Form_ConfermaOrdine extends Zend_Form
{   
    protected $_config;
    public function __construct()
    {
        $this->_config = Zend_Registry::get('config');
        $this->setAttrib('id', 'formConfermaOrdine');
        parent::__construct();
    }
    
    public function init()
    {
        $this->addElement('checkbox', 'NewAddress', array(
            'label'     => 'Specifica indirizzo diverso:'
        ));
        
        $this->addElement('text', 'Nome', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(1, 100)),
                array('Alpha',true)
            ),
            'required'   => true,
            'label'      => 'Nome:',
            'maxlength'  => 100,
        ));

        $this->addElement('text', 'Cognome', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(1, 100)),
                array('Alpha',true)
            ),
            'required'   => true,
            'label'      => 'Cognome:',
            'maxlength'  => 100,
        ));

        $this->addElement('text', 'Email', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(1, 40)),
                'EmailAddress'),
            'label'      => 'Email:',
            'maxlength'  => 50,
            'required'   => true
        ));

        $this->addElement('text', 'Indirizzo', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(1, 150))
            ),
            'required'   => true,
            'label'      => 'Indirizzo:',
            'maxlength'  => 150,
        ));

        $this->addElement('text', 'CAP', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(1, 10))
            ),
            'required'   => true,
            'label'      => 'CAP:',
            'maxlength'  => 10,
        ));

        $this->addElement('text', 'Città', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(1, 100))
            ),
            'required'   => true,
            'label'      => 'Città:',
            'maxlength'  => 100,
        ));

        $this->addElement('text', 'Provincia', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(1, 50))
            ),
            'required'   => true,
            'label'      => 'Provincia:',
            'maxlength'  => 50,
        ));
        
        $this->addDisplayGroup(array('NewAddress','Nome', 'Cognome', 'Email', 'Indirizzo','CAP','Città','Provincia'), 'dati',array('legend' => 'Dati Cliente'));

        $this->addElement('textarea', 'Note', array(
            'filters'    => array('StringTrim','StripTags'),
            'required'   => false,
            'label'      => 'Note:'
        ));
        
        $this->addElement('submit','conferma',array('label' => 'Conferma'));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
        
        $this->setDisplayGroupDecorators(array(
            'FormElements',
            'Fieldset'
        ));
        
        $this->addElement('hash','csrf', array(
            'ignore' => true
        ));

        $this->addDisplayGroup(array('csrf'), 'hidden',array('class' => 'hidden'));
    }
}

