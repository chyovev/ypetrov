<?php
$currentPage = getCurrentNavPage();
$searchStr   = getGetRequestVar('s');
?>
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?= isset($metaTitle) ? escape($metaTitle) . META_SUFFIX : 'Официален сайт в памет на поета Йосиф Петров (1909 – 2004)' ?></title>
    <meta property="og:title" content="<?= isset($metaTitle) ? escape($metaTitle) : 'Официален сайт в памет на поета Йосиф Петров (1909 – 2004)' ?>" />
    <meta name="description" content="<?= isset($metaDesc) ? truncateString($metaDesc, 250) : 'Йосиф Петров е български поет, общественик и политик'; ?>" />
    <meta property="og:description" content="<?= isset($metaDesc) ? truncateString($metaDesc, 250) : 'Йосиф Петров е български поет, общественик и политик'; ?>" />
    <meta name="keywods" content="Йосиф Петров, поезия, поет, общественик, депутат, стихосбирки, стихотворения, VII Велико Народно събрание, Персин" />
    <script type="text/javascript" src="<?= WEBROOT ?>resources/js/script.js"></script>
    <link type="text/css" rel="stylesheet" href="<?= WEBROOT ?>resources/css/style.css" />
    <?php
    if (isset($metaImage) && is_array($metaImage) && count(array_filter($metaImage)) > 1) {
        echo   '<meta property="og:image:width" content="' . $metaImage['size']['width'] . '" />
                <meta property="og:image:height" content="' . $metaImage['size']['height'] . '" />
                <meta property="og:image" content="' . HOST_URL . $metaImage['url'] . '" />'."\n";
    }
    ?>
    <meta property="og:image:width" content="768" />
    <meta property="og:image:height" content="1024" />
    <meta property="og:image" content="<?= HOST_URL . IMG_LAYOUT . '/og-image.jpg' ?>" />
    <?php
    if (isset($mainVideo)) {
        echo '<meta property="og:video" content="' . HOST_URL . $mainVideo['video']['mp4'] . '" />
              <meta property="og:video:type" content="video/mp4" />
              <meta property="og:video" content="' . HOST_URL . $mainVideo['video']['webm'] . '" />
              <meta property="og:video:type" content="video/webm" />'."\n";
    }
    if (isset($canonical)) {
        echo '<link rel="canonical" href="' . $canonical . '" />';
    }
    ?>
    <link rel="apple-touch-icon" sizes="120x120" href="<?= IMG_LAYOUT ?>/favicon/apple-touch-icon.png" />
    <link rel="apple-touch-icon-precomposed" type="image/png" href="<?= IMG_LAYOUT ?>/favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="<?= IMG_LAYOUT ?>/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="<?= IMG_LAYOUT ?>/favicon/favicon-16x16.png" />
    <link rel="shortcut icon" href="<?= IMG_LAYOUT ?>/favicon/favicon.ico" />
    <link rel="manifest" href="<?= IMG_LAYOUT ?>/favicon/site.webmanifest" />
    <link rel="mask-icon" href="<?= IMG_LAYOUT ?>/favicon/favicon.png" color="#F6F6F6" />
    <meta name="msapplication-TileColor" content="#F6F6F6" />
    <meta name="theme-color" content="#F6F6F6" />
    <?php if (isset($currentPage['noindex']) && $currentPage['noindex']) {
        echo '<meta name="robots" content="noindex" />'."\n";
    } ?>
</head>
<body>
    <header>
        <div class="nav-wrapper-outer">
            <div class="nav-wrapper-inner">

                <div class="logo-wrapper">
                    <a href="<?= WEBROOT ?>" class="logo">Йосиф Петров</a>
                    <span<?= ( isset($searchStr) ? ' style="display: none"' : '') ?>>(1909 – 2004)</span>
                </div>

                <div class="nav-toggler-wrapper mobile-only"><span id="nav-toggler"></span></div>

                <nav>
                    <?php
                        $navigation  = getGlobalNavigation();
                    ?>
                    <ul>

                        <?php if (isset($navigation['books']) && count($navigation['books']) > 0) { ?>
                        <li class="has-items<?= (isCurrentPageFile('poem.php') ? ' active open' : '') ?>">
                            <a href="javascript: void(0);">Творчество</a>
                            <ul>
                                <?php
                                foreach ($navigation['books'] as $entity) {
                                    $item  = $entity->getDetails();
                                    $class = isCurrentPageSlug($item['slug']) ? ' class="active"' : '';
                                    echo '<li><a href="' . Url::generateBookUrl($item['slug']) . '"' . $class . '>' . escape($item['title']) .' (' . $item['published_year'] . ')</a></li>'."\n";
                                }
                                ?>
                            </ul>
                        </li>
                        <?php } ?>

                        <li<?= (isCurrentPageFile('gallery.php') ? ' class="active"' : '') ?>><a href="<?= Url::generateGalleryUrl() ?>">Галерия</a></li>

                        <?php if (isset($navigation['videos']) && count($navigation['videos']) > 0) { ?>
                        <li class="has-items<?= (isCurrentPageFile('video.php') ? ' active open' : '') ?>">
                            <a href="javascript: void(0);">Видео</a>
                            <ul>
                                <?php
                                foreach ($navigation['videos'] as $entity) {
                                    $item  = $entity->getDetails();
                                    $class = isCurrentPageSlug($item['slug']) ? ' class="active"' : '';
                                    echo '<li><a href="' . Url::generateVideoUrl($item['slug']) . '"' . $class . '>' . escape($item['title']) .'</a></li>'."\n";
                                }
                                ?>
                            </ul>
                        </li>
                        <?php } ?>

                        <?php if (isset($navigation['articles']) && count($navigation['articles']) > 0) { ?>
                        <li class="has-items<?= (isCurrentPageFile('press.php') ? ' active open' : '') ?>">
                            <a href="javascript: void(0);">Преса</a>
                            <ul>
                                <?php
                                foreach ($navigation['articles'] as $entity) {
                                    $item  = $entity->getDetails();
                                    $class = isCurrentPageSlug($item['slug']) ? ' class="active"' : '';
                                    echo '<li><a href="' . Url::generatePressUrl($item['slug']) . '"' . $class . '>' . ($item['short_title'] ?? $item['title']) .'</a></li>'."\n";
                                }
                                ?>
                            </ul>
                        <?php } ?>

                        <?php if (isset($navigation['essays']) && count($navigation['essays']) > 0) { ?>
                        <li class="has-items<?= (isCurrentPageFile('essays.php') ? ' active open' : '') ?>">
                            <a href="javascript: void(0);">За Йосиф Петров</a>
                            <ul>
                                <?php
                                foreach ($navigation['essays'] as $entity) {
                                    $item  = $entity->getDetails();
                                    $class = isCurrentPageSlug($item['slug']) ? ' class="active"' : '';
                                    echo '<li><a href="' . Url::generateEssayUrl($item['slug']) . '"' . $class . '>' . $item['title'] .'</a></li>'."\n";
                                }
                                ?>
                            </ul>
                        </li>
                        <?php } ?>

                        <li<?= (isCurrentPageFile('contact.php') ? ' class="active"' : '') ?>><a href="<?= Url::generateContactUrl() ?>">Контакт</a></li>
                    </ul>
                    <div class="search-form">
                        <form action="<?= Url::generateSearchUrl() ?>" method="GET">
                            <input type="text" class="search-field" name="s" placeholder="Търси..."<?= ( $searchStr != NULL ? ' value="' . htmlspecialchars($searchStr) . '"' : '') ?> />
                            <button type="submit" class="search-submit" title="Търсене"></button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <div class="header-image"> </div>
    </header>