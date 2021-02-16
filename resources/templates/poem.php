<main class="sidebar">

    <div class="aside-wrapper">
        <aside>
            <div class="title-wrapper stickable">
                <div class="title">
                    <?php
                        $class = !$poem ? ' class="active"' : '';
                        echo '<a href="' . Url::generateBookUrl($book['slug']) . '"'.$class.'>«' . $book['title'] . '»</a>';
                    ?>
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
                <?php
                foreach ($book['contents'] as $poemSlug => $contentItem) {
                    $item  = $contentItem->getPoem()->getDetails();
                    $class = ($poem && $poemSlug === $poem['slug']) ? ' class="active"' : '';
                    echo '<li><a href="' . Url::generatePoemUrl($book['slug'], $poemSlug) . '"'.$class.' title="' . escape($item['title']) . '" data-dedication="' . escape($item['dedication']) . '">' . escape($item['title']) . '</a></li>';
                }
                ?>
            </ol>
        </aside>
        <div class="aside-toggler mobile-only"><span>Съдържание</span></div>
    </div>

<?php
// if there's a poem object, use it    
if ($poem) {
    $class      = $poem['use_monospace_font'] ? ' monospace' : '';
    $dedication = $poem['dedication'];

    echo   '<section class="text'.$class.'" id="container">
                <div class="content-wrapper">
                    <div class="poem-wrapper">
                        <h1 class="stickable" id="title">' . $poem['title'] . '</h1>
                        <div id="dedication"' . ( ! $dedication ? ' style="display: none"' : '') . '>' . nl2br($dedication) . '</div>
                        <div id="body">' . $poem['body'] . '</div>
                    </div>';

    include 'elements/comment-section.php';

    echo   '    </div>
            </section>';


}

// otherwise show information about the book
else {
    echo   '<section class="text" id="container">
                <div class="content-wrapper">
                    <div class="poem-wrapper">
                        <h1 class="stickable" id="title">' . $book['title'] . '</h1>';

        include 'elements/book-details.php';

    echo   '        </div>
                </div>
            </section>';
}
?>
</main>
