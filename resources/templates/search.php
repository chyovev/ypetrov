<main>

    <section class="text textpage" id="container">
        <div class="content-wrapper">

            <div class="search-form-fullpage center">
                <h1>Търсене на стихотворение</h1>
                <form action="<?= Url::generateSearchUrl() ?>" method="GET">
                    <input type="text" name="s" value="<?= htmlspecialchars($searchString) ?>" />
                    <input type="submit" value="Търси" />
                </form>
            </div>

            <div class="results">
                <?php
                if ($searchString) {
                    echo '<div class="info mobile-stickable center">Търсене за: <strong>«'.htmlspecialchars($searchString).'»</strong>.<br /> Намерени резултати: <strong>' . $resultsCount . '</strong></div>';
                }

                $navigation  = getGlobalNavigation();
                $searchWords = explodeWords($searchString);

                foreach ($results as $bookId => $group) {
                    $bookObject = $navigation['books'][$bookId];

                    $book       = $bookObject->getBookDetails();
                    $image      = $book['image'];

                    echo   '<div class="book-result-wrapper">
                                <a href="'.Url::generateBookUrl($book['slug']).'" class="book-title desktop-stickable">'.escape($book['title']).' ('.$book['published_year'].')</a>
                                    <div class="poem-result-wrapper">';

                    foreach ($group as $item) {
                        echo '      <div class="poem-result">
                                        <a href="' . Url::generatePoemUrl($book['slug'], $item['slug']) . '">' . outlineElementsInText($searchWords, escape($item['title'])) . '</a>
                                        <div class="sample">' . getTextSample($item['body'], $searchString, 30, 200) . '</div>
                                    </div>';
                    }

                    echo   '    </div>
                                <div class="cover-wrapper">
                                    <div class="img-wrapper desktop-stickable"><img src="' . $image . '" alt="' . escape($book['title']) . '" /></div>
                                </div>
                            </div>';
                }
                ?>
            </div>
        </div>
    </section>

</main>