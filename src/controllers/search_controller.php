<?php
class SearchController extends AppController {

    public $models = ['BookContent'];

    ///////////////////////////////////////////////////////////////////////////
    public function index() {
        $metaTitle    = 'Търсене';
        $searchString = Router::getQueryParam('s');

        if ($searchString) {
            $metaTitle .= ' за: ' . $searchString;
        }

        $results      = $this->BookContent->searchPoemsAndBooksByString($searchString);
        $resultsCount = $this->BookContent->getResultsCount();

        $vars = [
            'metaTitle'    => $metaTitle,
            'searchString' => $searchString,
            'resultsCount' => $resultsCount,
            'results'      => $results,
            'noindex'      => true, 
        ];

        $this->smarty->showPage('search.tpl', $vars);
    }

}
