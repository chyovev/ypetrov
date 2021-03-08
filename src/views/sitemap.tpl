<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

    <!-- Home -->
    <url>
        <loc>{WEBROOT_FULL}</loc>
        <changefreq>monthly</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- Books & Poems -->
    {foreach $booksPoems as $bookId => $group}
        {$bookSlug = $navigation['books'][$bookId]->getSlug()}
        <url>
            <loc>{url params=['controller' => 'books', 'action' => 'view', 'book' => $bookSlug] full=true}</loc>
            <changefreq>yearly</changefreq>
            <priority>0.6</priority>
        </url>
        {foreach $group as $item}
            <url>
                <loc>{url params=['controller' => 'poems', 'action' => 'view', 'book' => $item['book_slug'], 'poem' => $item['poem_slug']] full=true}</loc>
                <changefreq>yearly</changefreq>
                <priority>0.5</priority>
            </url>
        {/foreach}
    {/foreach}

    <!-- Gallery -->
    <url>
        <loc>{url params=['controller' => 'gallery', 'action' => 'index'] full=true}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>

    <!-- Press Articles -->
    {foreach $navigation['articles'] as $entity}
        {$slug = $entity->getSlug()}
        <url>
            <loc>{url params=['controller' => 'press', 'action' => 'view', 'press' => $slug] full=true}</loc>
            <changefreq>yearly</changefreq>
            <priority>0.3</priority>
        </url>
    {/foreach}

    <!-- About the Poet -->
    {foreach $navigation['essays'] as $entity}
        {$slug = $entity->getSlug()}
        <url>
            <loc>{url params=['controller' => 'essays', 'action' => 'view', 'essay' => $slug] full=true}</loc>
            <changefreq>yearly</changefreq>
            <priority>0.4</priority>
        </url>
    {/foreach}

    <!-- Contact -->
    <url>
        <loc>{url params=['controller' => 'contact', 'action' => 'index'] full=true}</loc>
        <changefreq>yearly</changefreq>
        <priority>0.6</priority>
    </url>

</urlset>