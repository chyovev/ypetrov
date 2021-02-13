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
];


// set noindex param to true for current page to add respective meta tag in header.php
setCurrentNavPage(basename(__FILE__), NULL, $noindex = true);

renderLayoutWithContentFile('search.php', $vars);