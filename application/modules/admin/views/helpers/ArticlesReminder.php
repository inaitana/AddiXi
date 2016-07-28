<?php
class Admin_View_Helper_articlesReminder extends Zend_View_Helper_Abstract
{
    protected $_view;

    public function setView(Zend_View_Interface $view)
    {
        $this->_view = $view;
    }

    public function articlesReminder()
    {
        $articlesModel = new Admin_Model_Articoli();
        $categoriesModel = new Admin_Model_Categorie();

        $javascript = "function AssociaTooltip(elemento)
            {
                $('#promemoria-articoli-'+elemento+'-numero').xTooltip({clickToOpen: true, customHtml: $('#promemoria-articoli-'+elemento+'-lista').html(), tooltipHover: true, tooltipClass: 'promemoria-tooltip', addClass: 'ui-widget-content ui-corner-all ui-helper-reset'});
            };
        ";

        $articlesReminder = "<ul id='promemoria-articoli' class='promemoria'>";
        
        $emptyCategories = $categoriesModel->getEmptyCategories();
        if(count($emptyCategories) > 0)
        {
            $articlesReminder .= "<li id='promemoria-articoli-categorieVuote'>";
            $articlesReminder .= "Sono presenti <span id='promemoria-articoli-categorieVuote-numero' class='promemoria-numero'>".count($emptyCategories)."</span> categorie che non contengono nessun articolo.";
            $articlesReminder .= "<div id='promemoria-articoli-categorieVuote-lista' style='display:none'><ul class='promemoria-lista'>";
            foreach($emptyCategories as $category)
                $articlesReminder .= "<li><a href='".htmlentities($this->_view->url(array('module' => 'admin', 'controller' => 'articoli'))."#/?Categoria=".$category['idCategoria']."&action=List")."'>".$category['Nome']."</a></li>";
            $articlesReminder .= "</ul></div>";
            $articlesReminder .= "</li>";
            $javascript .= "AssociaTooltip('categorieVuote');";
        }

        $descriptionlessCategories = $categoriesModel->getDescriptionLessCategories();
        if(count($descriptionlessCategories) > 0)
        {
            $articlesReminder .= "<li id='promemoria-articoli-categorieNoDesc'>";
            $articlesReminder .= "Sono presenti <span id='promemoria-articoli-categorieNoDesc-numero' class='promemoria-numero'>".count($descriptionlessCategories)."</span> categorie senza descrizione.";
            $articlesReminder .= "<div id='promemoria-articoli-categorieNoDesc-lista' style='display:none'><ul class='promemoria-lista'>";
            foreach($descriptionlessCategories as $category)
                $articlesReminder .= "<li><a href='".htmlentities($this->_view->url(array('module' => 'admin', 'controller' => 'articoli'))."#/?GestisciCategoria=".$category['idCategoria']."&action=Edit-Categorie")."'>".$category['Nome']."</a></li>";
            $articlesReminder .= "</ul></div>";
            $articlesReminder .= "</li>";
            $javascript .= "AssociaTooltip('categorieNoDesc');";
        }

        $pricelessArticles = $articlesModel->getPricelessArticles();
        if(count($pricelessArticles) > 0)
        {
            $articlesReminder .= "<li id='promemoria-articoli-senzaPrezzo'>";
            $articlesReminder .= "Sono presenti <span id='promemoria-articoli-senzaPrezzo-numero' class='promemoria-numero'>".count($pricelessArticles)."</span> articoli che non hanno un prezzo specificato.";
            $articlesReminder .= "<div id='promemoria-articoli-senzaPrezzo-lista' style='display:none'><ul class='promemoria-lista'>";
            foreach($pricelessArticles as $article)
                $articlesReminder .= "<li><a href='".htmlentities($this->_view->url(array('module' => 'admin', 'controller' => 'articoli'))."#/?Articolo=".$article['idArticoloSpecifico']."&Categoria=".$article['idCategoria']."&action=Edit")."'>".$article['Marca'].' '.$article['Nome']."</a></li>";
            $articlesReminder .= "</ul></div>";
            $articlesReminder .= "</li>";
            $javascript .= "AssociaTooltip('senzaPrezzo');";
        }

        $soldoutArticles = $articlesModel->getSoldoutArticles();
        if(count($soldoutArticles) > 0)
        {
            $articlesReminder .= "<li id='promemoria-articoli-esauriti'>";
            $articlesReminder .= "Sono presenti <span id='promemoria-articoli-esauriti-numero' class='promemoria-numero'>".count($soldoutArticles)."</span> articoli esauriti.";
            $articlesReminder .= "<div id='promemoria-articoli-esauriti-lista' style='display:none'><ul class='promemoria-lista'>";
            foreach($soldoutArticles as $article)
                $articlesReminder .= "<li><a href='".htmlentities($this->_view->url(array('module' => 'admin', 'controller' => 'articoli'))."#/?Articolo=".$article['idArticoloSpecifico']."&Categoria=".$article['idCategoria']."&action=Edit")."'>".$article['Marca'].' '.$article['Nome']."</a></li>";
            $articlesReminder .= "</ul></div>";
            $articlesReminder .= "</li>";
            $javascript .= "AssociaTooltip('esauriti');";
        }

        $descriptionlessArticles = $articlesModel->getDescriptionLessArticles();
        if(count($descriptionlessArticles) > 0)
        {
            $articlesReminder .= "<li id='promemoria-articoli-articoliNoDesc'>";
            $articlesReminder .= "Sono presenti <span id='promemoria-articoli-articoliNoDesc-numero' class='promemoria-numero'>".count($descriptionlessArticles)."</span> articoli senza descrizione.";
            $articlesReminder .= "<div id='promemoria-articoli-articoliNoDesc-lista' style='display:none'><ul class='promemoria-lista'>";
            foreach($descriptionlessArticles as $article)
                $articlesReminder .= "<li><a href='".htmlentities($this->_view->url(array('module' => 'admin', 'controller' => 'articoli'))."#/?Articolo=".$article['idArticoloSpecifico']."&Categoria=".$article['idCategoria']."&action=Edit")."'>".$article['Marca'].' '.$article['Nome']."</a></li>";
            $articlesReminder .= "</ul></div>";
            $articlesReminder .= "</li>";
            $javascript .= "AssociaTooltip('articoliNoDesc');";
        }
        
        $articlesReminder .= "</ul>";
        $articlesReminder .= "<script type='text/javascript'>".$javascript."</script>";
        return $articlesReminder;
    }
}
?>