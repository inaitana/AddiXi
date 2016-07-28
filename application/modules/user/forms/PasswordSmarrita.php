<?php

class User_Form_PasswordSmarrita extends Zend_Form
{
    public function init()
    {
        $this->setAttrib('class', 'login');
        
        $this->addElement('text', 'email', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(1, 40)),
                'EmailAddress'),
            'label'      => 'Email:',
            'maxlength'  => 50,
            'required' => false
        ));

        $this->addElement('submit', 'Conferma', array(
            'required' => false,
            'ignore'   => true,
            'label'    => 'Conferma'
        ));
    }
}

