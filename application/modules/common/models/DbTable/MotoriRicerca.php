<?php

class Application_Model_DbTable_MotoriRicerca extends Zend_Db_Table_Abstract
{
    protected $_name = 'Motori di ricerca';
    protected $_rowClass = 'MotoreRicercaRow';

    public function __construct()
    {
        parent::__construct();
        // $this->initializeDb();
    }

    public function initializeDb()
    {
        if($this->fetchRow(array('`Nome Motore` = ?' => 'Google')) == null)
            $this->insert(array('Nome Motore' => 'Google', 'URL Ping Sitemap' => 'http://www.google.com/webmasters/tools/ping?sitemap=', 'Ultimo Ping Sitemap' => null));

        if($this->fetchRow(array('`Nome Motore` = ?' => 'Yahoo!')) == null)
            $this->insert(array('Nome Motore' => 'Yahoo!', 'URL Ping Sitemap' => '', 'Ultimo Ping Sitemap' => null));

        if($this->fetchRow(array('`Nome Motore` = ?' => 'Ask.com')) == null)
            $this->insert(array('Nome Motore' => 'Ask.com', 'URL Ping Sitemap' => 'http://submissions.ask.com/ping?sitemap=', 'Ultimo Ping Sitemap' => null));

        if($this->fetchRow(array('`Nome Motore` = ?' => 'Bing')) == null)
            $this->insert(array('Nome Motore' => 'Bing', 'URL Ping Sitemap' => 'http://www.bing.com/webmaster/ping.aspx?siteMap=', 'Ultimo Ping Sitemap' => null));
    }
}

class MotoreRicercaRow extends Zend_Db_Table_Row_Abstract
{
    function updateTimestamp() {
        $timestamp = new Zend_Date();
        $this->{'Ultimo Ping Sitemap'} = $timestamp->get('yyyyMMddHHmmss');
        $this->save();        
        return $timestamp->toString();
    }
}
