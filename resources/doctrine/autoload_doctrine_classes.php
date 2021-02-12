<?php
// Since the general advice is *NOT* to commit the /vendor folder,
// all doctrine custom files are taken out of their default folders
// which means that the classes need to be loaded manually

function includeAllFilesRecursively($folder, $extension) {
    foreach (glob($folder.'/*') as $item) {
        
        // if the item is a file which matches the extension, include it
        $pattern = '/\.' . $extension . '$/';
        if (preg_match($pattern, $item)) {
            require_once($item);
        }

        // if the item is a folder, call recursively same function for it
        elseif (is_dir($item)) {
            includeAllFilesRecursively($item, $extension);
        }
    }
}

includeAllFilesRecursively(DOCTRINE_PATH . '/src', 'php');
