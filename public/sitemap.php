<?php
require_once('../resources/autoload.php');
header ("Content-Type:text/xml");

$navigation        = getGlobalNavigation();
$contentRepository = $entityManager->getRepository('BookContent');
$booksPoems        = $contentRepository->getActiveBooksAndPoemsSlugs();
?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <!-- Home -->
    <url>
        <loc><?= WEBROOT_FULL ?></loc>
        <changefreq>monthly</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Books & Poems -->
    <?php
    foreach ($booksPoems as $bookId => $group) {
        $bookSlug = $navigation['books'][$bookId]->getSlug();
        echo   '<url>
                    <loc>' . HOST_URL . Url::generateBookUrl($bookSlug) . '</loc>
                    <changefreq>yearly</changefreq>
                    <priority>0.6</priority>
                </url>';
        foreach ($group as $item) {
            echo   '<url>
                        <loc>' . HOST_URL . Url::generatePoemUrl($item['book_slug'], $item['poem_slug']) . '</loc>
                        <changefreq>yearly</changefreq>
                        <priority>0.5</priority>
                    </url>';            
        }
    }
    ?>

    <!-- Gallery -->
    <url>
        <loc><?= HOST_URL . Url::generateGalleryUrl() ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    <!-- Press Articles -->
    <?php
    foreach ($navigation['articles'] as $entity) {
        $slug = $entity->getSlug();
        echo   '<url>
                    <loc>' . HOST_URL . Url::generatePressUrl($slug) . '</loc>
                    <changefreq>yearly</changefreq>
                    <priority>0.3</priority>
                </url>';
    }
    ?>

    <!-- About the Poet -->
    <?php
    foreach ($navigation['essays'] as $entity) {
        $slug = $entity->getSlug();
        echo   '<url>
                    <loc>' . HOST_URL . Url::generateEssayUrl($slug) . '</loc>
                    <changefreq>yearly</changefreq>
                    <priority>0.4</priority>
                </url>';
    }
    ?>

    <!-- Contact -->
    <url>
        <loc><?= HOST_URL . Url::generateContactUrl() ?></loc>
        <changefreq>yearly</changefreq>
        <priority>0.6</priority>
    </url>

</urlset>