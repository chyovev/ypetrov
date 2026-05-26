<div class="nav-toggler-wrapper mobile-only"><span id="nav-toggler"></span></div>

<nav>
    <ul>

        @if ($books->count())
        <li @class(['has-items' => true, 'active open' => request()->routeIs('book', 'poem')])>
            <a href="javascript: void(0);">Творчество</a>
            <ul>
                @foreach ($books as $navBook)
                    <li><a href="{{ route('public.book', ['book' => $navBook]) }}" @class(['active' => (request()->routeIs('book', 'poem') && isset($book) && $book->slug === $navBook->slug)])>{{ $navBook->title }} ({{ $navBook->publish_year }})</a></li>
                @endforeach
            </ul>
        </li>
        @endif

        <li @class(['active' => request()->routeIs('gallery')])><a href="{{ route('public.gallery') }}">Галерия</a></li>

        @if ($videos->count())
        <li @class(['has-items' => true, 'active open' => request()->routeIs('video')])>
            <a href="javascript: void(0);">Видео</a>
            <ul>
                @foreach ($videos as $navVideo)
                    <li><a href="{{ route('public.video', ['video' => $navVideo]) }}" @class(['active' => (request()->routeIs('video') && isset($video) && $video->slug === $navVideo->slug)])>{{ $navVideo->title }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif

        @if ($press->count())
        <li @class(['has-items' => true, 'active open' => request()->routeIs('press')])>
            <a href="javascript: void(0);">Преса</a>
            <ul>
                @foreach ($press as $navArticle)
                    <li><a href="{{ route('public.press', ['article' => $navArticle]) }}" @class(['active' => (request()->routeIs('press') && isset($article) && $article->slug === $navArticle->slug)])>{{ $navArticle->title }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif

        @if ($essays->count())
        <li @class(['has-items' => true, 'active open' => request()->routeIs('essay')])>
            <a href="javascript: void(0);">За Йосиф Петров</a>
            <ul>
                @foreach ($essays as $navEssay)
                    <li><a href="{{ route('public.essay', ['essay' => $navEssay]) }}" @class(['active' => (request()->routeIs('essay') && isset($essay) && $essay->slug === $navEssay->slug)])>{{ $navEssay->title }}</a></li>
                @endforeach
            </ul>
        </li>
        @endif

        <li @class(['active' => request()->routeIs('chrestomathy')])><a href="{{ route('public.chrestomathy') }}">Христоматия</a></li>

        <li @class(['active' => request()->routeIs('contact')])><a href="{{ route('public.contact') }}">Контакт</a></li>

    </ul>

    <div class="search-form">
        <form action="{{ route('public.search') }}" method="GET">
            <input type="text" @class(["search-field" => true, "open" => isset($search) && $search != '']) name="s" placeholder="Търси..." value="{{ $search ?? null }}" />
            <button type="submit" class="search-submit" title="Търсене"></button>
        </form>
    </div>
</nav>