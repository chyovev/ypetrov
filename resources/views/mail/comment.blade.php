<x-mail::message>
    <div style="text-align: center"><strong>Нов коментар</strong></div><br />
    <strong>Ресурс</strong>: {{ strip_tags($comment->getCommentableTitle()) }}<br />
    <strong>Линк към CMS</strong>: <a href="{{ $comment->commentable->getCMSUrl() }}" target="_blank">{{ $comment->commentable->getCMSUrl() }}</a><br />
    <strong>Дата</strong>: {{ $comment->created_at->translatedFormat('l, d.m.Y г., H:i ч.') }}<br />
    <br />
    <strong>От</strong>: {{ $comment->name }}<br />
    <strong>Съобщение</strong>: {!! nl2br(e($comment->message)) !!}
</x-mail::message>
