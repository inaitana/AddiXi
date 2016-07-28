<?php

class Admin_Model_DbTable_Categorie extends Application_Model_DbTable_Categorie
{
    protected $_categoriesNamesDb;
    protected $_categoriesDescriptionsDb;
    protected $_rowClass = 'AdminCategoryRow';

    public function init()
    {
        $this->_categoriesNamesDb = new Application_Model_DbTable_NomiCategorie();
        $this->_categoriesDescriptionsDb = new Application_Model_DbTable_DescrizioniCategorie();
        parent::init();
    }

    public function insertCategory($data)
    {
        if($data['idCategoriaPadre'] == 0 || $data['idCategoriaPadre'] == '')
            $data['idCategoriaPadre'] = NULL;
        $categoryData['idCategoriaPadre'] = $data['idCategoriaPadre'];
        $categoryData['QuantitàEsatte'] = $data['QuantitàEsatte'];
        $categoryData['Attivo'] = $data['Attivo'];

        $categoryId = parent::insert($categoryData);

        $categoryNameData['idCategoria'] = $categoryId;
        $categoryNameData['Nome'] = $data['Nome'];
        $categoryNameData['idLingua'] = 1;
        $this->_categoriesNamesDb->insert($categoryNameData);

        $categoryDescriptionData['idCategoria'] = $categoryId;
        $categoryDescriptionData['Descrizione'] = $data['Descrizione'];
        $categoryDescriptionData['idLingua'] = 1;
        $this->_categoriesDescriptionsDb->insert($categoryDescriptionData);
    }

    public function updateCategory($data)
    {
        $category = $this->find($data['id'])->current();

        if($data['idCategoriaPadre'] == 0 || $data['idCategoriaPadre'] == '')
            $data['idCategoriaPadre'] = NULL;
        
        if($data['idCategoriaPadre'] != $category['idCategoriaPadre'])
        {
            $this->fillHole($category['idCategoriaPadre'],$category['Ordine']);
            $category['idCategoriaPadre'] = $data['idCategoriaPadre'];
            $category['Ordine'] = $this->getNextOrder($data['idCategoriaPadre']);
        }

        $category['QuantitàEsatte'] = $data['QuantitàEsatte'];
        $category['Attivo'] = $data['Attivo'];
        $category->save();

        $categoryNameData['Nome'] = $data['Nome'];
        $categoryNameData['idLingua'] = 1;
        $this->_categoriesNamesDb->update($categoryNameData,array('idCategoria = ?' => $data['id']));

        $categoryDescriptionData['Descrizione'] = $data['Descrizione'];
        $categoryDescriptionData['idLingua'] = 1;
        $this->_categoriesDescriptionsDb->update($categoryDescriptionData,array('idCategoria = ?' => $data['id']));
    }
    
    public function deleteCategory($categoryId)
    {
        $this->_categoriesNamesDb->delete(array('idCategoria = ?' => $categoryId));
        $this->_categoriesDescriptionsDb->delete(array('idCategoria = ?' => $categoryId));
        parent::delete(array('idCategoria = ?' => $categoryId));
    }
    
    public function getEmptyCategories()
    {
        return Zend_Registry::get('db')->select()->from(array("Categorie Articoli"),array("idCategoria"))->joinLeftUsing('Articoli View','idCategoria',array())->joinUsing('Nomi Categorie', 'idCategoria',array('Nome'))->group(array("Categorie Articoli" => "idCategoria"))->having("COUNT(`idArticoloSpecifico`) = 0")->query()->fetchAll();
    }

    public function getDescriptionLessCategories()
    {
        return Zend_Registry::get('db')->select()->from(array("Categorie Articoli"),array("idCategoria"))->joinLeftUsing('Descrizioni Categorie','idCategoria',array())->joinUsing('Nomi Categorie', 'idCategoria',array('Nome'))->where("`Descrizione` = ''")->query()->fetchAll();
    }

    protected function getNextOrder($fatherCategoryId)
    {
        if($fatherCategoryId == null || $fatherCategoryId == 0)
            return $this->fetchAll('idCategoriaPadre IS NULL')->count() + 1;
        else
            return $this->fetchAll(array('idCategoriaPadre = ?' => $fatherCategoryId))->count() + 1;
    }

    protected function fillHole($fatherCategoryId, $place)
    {
        if($fatherCategoryId == null)
            $where = array('idCategoriaPadre IS NULL', 'Ordine > ?' => $place);
        else
            $where = array('idCategoriaPadre = ?' => $fatherCategoryId, 'Ordine > ?' => $place);

        $this->update(array('Ordine' => new Zend_Db_Expr('`Ordine` - 1')), $where);
    }
}

class AdminCategoryRow extends CategoryRow
{
    public function activate($status)
    {
        $this->Attivo = $status;
        $this->save();
    }

    public function setOrder($place)
    {
        $this->Ordine = $place;
        $this->save();
    }

    public function canDelete()
    {
        $articles = $this->getArticles(true, null, false, true);
        $subCategories = $this->getSubCategories();
        
        if(count($subCategories) || count($articles))
            return false;
        else
            return true;
    }
}