<?php
require_once('../resources/autoload.php');

$metaTitle    = 'Търсене';
$searchString = getGetRequestVar('s');

if ($searchString) {
    $metaTitle .= ' за: ' . $searchString;
}

$contentRepository = $entityManager->getRepository('BookContent');
$results           = $contentRepository->searchPoemsAndBooksByString($searchString);
$resultsCount      = $contentRepository->getResultsCount();

$vars = [
    'metaTitle'    => $metaTitle,
    'searchString' => $searchString,
    'resultsCount' => $resultsCount,
    'results'      => $results,
    'noindex'      => true, 
];
$smarty->assign($vars);

renderLayoutWithContentFile('search.tpl');