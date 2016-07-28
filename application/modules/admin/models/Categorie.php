<?php
class Admin_Model_Categorie extends Application_Model_Categorie {
    protected $_selectOptions;

    public function __construct()
    {
        $this->_categoriesDb = new Admin_Model_DbTable_Categorie();
    }

    public function addCategory ($formValues)
    {
        $this->_categoriesDb->insertCategory($formValues);
    }

    public function editCategory ($formValues)
    {
        $this->_categoriesDb->updateCategory($formValues);
    }

    public function deleteCategory ($categoryId)
    {
        $this->_categoriesDb->deleteCategory($categoryId);
    }

    public function orderCategories ($order)
    {
        foreach($order as $place => $id)
        {
            $this->getCategory($id)->setOrder($place+1);
        }
    }

    public function getEmptyCategories()
    {
        return $this->_categoriesDb->getEmptyCategories();
    }

    public function getDescriptionLessCategories()
    {
        return $this->_categoriesDb->getDescriptionLessCategories();
    }
    
    public function getSelectOptions($excludeCategory = null)
    {
        $this->buildCategoriesList();
        
        if($this->_selectOptions == null)
            $this->buildSelectOptionsRecursively($excludeCategory);

        return $this->_selectOptions;
    }

    protected function buildSelectOptionsRecursively($excludeCategory, $rootCategory = null, $indent = '')
    {
        foreach($this->_categoriesList[$rootCategory] as $categoryId => $category)
        {
            if($categoryId != $excludeCategory)
            {
                $this->_selectOptions[$categoryId] = $indent.$category['Nome'];
                if(isset($this->_categoriesList[$categoryId]))
                    $this->buildSelectOptionsRecursively($excludeCategory, $categoryId, $indent.'-');
            }
        }
    }

    public function addCategoriesToSelectButton($select)
    {
        foreach($this->getCategoriesList() as $fatherId => $children)
        {
            foreach($children as $categoryId => $category)
            {
                $select->addSubOption($fatherId, $categoryId, $category['Nome']);
            }
        }
    }
}
?>
