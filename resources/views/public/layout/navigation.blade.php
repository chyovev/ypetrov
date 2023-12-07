<div class="nav-toggler-wrapper mobile-only"><span id="nav-toggler"></span></div>

<nav>
    <ul>

        @if ($books->count())
        <li class="has-items">
            <a href="javascript: void(0);">Творчество</a>
            <ul>
                @foreach ($books as $book)
                    <li><a href="{{ route('book', ['bookSlug' => $book->slug]) }}">{{ $book->title }} ({{ $book->publish_year }})</a></li>
                @endforeach
            </ul>
        </li>
        @endif

    </ul>
</nav>