<?php

class User_Form_Login extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('class', 'login');
        
        $this->addElement('text', 'username', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                'Alnum',
                array('StringLength', false, array(4, 20)),
            ),
            'required'   => true,
            'label'      => 'Nome Utente:',
        ));

        $this->addElement('password', 'password', array(
            'filters'    => array('StringTrim'),
            'validators' => array(
                array('StringLength', false, array(4, 20)),
            ),
            'required'   => true,
            'label'      => 'Password:',
        ));

        $this->addElement('submit', 'login', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Login',
        ));

        $this->addElement('hidden','loginFrom');

        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'dl', 'class' => 'zend_form')),
            array('Description', array('placement' => 'prepend')),
            'Form'
        ));
        
        $this->addDisplayGroup(array('loginFrom'), 'hidden',array('class' => 'hidden'));
    }
}

