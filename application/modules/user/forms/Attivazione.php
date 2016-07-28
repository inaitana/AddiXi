<?php

class User_Form_Attivazione extends Zend_Form
{       
    public function init()
    {
        $this->setAttrib('class', 'login');
        
        $this->addElement('text', 'code', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(32, 32))
            ),
            'required'   => true,
            'label'      => 'Codice di Conferma:',
            'maxlength'  => 32,
        ));

        $this->addElement('submit','Attiva',array('label' => 'Attiva'));

        $this->addElement('hash','csrf', array(
            'ignore' => true
        ));
        $this->addDisplayGroup(array('csrf'), 'hidden',array('class' => 'hidden'));
    }
}

