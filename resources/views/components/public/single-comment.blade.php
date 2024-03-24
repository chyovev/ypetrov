<div class="comment">
    <div class="title">
        <div class="username">#<span class="counter">{{ $counter ?? 0 }}</span> {{ $comment->name }}</div>
        <div class="date">
            <span class="desktop-only">{{ $comment->created_at->translatedFormat('l, d.m.Y г., H:i ч.') }}</span>
            <span class="mobile-only">{{ $comment->created_at->translatedFormat('d.m.Y г.') }}</span>
        </div>
    </div>
    <div class="body">{!! nl2br(e($comment->message)) !!}</div>
</div>