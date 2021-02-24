{$searchStr = getGetRequestVar('s')}
<!DOCTYPE html>
<html lang="bg">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>{if isset($metaTitle)}{escape($metaTitle)}{META_SUFFIX}{else}Официален сайт в памет на поета Йосиф Петров (1909 – 2004){/if}</title>
    <meta property="og:title" content="{if isset($metaTitle)}{escape($metaTitle)}{else}Официален сайт в памет на поета Йосиф Петров (1909 – 2004){/if}" />
    <meta name="description" content="{if isset($metaDesc)}{truncateString($metaDesc, 250)}{else}Йосиф Петров е български поет, общественик и политик{/if}" />
    <meta property="og:description" content="{if isset($metaDesc)}{truncateString($metaDesc, 250)}{else}Йосиф Петров е български поет, общественик и политик{/if}" />
    <meta name="keywods" content="Йосиф Петров, поезия, поет, общественик, депутат, стихосбирки, стихотворения, VII Велико Народно събрание, Персин" />
    <script type="text/javascript" src="{WEBROOT}resources/js/script.js?v=20210224"></script>
    <link type="text/css" rel="stylesheet" href="{WEBROOT}resources/css/style.css?v=20210224" />
    {if isset($metaImage) && is_array($metaImage)}
        <meta property="og:image" content="{HOST_URL}{$metaImage['url']}" />
    {/if}
    {if isset($metaImage['size']['width']) && isset($metaImage['size']['height'])}
        <meta property="og:image:width" content="{$metaImage['size']['width']}" />
        <meta property="og:image:height" content="{$metaImage['size']['height']}" />
    {/if}
    <meta property="og:image:width" content="768" />
    <meta property="og:image:height" content="1024" />
    <meta property="og:image" content="{HOST_URL}{IMG_LAYOUT}/og-image.jpg" />

    {if isset($mainVideo)}
        <meta property="og:video" content="{HOST_URL}{$mainVideo['video']['mp4']}" />
        <meta property="og:video:type" content="video/mp4" />
        <meta property="og:video" content="{HOST_URL}{$mainVideo['video']['webm']}" />
        <meta property="og:video:type" content="video/webm" />
    {/if}

    {if isset($canonical)}
        <link rel="canonical" href="{$canonical}" />
    {/if}

    <link rel="apple-touch-icon" sizes="120x120" href="{IMG_LAYOUT}/favicon/apple-touch-icon.png" />
    <link rel="apple-touch-icon-precomposed" type="image/png" href="{IMG_LAYOUT}/favicon/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32" href="{IMG_LAYOUT}/favicon/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16" href="{IMG_LAYOUT}/favicon/favicon-16x16.png" />
    <link rel="shortcut icon" href="{IMG_LAYOUT}/favicon/favicon.ico" />
    <link rel="manifest" href="{IMG_LAYOUT}/favicon/site.webmanifest" />
    <link rel="mask-icon" href="{IMG_LAYOUT}/favicon/favicon.png" color="#F6F6F6" />
    <meta name="msapplication-TileColor" content="#F6F6F6" />
    <meta name="theme-color" content="#F6F6F6" />

    {if isset($noindex) && $noindex}
        <meta name="robots" content="noindex" />
    {/if}

    <script type="text/javascript" language="javascript">
        var root = '{WEBROOT}';
    </script>
    
</head>
<body>
    <header>
        <div class="nav-wrapper-outer">
            <div class="nav-wrapper-inner">

                <div class="logo-wrapper">
                    <a href="{WEBROOT}" class="logo">Йосиф Петров</a>
                    <span{if isset($searchStr) && $searchStr != ''} style="display: none"{/if}>(1909 – 2004)</span>
                </div>

                <div class="nav-toggler-wrapper mobile-only"><span id="nav-toggler"></span></div>

                <nav>
                    <ul>

                        {if isset($navigation['books']) && $navigation['books']|@count > 0}
                        <li class="has-items{if isCurrentPageFile('poem') || isCurrentPageFile('book')} active open{/if}">
                            <a href="javascript: void(0);">Творчество</a>
                            <ul>
                                {foreach $navigation['books'] as $entity}
                                    {$item  = $entity->getDetails()}
                                    <li><a href="{Url::generateBookUrl($item['slug'])}"{if isCurrentPageSlug($item['slug'])} class="active"{/if}>{escape($item['title'])} ({$item['published_year']})</a></li>
                                {/foreach}
                            </ul>
                        </li>
                        {/if}

                        <li{if isCurrentPageFile('gallery')} class="active"{/if}><a href="{Url::generateGalleryUrl()}">Галерия</a></li>

                        {if isset($navigation['videos']) && $navigation['videos']|@count > 0}
                        <li class="has-items{if isCurrentPageFile('video')} active open{/if}">
                            <a href="javascript: void(0);">Видео</a>
                            <ul>
                                {foreach $navigation['videos'] as $entity}
                                    {$item  = $entity->getDetails()}
                                    <li><a href="{Url::generateVideoUrl($item['slug'])}"{if isCurrentPageSlug($item['slug'])} class="active"{/if}>{escape($item['title'])}</a></li>
                                {/foreach}
                            </ul>
                        </li>
                        {/if}

                        {if isset($navigation['articles']) && $navigation['articles']|@count > 0}
                        <li class="has-items{if isCurrentPageFile('press')} active open{/if}">
                            <a href="javascript: void(0);">Преса</a>
                            <ul>
                                {foreach $navigation['articles'] as $entity}
                                    {$item  = $entity->getDetails()}
                                    <li><a href="{Url::generatePressUrl($item['slug'])}"{if isCurrentPageSlug($item['slug'])} class="active"{/if}>{$item['short_title']|default:$item['title']}</a></li>
                                {/foreach}
                            </ul>
                        {/if}

                        {if isset($navigation['essays']) && $navigation['essays']|@count > 0}
                        <li class="has-items{if isCurrentPageFile('essay')} active open{/if}">
                            <a href="javascript: void(0);">За Йосиф Петров</a>
                            <ul>
                                {foreach $navigation['essays'] as $entity}
                                    {$item  = $entity->getDetails()}
                                    <li><a href="{Url::generateEssayUrl($item['slug'])}"{if isCurrentPageSlug($item['slug'])} class="active"{/if}>{$item['short_title']|default:$item['title']}</a></li>
                                {/foreach}
                            </ul>
                        </li>
                        {/if}

                        <li{if isCurrentPageFile('textpage') && isCurrentPageSlug('hristomatiya')} class="active"{/if}><a href="{Url::generateTextPageUrl('hristomatiya')}">Христоматия</a></li>

                        <li{if isCurrentPageFile('contact')} class="active"{/if}><a href="{Url::generateContactUrl()}">Контакт</a></li>
                    </ul>
                    <div class="search-form">
                        <form action="{Url::generateSearchUrl()}" method="GET">
                            <input type="text" class="search-field" name="s" placeholder="Търси..."{if $searchStr != NULL} value="{htmlspecialchars($searchStr)}"{/if} />
                            <button type="submit" class="search-submit" title="Търсене"></button>
                        </form>
                    </div>
                </nav>
            </div>
        </div>

        <div class="header-image"> </div>
    </header>