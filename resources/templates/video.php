<main class="sidebar">

    <div class="aside-wrapper">
        <aside id="no-ajax">
            <div class="title-wrapper stickable">
                <div class="title">Видео</div>
            </div>
            <ol class="videos">
            <?php
            $navigation = getGlobalNavigation();
            $allVideos  = $navigation['videos'];

            foreach ($allVideos as $videoItem) {
                $item  = $videoItem->getVideoDetails();
                $image = $item['video']['jpg'];
                $class = ($mainVideo['slug'] === $item['slug']) ? ' class="active"' : '';
                echo   '<li>
                            <a href="' . Url::generateVideoUrl($item['slug']) .'"' . $class . '>
                                <span>' . $item['title'] . '</span>
                                <img src="'.$image.'" alt="' . escape($item['title']) . '" />
                            </a>
                        </li>';
            }
            ?>
            </ol>

        </aside>
        <div class="aside-toggler mobile-only"><span>Още видеа</span></div>
    </div>

    <section class="text monospace" id="container">
        <div class="content-wrapper">
            <h1 class="stickable center" id="title"><?= $mainVideo['title']; ?></h1>
            <?php
            if ($mainVideo['summary']) {
                echo '<div id="summary">' . nl2br(trim($mainVideo['summary'])) .'</div>';
            }
            ?>
            <div class="video">
                <video preload="metadata" controls="controls" poster="<?= $mainVideo['video']['jpg'] ?>">
                    <source src="<?= $mainVideo['video']['mp4'] ?>" type="video/mp4">
                    <source src="<?= $mainVideo['video']['webm'] ?>" type="video/webm">
                    <div>
                        Вашият браузър не поддържа вграждане на видео.<br />
                        Можете да свалите видеото <a href="<?= $mainVideo['video']['mp4'] ?>">оттук</a>.
                    </div>
                </video>
            </div>
        </div>
    </section>
</main>
