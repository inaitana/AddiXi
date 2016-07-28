<?php
class AddiXi_Mail {
    protected $_from;
    protected $_to;
    protected $_subject;
    protected $_HtmlContent;
    protected $_TextContent;
    
    function __construct(array $from, array $to, $subject, $content)
    {
        $this->_from = $from;
        $this->_to = $to;
        $this->_subject = $subject;
        $this->_HtmlContent = $content;
        $this->_TextContent = $this->html2Text($content);
    }

    function send()
    {
        $mail = new Zend_Mail('UTF-8');
        $mail->setFrom($this->_from['mail'], $this->_from['name']);
        $mail->addTo($this->_to['mail'], $this->_to['name']);
        $mail->setSubject($this->_subject);
        $mail->setBodyHtml($this->_HtmlContent);
        $mail->setBodyText($this->_TextContent);
        
        return $mail->send();
    }

    protected function html2Text($html)
    {
        return strip_tags(substr($html,strpos($html,'</style>')));
    }
}
?>
