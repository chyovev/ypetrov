<main class="sidebar">

    <div class="aside-wrapper">
        <aside id="no-ajax">
            <div class="title-wrapper">
                <div class="title">Галерия</div>
            </div>
            <ol class="images op-0-fadein">
            {foreach $images as $imageItem}
                {$item  = $imageItem->getImageDetails()}
                {$image = $item['image']['thumb']}
                <li>
                    <a href="javascript: void(0);" class="thumb{if $imageItem@first} active{/if}" id="thumb-{$imageItem@index}">
                        <img src="{$image}" alt="Изображение #{$imageItem@iteration}" />
                    </a>
                </li>
            {/foreach}
            </ol>

            {if $images|@count > 1}
            <div class="center op-0-fadein"><span id="current-image">1</span>/{$images|@count}</div>
            {/if}

        </aside>
        <div class="aside-toggler mobile-only"><span>Галерия</span></div>
    </div>

    <section class="text{if !$images} error{/if}" id="container">
        <div class="content-wrapper">
            {if $images}
            <h1 class="center" id="title">Снимки на Йосиф Петров</h1>

            <div class="gallery-wrapper-outer">

                <div class="swipe-nav prev" title="Предишна"> </div>

                <div class="gallery-wrapper-inner">
                    <div id="swipe-gallery">
                        <div class="swipe-wrap op-0-fadein">
                            {foreach $images as $imageItem}
                                {$item  = $imageItem->getImageDetails()}
                                {$image = $item['image']['image']}
                                <div><img {if !$imageItem@first} data-{/if}src="{$image}" alt="Изображение #{$imageItem@iteration}" title="{escape($item['caption'])}" /><span>{nl2br($item['caption'])}</span></div>
                            {/foreach}
                            ?>
                        </div>
                    </div>
                </div>

                <div class="swipe-nav next" title="Следваща"> </div>
            {else}
                <div class="title">Галерия</div><br />
                <p>В момента няма добавени снимки в галерията.</p>
            {/if}
            </div>
        </div>
    </section>
</main>