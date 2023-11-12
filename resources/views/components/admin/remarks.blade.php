@if ($object->attachments_count)
    <em class="fa fa-chain help" title="Object has {{ $object->attachments_count }} attachment(s)"></em>
@endif

@if ($object->comments_count)
    <em class="fa fa-comments-o help" title="Object has {{ $object->comments_count }} comment(s)"></em>
@endif