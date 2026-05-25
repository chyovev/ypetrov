@if (isset($breadcrumbs))
<ol class="breadcrumb">
    @foreach ($breadcrumbs as $breadcrumb)

        @if ($breadcrumb->getUrl() && !$loop->last)
            <li class="breadcrumb-item"><a href="{{ $breadcrumb->getUrl() }}">{{ $breadcrumb->getTitle() }}</a></li>
        @else
            <li class="breadcrumb-item active">{{ $breadcrumb->getTitle() }}</li>
        @endif

    @endforeach
</ol>
@endif