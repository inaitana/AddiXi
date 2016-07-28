<?php
class Admin_Cron_PingSitemap extends AddiXi_Cron_CronJobAbstract{
    protected $_interval = 3600;
    protected $_settingsDb;
    protected $_seoModel;

    protected function setup()
    {
        $this->_settingsDb = new Admin_Model_Impostazioni();
        $this->_seoModel = new Admin_Model_SEO();
    }

    protected function run()
    {
        if($this->_settingsDb->get('Aggiornato') == true)
        {
            $frontController = Zend_Controller_Front::getInstance();
            $view = Zend_Layout::getMvcInstance()->getView();
            $sitemapUrl = "http://".$frontController->getRequest()->getHttpHost().$view->url(array('module' => 'frontend', 'controller' => 'catalogo', 'action' => 'sitemap'),'sitemap');

            $searchEngines = $this->_seoModel->getSearchEngines();

            foreach($searchEngines as $searchEngine)
                if($searchEngine->{'URL Ping Sitemap'} != '')
                    $this->_seoModel->pingSearchEngine($searchEngine->idMotore, $sitemapUrl);
            
            $this->_settingsDb->set('Aggiornato', false);
        }
        return true;
    }
}
?>
