<?php
class Admin_Model_SEO {
    protected $_searchEnginesDb;
    
    function __construct()
    {
        $this->_searchEnginesDb = new Application_Model_DbTable_MotoriRicerca();
    }

    function getSearchEngines()
    {
        return $this->_searchEnginesDb->fetchAll();
    }

    function pingSearchEngine($searchEngineId, $urlSitemap)
    {
        $searchEngine = $this->_searchEnginesDb->find($searchEngineId)->current();
        $urlPing = $searchEngine->{'URL Ping Sitemap'};
        $url = $urlPing.urlencode($urlSitemap);

        $pinger = new Zend_Http_Client();
        $pinger->setConfig(array('timeout' => 10, 'useragent' => 'AddiXi Sitemap Pinger', 'maxredirects' => 2, 'keepalive' => true));
        $pinger->setUri($url);
        $response = $pinger->request();

        if ($response->isSuccessful()) {
            $timestamp = $searchEngine->updateTimestamp();
            return $timestamp;
        } else {
            return false;
        }
    }
}
?>
