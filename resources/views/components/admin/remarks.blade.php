@if ($object->attachments_count)
    <em class="fa fa-chain help" title="{{ __('global.object_has_attachments', ['count' => $object->attachments_count]) }}"></em>
@endif

@if ($object->comments_count)
    <em class="fa fa-comments-o help" title="{{ __('global.object_has_comments', ['count' => $object->comments_count]) }}"></em>
@endif