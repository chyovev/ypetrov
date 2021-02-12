<div class="comment">
    <div class="title">
        <div class="username">#<span class="counter"><?= ($i ?? 0) .'</span> ' . htmlspecialchars($comment['username']) ?></div>
        <div class="date">
            <span class="desktop-only"><?= beautifyDate('%A, %d.%m.%Y г., %H:%M ч.', $comment['created_at']) ?></span>
            <span class="mobile-only"><?= beautifyDate('%d.%m.%Y г.', $comment['created_at']) ?></span>
        </div>
    </div>
    <div class="body"><?= nl2br(htmlspecialchars($comment['body'])) ?></div>
</div>