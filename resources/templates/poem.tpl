<main class="sidebar">

    <div class="aside-wrapper">
        <aside>
            <div class="title-wrapper stickable">
                <div class="title">
                    <a href="{Url::generateBookUrl($book['slug'])}"{if !$poem} class="active"{/if}>«{$book['title']}»</a>
                    <div class="filter">
                        <div class="filter-inner-wrapper">
                            <input type="text" id="filter-field" placeholder="Филтър" />
                            <button type="button" class="show" title="Покажи/скрий филтър">Филтър</button>
                        </div>
                        <button type="button" class="clear none" title="Изтрий">Изтрий</button>
                    </div>
                </div>
            </div>
            <ol>
                {foreach $book['contents'] as $poemSlug => $contentItem}
                    {$item  = $contentItem->getPoem()->getDetails()}
                    <li><a href="{Url::generatePoemUrl($book['slug'], $poemSlug)}"{if $poem && $poemSlug === $poem['slug']} class="active"{/if} title="{escape($item['title'])}" data-dedication="{escape($item['dedication'])}">{escape($item['title'])}</a></li>
                {/foreach}
            </ol>
        </aside>
        <div class="aside-toggler mobile-only"><span>Съдържание</span></div>
    </div>
    {* if there's a poem object, use it *}
    {if $poem}
        {$dedication = $poem['dedication']}
        <section class="text{if $poem['use_monospace_font']} monospace{/if}" id="container">
            <div class="content-wrapper">
                <div class="poem-wrapper">
                    <h1 class="stickable" id="title">{$poem['title']}</h1>
                    <div id="dedication"{if !$dedication} style="display: none"{/if}>{nl2br($dedication)}</div>
                    <div id="body">{$poem['body']}</div>
                </div>

                {include 'elements/comment-section.tpl'}

            </div>
        </section>

    {* otherwise show information about the book *}
    {else}
        <section class="text" id="container">
            <div class="content-wrapper">
                <div class="poem-wrapper">
                    <h1 class="stickable" id="title">{$book['title']}</h1>

                    {include 'elements/book-details.tpl'}

                </div>

                {include 'elements/comment-section.tpl'}
            </div>
        </section>
    {/if}
</main>
