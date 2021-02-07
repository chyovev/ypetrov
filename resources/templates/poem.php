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
    $type  = $poem['use_monospace_font'] ? 'poem'       : 'story';
    $class = $poem['use_monospace_font'] ? ' monospace' : '';
    echo '<section class="text'.$class.'">
              <h1 class="title stickable center">' . $poem['title'] . '</h1>
              ' . ($poem['dedication'] ? '<div class="dedication">' . $poem['dedication'] . '</div>' : '') . '
              <div class="'.$type.'">' . $poem['body'] . '</div>
          </section>';

}

// otherwise show information about the book
else {
    echo '<section class="text center">
              <h1 class="title">' . $book['title'] . '</h1>
              <div class="book">
                  <div class="cover"><img src="'.$book['image'].'" alt="'.escape($book['title']).'" /></div>
                  <div class="info">
                      <div><strong>Година на издаване:</strong> ' . $book['published_year'] . '</div>
                      <div><strong>Стихотворения:</strong> '.count($book['contents']).'</div>
                  </div>
              </div>
          </section>';
}
?>

</main>