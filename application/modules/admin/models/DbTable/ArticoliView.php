<?php

class Admin_Model_DbTable_ArticoliView extends Application_Model_DbTable_ArticoliView
{
    protected $_genericArticlesDb;
    protected $_specificArticlesDb;
    protected $_articlesNamesDb;
    protected $_articlesDescriptionsDb;
    protected $_brandsDb;

    public function init()
    {
        $this->_genericArticlesDb = new Admin_Model_DbTable_ArticoliGenerici();
        $this->_specificArticlesDb = new Admin_Model_DbTable_ArticoliSpecifici();
        $this->_articlesNamesDb = new Application_Model_DbTable_NomiArticoli();
        $this->_articlesDescriptionsDb = new Application_Model_DbTable_DescrizioniArticoli();
        $this->_brandsDb = new Application_Model_DbTable_Marche();
        parent::init();
    }

    public function insert($data)
    {
        $genericArticlesData['idCategoria'] = $data['idCategoria'];

        if($data['NuovaMarca']=='hidden')
            $genericArticlesData['idMarca'] = $data['idMarca'];
        else
        {
            $brandData['Nome'] = $data['NuovaMarca'];
            $brandId = $this->_brandsDb->insert($brandData);
            $genericArticlesData['idMarca'] = $brandId;
        }
        
        $genericArticleId = $this->_genericArticlesDb->insert($genericArticlesData);

        $genericArticleNameData['idArticolo'] = $genericArticleId;
        $genericArticleNameData['Nome'] = $data['Nome'];
        $genericArticleNameData['idLingua'] = 1;
        $this->_articlesNamesDb->insert($genericArticleNameData);

        $genericArticleDescriptionData['idArticolo'] = $genericArticleId;
        $genericArticleDescriptionData['Descrizione'] = $data['Descrizione'];
        $genericArticleDescriptionData['idLingua'] = 1;
        $this->_articlesDescriptionsDb->insert($genericArticleDescriptionData);

        if($data['idImmagine'] != '')
            $specificArticlesData['idImmagineTagliata'] = $data['idImmagine'];

        $specificArticlesData['idArticolo'] = $genericArticleId;
        $specificArticlesData['Data'] = date('Y-m-d');
        $specificArticlesData['Prezzo di vendita'] = $data['Prezzo'];
        $specificArticlesData['Offerta'] = $data['Offerta'];
        $specificArticlesData['Quantità'] = $data['Quantità'];
        $specificArticlesData['Fuori Produzione'] = $data['fuoriProduzione'];
        $specificArticlesData['Codice Articolo'] = $data['Codice'];
        $specificArticlesData['Attivo'] = $data['Attivo'];
        $specificArticleId = $this->_specificArticlesDb->insert($specificArticlesData);
        
        $specificArticleNameData['idArticoloSpecifico'] = $specificArticleId;
        $specificArticleNameData['Nome'] = $data['Nome'];
        $specificArticleNameData['idLingua'] = 1;
        $this->_articlesNamesDb->insert($specificArticleNameData);

        $specificArticleDescriptionData['idArticoloSpecifico'] = $specificArticleId;
        $specificArticleDescriptionData['Descrizione'] = $data['Descrizione'];
        $specificArticleDescriptionData['idLingua'] = 1;
        $this->_articlesDescriptionsDb->insert($specificArticleDescriptionData);
    }

    public function update($data, $where)
    {
        $genericArticleId = $this->_specificArticlesDb->fetchRow(array('idArticoloSpecifico = ?' => $data['id']))->idArticolo;

        $genericArticlesData['idCategoria'] = $data['idNuovaCategoria'];

        if($data['NuovaMarca']=='hidden')
            $genericArticlesData['idMarca'] = $data['idMarca'];
        else
        {
            $brandData['Nome'] = $data['NuovaMarca'];
            $brandId = $this->_brandsDb->insert($brandData);
            $genericArticlesData['idMarca'] = $brandId;
        }
        
        $this->_genericArticlesDb->update($genericArticlesData,array('idArticolo = ?' => $genericArticleId));

        $genericArticleNameData['Nome'] = $data['Nome'];
        $genericArticleNameData['idLingua'] = 1;
        $this->_articlesNamesDb->update($genericArticleNameData,array('idArticolo = ?' => $genericArticleId));

        $genericArticleDescriptionData['Descrizione'] = $data['Descrizione'];
        $genericArticleDescriptionData['idLingua'] = 1;
        $this->_articlesDescriptionsDb->update($genericArticleDescriptionData,array('idArticolo = ?' => $genericArticleId));

        $specificArticlesData['idImmagineTagliata'] = $data['idImmagine'];       
        $specificArticlesData['Prezzo di vendita'] = $data['Prezzo'];
        $specificArticlesData['Offerta'] = $data['Offerta'];
        $specificArticlesData['Quantità'] = $data['Quantità'];
        $specificArticlesData['Fuori Produzione'] = $data['fuoriProduzione'];
        $specificArticlesData['Codice Articolo'] = $data['Codice'];
        $specificArticlesData['Attivo'] = $data['Attivo'];
        $this->_specificArticlesDb->update($specificArticlesData,array('idArticoloSpecifico = ?' => $data['id']));

        $specificArticleNameData['Nome'] = $data['Nome'];
        $specificArticleNameData['idLingua'] = 1;
        $this->_articlesNamesDb->update($specificArticleNameData,array('idArticoloSpecifico = ?' => $data['id']));

        $specificArticleDescriptionData['Descrizione'] = $data['Descrizione'];
        $specificArticleDescriptionData['idLingua'] = 1;
        $this->_articlesDescriptionsDb->update($specificArticleDescriptionData,array('idArticoloSpecifico = ?' => $data['id']));
    }
    
    public function delete($where)
    {
        $genericArticleId = $this->_specificArticlesDb->fetchRow($where)->idArticolo;

        $specificArticlesCount = $this->fetchAll(array('idArticolo = ?' => $genericArticleId))->count();

        $this->_articlesNamesDb->delete($where);
        $this->_articlesDescriptionsDb->delete($where);
        $this->_specificArticlesDb->delete($where);

        if($specificArticlesCount==1)
        {
            $this->_articlesNamesDb->delete(array('idArticolo = ?' => $genericArticleId));
            $this->_articlesDescriptionsDb->delete(array('idArticolo = ?' => $genericArticleId));
            $this->_genericArticlesDb->find($genericArticleId)->current()->delete();
        }
    }

    public function activate($id, $status)
    {
        $this->_specificArticlesDb->update(array('Attivo' => $status), array('idArticoloSpecifico = ?' => $id));
    }

    public function getPricelessArticles()
    {
        return $this->fetchAll(array("`Prezzo di vendita` = ?" => 0));
    }
    
    public function getDescriptionLessArticles()
    {
        return $this->fetchAll(array("Descrizione = ?" => ""));
    }

    public function getSoldoutArticles()
    {
        return $this->fetchAll($this->select()->where("`Fuori Produzione` = false")->where("`Quantità` = ?",0));
    }
}