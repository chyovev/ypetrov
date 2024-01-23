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

        @if ($videos->count())
        <li @class(['has-items' => true, 'active open' => request()->route()->named('video')])>
            <a href="javascript: void(0);">Видео</a>
            <ul>
                @foreach ($videos as $navVideo)
                    <li><a href="{{ route('video', ['slug' => $navVideo->slug]) }}" @class(['active' => (request()->route()->named('video') && $video->slug === $navVideo->slug)])>{{ $navVideo->title }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif

        @if ($press->count())
        <li @class(['has-items' => true, 'active open' => request()->route()->named('press')])>
            <a href="javascript: void(0);">Преса</a>
            <ul>
                @foreach ($press as $navArticle)
                    <li><a href="{{ route('press', ['slug' => $navArticle->slug]) }}" @class(['active' => (request()->route()->named('press') && $article->slug === $navArticle->slug)])>{{ $navArticle->title }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif

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

        <li @class(['active' => request()->route()->named('chrestomathy')])><a href="{{ route('chrestomathy') }}">Христоматия</a></li>

        <li @class(['active' => request()->route()->named('contact')])><a href="{{ route('contact') }}">Контакт</a></li>

    </ul>
</nav>