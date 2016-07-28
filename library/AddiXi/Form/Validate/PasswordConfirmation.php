<?php
    class AddiXi_Form_Validate_PasswordConfirmation extends Zend_Validate_Abstract
    {
        const NOT_MATCH = 'notMatch';
        protected $_messageTemplates = array(
            self::NOT_MATCH => 'Le due password specificate non corrispono.'
        );
        public function isValid($value, $context = null)
        {
            $value = (string) $value;
            $this->_setValue($value);
            if (is_array($context)) {
                if (isset($context['Password'])
                    && ($value == $context['Password']))
                {
                    return true;
                }
            } elseif (is_string($context) && ($value == $context)) {
                return true;
            }
            $this->_error(self::NOT_MATCH);
            return false;
        }
    }
?>
