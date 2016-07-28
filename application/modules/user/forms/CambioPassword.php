<?php

class User_Form_CambioPassword extends Zend_Form
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
        ));
        
        $this->addElement('password', 'Password', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(8, 40))
            ),
            'required'   => true,
            'label'      => 'Nuova Password:',
            'maxlength'  => 40,
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
            'ignore' => true
        ));
        
        $this->addElement('submit','submit',array('label' => 'Conferma', 'ignore' => true));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
        
        $this->addElement('hash','csrf', array(
            'ignore' => true
        ));
        $this->addDisplayGroup(array('csrf'), 'hidden',array('class' => 'hidden'));
    }
}

