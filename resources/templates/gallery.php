<main class="sidebar">

    <div class="aside-wrapper">
        <aside id="no-ajax">
            <div class="title-wrapper">
                <div class="title">Галерия</div>
            </div>
            <ol class="images op-0-fadein">
            <?php
            $i = 0;
            foreach ($images as $imageItem) {
                $class = ($i === 0) ? ' active' : '';
                $item  = $imageItem->getImageDetails();
                $image = $item['image']['thumb'];
                echo   '<li>
                            <a href="javascript: void(0);" class="thumb' . $class . '" id="thumb-' . $i . '">
                                <img src="'.$image.'" alt="Изображение #' . $i . '" />
                            </a>
                        </li>';
                $i++;

            }
            ?>
            </ol>
            <div class="center op-0-fadein"><span id="current-image">1</span>/<?= count($images) ?></div>

        </aside>
        <div class="aside-toggler mobile-only"><span>Галерия</span></div>
    </div>

    <section class="text" id="container">
        <div class="content-wrapper">
            <h1 class="center" id="title">Снимки на Йосиф Петров</h1>

            <div class="gallery-wrapper-outer">

                <div class="swipe-nav prev" title="Предишна"> </div>

                <div class="gallery-wrapper-inner">
                    <div id="swipe-gallery">
                        <div class="swipe-wrap op-0-fadein">
                            <?php
                            $i = 0;
                            foreach ($images as $imageItem) {
                                $item  = $imageItem->getImageDetails();
                                $image = $item['image']['image'];
                                $lazy  = ($i > 0) ? 'data-' : ''; // all images after the first one have data-src attr instead of src which shortens loading time
                                echo '<div><img ' . $lazy . 'src="'.$image.'" alt="Изображение #' . $i . '" title="' . escape($item['caption']) . '" /><span>' . nl2br($item['caption']) . '</span></div>'."\n";
                                $i++;
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="swipe-nav next" title="Следваща"> </div>

            </div>
        </div>
    </section>
</main>