<main class="sidebar">

    <div class="aside-wrapper">
        <aside id="no-ajax">
            <div class="title-wrapper">
                <div class="title">Видео</div>
            </div>
            <ol class="videos">
            {foreach $navigation['videos'] as $videoItem}
                {$item  = $videoItem->getDetails()}
                {$image = $item['video']['jpg']}
                <li>
                    <a href="{url params=['controller' => 'videos', 'action' => 'view', 'video' => $item['slug']]}"{if $mainVideo['slug'] === $item['slug']} class="active"{/if}>
                        <span>{$item['title']}</span>
                        <img src="{$image}" alt="{escape($item['title'])}" />
                    </a>
                </li>
            {/foreach}
            </ol>
        </aside>
        <div class="aside-toggler mobile-only"><span>Още видеа</span></div>
    </div>

    <section class="text monospace" id="container">
        <div class="content-wrapper">
            <h1 class="center" id="title">{$mainVideo['title']}</h1>
            {if $mainVideo['summary']}
                <div id="summary">{nl2br(trim($mainVideo['summary']))}</div>
            {/if}
            <div class="video">
                <video preload="metadata" controls="controls" poster="{$mainVideo['video']['jpg']}">
                    <source src="{$mainVideo['video']['mp4']}" type="video/mp4">
                    <source src="{$mainVideo['video']['webm']}" type="video/webm">
                    <div>
                        Вашият браузър не поддържа вграждане на видео.<br />
                        Можете да свалите видеото <a href="{$mainVideo['video']['mp4']}">оттук</a>.
                    </div>
                </video>
            </div>
            {include 'elements/comment-section.tpl'}
        </div>
    </section>
</main>
