<?php
// Since the general advice is *NOT* to commit the /vendor folder,
// all doctrine custom files are taken out of their default folders
// which means that the classes need to be loaded manually

function includeAllFiles($folder, $extension) {
    foreach (glob($folder . '/*.' . $extension) as $filename) {
        require_once($filename);
    }
}

includeAllFiles(DOCTRINE_PATH . '/src',              'php');
includeAllFiles(DOCTRINE_PATH . '/src/Repositories', 'php');