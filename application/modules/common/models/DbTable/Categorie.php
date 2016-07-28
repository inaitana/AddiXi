<?php
class Application_Model_DbTable_Categorie extends Zend_Db_Table_Abstract
{
    protected $_name = 'Categorie Articoli';
    protected $_rowClass = 'CategoryRow';

    protected $_referenceMap = array(
            'CategoriaPadre' => array(
                'columns'	=> array('idCategoriaPadre'),
                'refTableClass'	=> 'Application_Model_DbTable_Categorie',
                'refColumns'	=> array('idCategoria')
            )
    );

	protected $_dependentTables = array('Application_Model_DbTable_Categorie','Application_Model_DbTable_NomiCategorie','Application_Model_DbTable_DescrizioniCategorie','Application_Model_DbTable_ArticoliGenerici');

    public function fetchRoots($onlyactive = true) {
        return $this->fetchAll('idCategoriaPadre IS NULL'.($onlyactive?' AND Attivo = TRUE':''));
    }

    public function findCategoryByName($categoryName)
    {
        $categoryNamesDb = new Application_Model_DbTable_NomiCategorie();
        $category = $categoryNamesDb->fetchRow("Nome LIKE ?",$categoryName);
        if($category!=null)
            return $this->find($category->idCategoria)->current();
        else
            return null;
    }
}

class CategoryRow extends Zend_Db_Table_Row_Abstract
{
    protected $_nome = NULL;
    protected $_descrizione = NULL;
    
    public function getNome()
    {
        if($this->_nome==NULL)
        {
            $categoriesNamesDb = new Application_Model_DbTable_NomiCategorie();
            return $categoriesNamesDb->fetchRow(array('idCategoria = ?' => $this->idCategoria))->Nome;
        }
        else return $this->_nome;
    }

    public function getDescrizione()
    {
        if($this->_descrizione==NULL)
        {
            $categoriesDescriptionsDb = new Application_Model_DbTable_DescrizioniCategorie();
            return $categoriesDescriptionsDb->fetchRow(array('idCategoria = ?' => $this->idCategoria))->Descrizione;
        }
        else return $this->_descrizione;
    }

    public function setNome($nome)
    {
        $this->_nome = $nome;
    }

    public function setDescrizione($descrizione)
    {
        $this->_descrizione = $descrizione;
    }

    public function getChildren()
    {
        return $this->findDependentRowset('Application_Model_DbTable_Categorie');
    }

    public function getArticles($generici = false, $order = NULL, $onlyActive = true, $onlyCategory = false)
    {
        $articlesDb = new Application_Model_DbTable_ArticoliView();
        return $articlesDb->fetchAll($this->getArticlesSelect($order, $onlyActive, $onlyCategory));
    }

    public function getArticlesSelect($order = NULL, $onlyActive = true, $onlyCategory = false)
    {
        $articlesDb = new Application_Model_DbTable_ArticoliView();

        $config = Zend_Registry::get('config');

        if($config->articles->showSubCategories && !$onlyCategory)
        {
            $subCategoriesList = $this->getSubCategoriesIds();
            
            $selectArticles = $articlesDb->select()->where("idCategoria IN (".implode(",",$subCategoriesList).")");
        }
        else
            $selectArticles = $articlesDb->select()->where('idCategoria = ?', $this->idCategoria);

        if($onlyActive)
            $selectArticles = $selectArticles->where('Attivo = TRUE');
        
        if($order!==NULL)
            $selectArticles = $selectArticles->order($order);

        return $selectArticles;
    }

    public function getSubCategories()
    {
        return $this->_table->fetchAll(array('idCategoriaPadre = ?' => $this->idCategoria),'Ordine');
    }

    protected function getSubCategoriesIds()
    {
        $subCategoriesArray = array();

        if($this->Attivo)
        {
            $subCategoriesArray[] = $this->idCategoria;

            $cache = Zend_Registry::get('cache_core');            
            if($cache->test('categoriesNavigation'))
            {
                $catalogNavigation = $cache->load('categoriesNavigation');
                $categoryPage = $catalogNavigation->findOneById($this->idCategoria);
                // trucchetto per prendere tutti i figli... a findAllBy bisogna per forza passare una prorpietà, active sui figli è sempre false perché attivo lo è il padre (ma è già stato aggiunto)
                $children = $categoryPage->findAllBy('active', false);
                foreach($children as $categoryChild)
                    $subCategoriesArray[] = $categoryChild->id;
            }
            else
            {
                $subCategories = $this->findDependentRowset('Application_Model_DbTable_Categorie');

                foreach($subCategories as $subCategory)
                    $subCategoriesArray = array_merge($subCategoriesArray,$subCategory->getSubCategoriesIds());
            }
        }
        return $subCategoriesArray;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(),array('Nome' => $this->getNome(), 'Descrizione' => $this->getDescrizione()));
    }
}
