<div class="attachments-preview">
    @foreach ($attachments as $attachment)
        <a href="{{ $attachment->getURL() }}" title="{{ $attachment->original_file_name }}">
            <span class="filename">{{ $attachment->original_file_name }}</span>
        </a>
    @endforeach
</div>