<div class="comment">
    <div class="title">
        <div class="username">#<span class="counter">{$i|default:'0'}</span> {htmlspecialchars($comment['username'])}</div>
        <div class="date">
            <span class="desktop-only">{$comment['created_at']|date_format:'%A, %d.%m.%Y г., %H:%M ч.'}</span>
            <span class="mobile-only">{$comment['created_at']|date_format:'%d.%m.%Y г.'}</span>
        </div>
    </div>
    <div class="body">{nl2br(htmlspecialchars($comment['body']))}</div>
</div>