<?php if ( ! isset($comments)) { $comments = []; } ?>
<div class="comments-wrapper">
    <div class="comments"<?= ( ! count($comments) ? ' style="display: none"' : '') ?>>
        <div class="section-title">Коментари</div>
        <?php
        $i = 1;
        foreach ($comments as $item) {
            $comment = $item->getCommentDetails();
            include 'single-comment.php';
            $i++;
        }
        ?>
    </div>

    <div class="comment-form">
        <div class="section-title">Добавяне на коментар</div>
        <form method="POST" id="comment-form" action="<?= $commentUrl ?>">
            <input type="text" id="username" name="username" placeholder="Подател" />
            <textarea name="comment" id="comment" placeholder="Коментар"></textarea>
            <div class="error-message none"></div>
            <input type="submit" value="Изпрати" />
        </form>
    </div>
</div>