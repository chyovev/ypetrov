<div class="nav-toggler-wrapper mobile-only"><span id="nav-toggler"></span></div>

<nav>
    <ul>

        @if ($books->count())
        <li @class(['has-items' => true, 'active open' => request()->route()->named('book', 'poem')])>
            <a href="javascript: void(0);">Творчество</a>
            <ul>
                @foreach ($books as $navBook)
                    <li><a href="{{ route('book', ['bookSlug' => $navBook->slug]) }}" @class(['active' => (request()->route()->named('book', 'poem') && $book->slug === $navBook->slug)])>{{ $navBook->title }} ({{ $navBook->publish_year }})</a></li>
                @endforeach
            </ul>
        </li>
        @endif

        <li @class(['active' => request()->route()->named('gallery')])><a href="{{ route('gallery') }}">Галерия</a></li>

        @if ($essays->count())
        <li @class(['has-items' => true, 'active open' => request()->route()->named('essay')])>
            <a href="javascript: void(0);">За Йосиф Петров</a>
            <ul>
                @foreach ($essays as $navEssay)
                    <li><a href="{{ route('essay', ['slug' => $navEssay->slug]) }}" @class(['active' => (request()->route()->named('essay') && $essay->slug === $navEssay->slug)])>{{ $navEssay->title }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif

    </ul>
</nav>