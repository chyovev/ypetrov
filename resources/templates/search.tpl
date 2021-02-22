<main>

    <section class="text textpage" id="container">
        <div class="content-wrapper">

            <div class="search-form-fullpage center">
                <h1>Търсене на стихотворение</h1>
                <form action="{Url::generateSearchUrl()}" method="GET">
                    <input type="text" name="s" value="{htmlspecialchars($searchString)}" placeholder="Въведете ключова дума" />
                    <input type="submit" value="Търси" />
                </form>
            </div>

            <div class="results">
                {if $searchString}
                    <div class="info mobile-stickable center">
                        Търсене за: <strong>«{htmlspecialchars($searchString)}»</strong>.
                        <br /> Намерени резултати: <strong>{$resultsCount}</strong>
                    </div>
                {/if}

                {$searchWords = explodeWords($searchString)}

                {foreach $results as $bookId => $group}
                    {$bookObject = $navigation['books'][$bookId]}

                    {$book       = $bookObject->getDetails()}
                    {$image      = $book['image']}

                    <div class="book-result-wrapper">
                        <a href="{Url::generateBookUrl($book['slug'])}" class="book-title desktop-stickable">{escape($book['title'])} ({$book['published_year']})</a>
                        <div class="poem-result-wrapper">

                    {foreach $group as $item}
                            <div class="poem-result">
                                <a href="{Url::generatePoemUrl($book['slug'], $item['slug'])}">{outlineElementsInText($searchWords, escape($item['title']))}</a>
                                <div class="sample">{getTextSample($item['body'], $searchString, 35)}</div>
                            </div>
                    {/foreach}

                        </div>
                        <div class="cover-wrapper">
                            <div class="img-wrapper desktop-stickable"><img src="{$image}" alt="{escape($book['title'])}" /></div>
                        </div>
                    </div>
                {/foreach}
            </div>
        </div>
    </section>

</main>