<?php

class Admin_Form_CambioPassword extends Zend_Form
{       
    public function init()
    {
        $this->setAttrib('class', 'login');
        
        $this->addElement('password', 'VecchiaPassword', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(8, 40))
            ),
            'required'   => true,
            'label'      => 'Password Corrente:',
            'maxlength'  => 40,
            'class'      => 'ui-widget-content ui-corner-all'
        ));
        
        $this->addElement('password', 'Password', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(8, 40))
            ),
            'required'   => true,
            'label'      => 'Nuova Password:',
            'maxlength'  => 40,
            'class'      => 'ui-widget-content ui-corner-all'
        ));
        
        $this->addElement('password', 'ConfermaPassword', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(8, 40)),
                new AddiXi_Form_Validate_PasswordConfirmation()
            ),
            'required'   => true,
            'label'      => 'Conferma Nuova Password:',
            'maxlength'  => 40,
            'ignore' => true,
            'class'      => 'ui-widget-content ui-corner-all'
        ));
        
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
        
        $this->addElement('hidden','conferma',array('value' => 'true', 'ignore' => true));

        $this->addElement('hash','csrf', array(
            'ignore' => true
        ));

        $this->addDisplayGroup(array('conferma', 'csrf'), 'hidden',array('class' => 'hidden'));
    }
}

