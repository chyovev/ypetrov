<!DOCTYPE html>
<html lang="bg">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?= isset($metaTitle) ? escape($metaTitle) . META_SUFFIX : 'Официален сайт в памет на поета Йосиф Петров (1909 – 2004)' ?></title>
    <meta property="og:title" content="<?= isset($metaTitle) ? escape($metaTitle) : 'Официален сайт в памет на поета Йосиф Петров (1909 – 2004)' ?>" />
    <meta name="description" content="<?= isset($metaDesc) ? truncate($metaDesc, 250) : 'Йосиф Петров е български поет, общественик и политик'; ?>" />
    <meta property="og:description" content="<?= isset($metaDesc) ? truncate($metaDesc, 250) : 'Йосиф Петров е български поет, общественик и политик'; ?>" />
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
    <script>

    </script>
</head>
<body>
    <header>
        <div class="nav-wrapper-outer">
            <div class="nav-wrapper-inner">

                <div class="logo-wrapper">
                    <a href="<?= WEBROOT ?>" class="logo">Йосиф Петров</a>
                    <span>(1909 – 2004)</span>
                </div>

                <div class="nav-toggler-wrapper mobile-only"><span id="nav-toggler"></span></div>

                <nav>
                    <?php
                        $navigation  = getGlobalNavigation();
                        $currentPage = getCurrentNavPage();
                    ?>
                    <ul>
                        <?php if (isset($navigation['books']) && count($navigation['books']) > 0) { ?>
                        <li class="has-items<?= (isset($currentPage['fileName']) && $currentPage['fileName'] == 'poem.php' ? ' active open' : '') ?>">
                            <a href="javascript: void(0);">Творчество</a>
                            <ul>
                                <?php
                                foreach ($navigation['books'] as $item) {
                                    $bookItem = $item->getBookDetails();
                                    $class    = (isset($currentPage['slug']) && $currentPage['slug'] === $bookItem['slug']) ? ' class="active"' : '';
                                    echo '<li><a href="' . Url::generateBookUrl($bookItem['slug']) . '"' . $class . '>' . escape($bookItem['title']) .' (' . $bookItem['published_year'] . ')</a></li>'."\n";
                                }
                                ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <li><a href="#">Галерия</a></li>
                        <?php if (isset($navigation['videos']) && count($navigation['videos']) > 0) { ?>
                        <li class="has-items<?= (isset($currentPage['fileName']) && $currentPage['fileName'] == 'video.php' ? ' active open' : '') ?>">
                            <a href="javascript: void(0);">Видео</a>
                            <ul>
                                <?php
                                foreach ($navigation['videos'] as $item) {
                                    $videoItem = $item->getVideoDetails();
                                    $class     = (isset($currentPage['slug']) && $currentPage['slug'] === $videoItem['slug']) ? ' class="active"' : '';
                                    echo '<li><a href="' . Url::generateVideoUrl($videoItem['slug']) . '"' . $class . '>' . escape($videoItem['title']) .'</a></li>'."\n";
                                }
                                ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <li><a href="#">Интервюта</a></li>
                        <li><a href="#">Статии и спомени</a></li>
                        <li><a href="#">Контакт</a></li>
                    </ul>
                    <div class="search-form">
                        <form action="#">
                            <input type="text" class="search-field" name="s" placeholder="Търси..." />
                            <button type="submit" class="search-submit" title="Търсене"></button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <div class="header-image"> </div>
    </header>