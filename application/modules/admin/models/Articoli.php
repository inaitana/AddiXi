<?php
class Admin_Model_Articoli extends Application_Model_Articoli {
    protected $_articlesDb;
    protected $_articlesFlowsDb;

    public function __construct() {
        $this->_articlesDb = new Admin_Model_DbTable_ArticoliView();
        $this->_articlesFlowsDb = new Application_Model_DbTable_MovimentiArticoli();
    }

    public function addArticle ($formValues)
    {
        $this->_articlesDb->insert($formValues);
    }

    public function editArticle ($formValues)
    {
        $this->_articlesDb->update($formValues, array('idArticoloSpecifico = ?' => $formValues['id']));
    }

    public function deleteArticle ($id)
    {
        $this->_articlesDb->delete(array('idArticoloSpecifico = ?' => $id));
    }

    public function canDelete ($id)
    {
        if($this->_articlesFlowsDb->fetchAll(array('idArticoloSpecifico = ?' => $id))->count())
            return false;
        else
            return true;
    }

    public function activateArticle ($id, $status)
    {
        $this->_articlesDb->activate($id, $status);
    }

    public function getPricelessArticles()
    {
        return $this->_articlesDb->getPricelessArticles();
    }

    public function getDescriptionLessArticles()
    {
        return $this->_articlesDb->getDescriptionLessArticles();
    }

    public function getSoldoutArticles()
    {
        return $this->_articlesDb->getSoldoutArticles();
    }
}
?>