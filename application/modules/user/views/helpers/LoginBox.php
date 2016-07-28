<?php
class User_View_Helper_LoginBox extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function loginBox()
    {
        $auth = Zend_Registry::get('User_Auth');
        
        if($auth->hasIdentity())
        {
            $identity = $auth->getIdentity();
            
            $output = "Utente Connesso: ".$identity->Nome." ".$identity->Cognome."
                <div id='login-actions'>
                    <a href='".$this->_view->url(array('module' => 'user', 'controller' => 'index', 'action' => 'index'),'default',true)."' class='azioniLogin'>Area Utente</a>
                    <a href='".$this->_view->url(array('module' => 'user', 'controller' => 'login', 'action' => 'logout'),'default',true)."' class='azioniLogin'>Esci</a>
                </div>";

            return $output;
        }
        else
        {
            $form = new User_Form_Login();
            $form->setName('loginForm');

            $form->loginFrom->setValue($this->_view->url(array(), 'default'));

            $form->setAction($this->_view->url(array('module' => 'user', 'controller' => 'login', 'action' => 'index'),'default',true));
            $output = $form;
            $output .= "
                <div id='login-actions'>
                    <a href='".$this->_view->url(array('module' => 'user', 'controller' => 'login', 'action' => 'register'),'default',true)."' class='azioniLogin'>Registrazione</a>
                    <a href='".$this->_view->url(array('module' => 'user', 'controller' => 'login', 'action' => 'index'), 'default', true)."?lostPassword=1'>Password Smarrita</a>
                </div>
                <script type='text/javascript'>
                    $('#loginForm :text, #loginForm :password').focus(function(){ $(this).addClass('inputFocus')});
                    $('#loginForm :text, #loginForm :password').blur(function(){ $(this).removeClass('inputFocus')});
                    $('#loginForm input[type=\'submit\']').button();
                </script>
                ";
        }
        
        return $output;
    }
}
?>