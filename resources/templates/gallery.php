<main class="sidebar">

    <div class="aside-wrapper">
        <aside id="no-ajax">
            <div class="title-wrapper stickable">
                <div class="title">Галерия</div>
            </div>
            <ol class="images">
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

        </aside>
        <div class="aside-toggler mobile-only"><span>Галерия</span></div>
    </div>

    <section class="text" id="container">
        <div class="content-wrapper">
            <h1 class="stickable center" id="title">Снимки на Йосиф Петров</h1>

            <div class="gallery-wrapper-outer">

                <div class="swipe-nav prev" title="Предишна"> </div>

                <div class="gallery-wrapper-inner">
                    <div id="swipe-gallery">
                        <div class="swipe-wrap">
                            <?php
                            $i = 0;
                            foreach ($images as $imageItem) {
                                $item  = $imageItem->getImageDetails();
                                $image = $item['image']['image'];
                                echo '<div><img src="'.$image.'" alt="Изображение #' . $i . '" /><span>' . nl2br($item['caption']) . '</span></div>'."\n";
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