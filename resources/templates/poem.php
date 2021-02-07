<main class="sidebar">

    <div class="aside-wrapper">
        <aside>
            <div class="title-wrapper stickable">
                <?php
                $class = !$poem ? ' active' : '';
                echo '<a href="' . Url::generateBookUrl($book['slug']) . '" class="title'.$class.'">«' . $book['title'] . '»</a>';
                ?>
            </div>
            <ol>
                <?php
                foreach ($book['contents'] as $poemSlug => $contentItem) {
                    $item  = $contentItem->getPoem()->getPoemDetails();
                    $class = ($poem && $poemSlug === $poem['slug']) ? ' class="active"' : '';
                    echo '<li><a href="' . Url::generatePoemUrl($book['slug'], $poemSlug) . '"'.$class.' title="' . escape($item['title']) . '">' . escape($item['title']) . '</a></li>';
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
                <div class="book-poem-wrapper">
                    <h1 class="stickable" id="title">' . $poem['title'] . '</h1>
                    <div id="dedication"' . ( ! $dedication ? ' style="display: none"' : '') . '>' . $dedication . '</div>
                    <div id="body">' . $poem['body'] . '</div>
                </div>
            </section>';

}

// otherwise show information about the book
else {
    echo   '<section class="text" id="container">
                <div class="book-poem-wrapper">
                <h1 class="stickable" id="title">' . $book['title'] . '</h1>';

        include 'book-details.php';

    echo   '    </div>
            </section>';
}
?>
</main>

<div class="desktop-only none" id="scroll-top"></div>