<?php

class Admin_Form_Login extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('class', 'login');
        
        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim', 'StringToLower'),
            'validators' => array(
                'Alpha',
                array('StringLength', false, array(5, 20)),
            ),
            'required'   => true,
            'label'      => 'Nome Utente:',
            'class'      => 'ui-widget-content ui-corner-all'
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(8, 20)),
            ),
            'required'   => true,
            'label'      => 'Password:',
            'class'      => 'ui-widget-content ui-corner-all'
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Login',
        ));

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));

        $this->addElement('hash','csrf', array(
            'ignore' => true
        ));

        $this->addDisplayGroup(array('username','password','login'), 'userpass',array('class' => 'ui-corner-all'));
        $this->addDisplayGroup(array('csrf'), 'hidden',array('class' => 'hidden'));
    }
}

