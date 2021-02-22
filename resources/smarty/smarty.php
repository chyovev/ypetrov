<?php
$smarty = new Smarty();

$smarty->setCacheDir(SMARTY_PATH . '/cache')
       ->setCompileDir(SMARTY_PATH . '/compile')
       ->setConfigDir(SMARTY_PATH . '/configs')
       ->addPluginsDir(SMARTY_PATH . '/plugins')
       ->setTemplateDir(SMARTY_PATH . '/../templates');

// allow caching on production
$smarty->caching = !IS_DEV;