<main>

    <section class="text textpage" id="container">
        <div class="content-wrapper">
            <?php
                echo '<h1>' . escape($article['title']) . '</h1>';
                echo '<hr />';
                echo $article['body'];
                
                include 'elements/comment-section.php';
            ?>
        </div>
    </section>

</main>