<main>

    <section class="text textpage" id="container">
        <div class="content-wrapper">
            <h1>{escape($title)}</h1>
            {if isset($subtitle) && $subtitle}<div class="subtitle">{$subtitle}</div>{/if}
            {if ! isset($nohr) || !$nohr}<hr />{/if}
            {$body}

            {if isset($commentUrl)}
                {include 'elements/comment-section.tpl'}
            {/if}
        </div>
    </section>

</main>