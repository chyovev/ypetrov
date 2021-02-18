<main>

    <section class="text textpage" id="container">
        <div class="content-wrapper">
            <?php
                echo '<h1>' . escape($title) . '</h1>';
                if (isset($subtitle) && $subtitle) {
                    echo '<div class="subtitle">' . $subtitle . '</div>';
                }
                echo '<hr />';
                echo $body;

                if (isset($commentUrl)) {
                    include 'elements/comment-section.php';
                }
            ?>
        </div>
    </section>

</main>