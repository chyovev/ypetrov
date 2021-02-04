<?php

///////////////////////////////////////////////////////////////////////////////
function renderLayoutWithContentFile($contentFile, $variables = []) {
    $contentFileFullPath = TEMPLATES_PATH . '/' . $contentFile;
 
    // convert the $variables array into single variables
    extract($variables);
 
    require_once(LAYOUTS_PATH . '/header.php');
 
    if (file_exists($contentFileFullPath)) {
        require_once($contentFileFullPath);
    }
    else {
        header('HTTP/1.1 404 Not Found'); 
        require_once(LAYOUTS_PATH . '/error404.php');
    }
 
    require_once(LAYOUTS_PATH . '/footer.php');
}