<?php

class User_Form_Registrazione extends Zend_Form
{   
    protected $_config;
    public function __construct()
    {
        $this->_config = Zend_Registry::get('config');
        parent::__construct();
    }
    
    public function init()
    {
        $this->addElement('text', 'NomeUtente', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(4, 40)),
                    new Zend_Validate_Db_NoRecordExists(
                        array(
                            'table' => 'Utenti',
                            'field' => 'Nome Utente'
                        )
                    ),
                'Alnum'
            ),
            'required'   => true,
            'label'      => 'Nome Utente:',
            'maxlength'  => 40,
        ));

        $this->addElement('password', 'Password', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(8, 40))
            ),
            'required'   => true,
            'label'      => 'Password:',
            'maxlength'  => 40,
        ));
        
        $this->addElement('password', 'ConfermaPassword', array(
            'filters'    => array('StringTrim','StripTags'),
            'validators' => array(
                array('StringLength', false, array(8, 40)),
                new AddiXi_Form_Validate_PasswordConfirmation()
            ),
            'required'   => true,
            'label'      => 'Conferma Password:',
            'maxlength'  => 40,
            'ignore' => true
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

        $this->addDisplayGroup(array('NomeUtente', 'Password', 'ConfermaPassword','Email'), 'login',array('legend' => 'Dati Accesso'));

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
        
        $this->addDisplayGroup(array('Nome', 'Cognome', 'Indirizzo','CAP','Città','Provincia'), 'dati',array('legend' => 'Dati Cliente'));

        $this->addElement('captcha','captcha',array(
            'label' => 'Controllo di sicurezza',
            'captcha' => 'ReCaptcha',
            'captchaOptions' => array(
                    'captcha' => 'ReCaptcha',
                    'PrivKey' => $this->_config->recaptcha->privatekey,
                    'PubKey' => $this->_config->recaptcha->publickey
                ),
            'ignore' => true
            )
        );
        
        $this->addElement('submit','submit',array('label' => 'Conferma', 'ignore' => true));

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

