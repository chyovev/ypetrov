@extends('public.layout.default')

@section('content')

    <main>

        <section class="text textpage" id="container">
            <div class="content-wrapper">

                <div class="search-form-fullpage center">
                    <h1>Търсене на стихотворение</h1>
                    <form action="{{ route('search') }}" method="GET">
                        <input type="text" name="s" value="{{ $search }}" placeholder="Въведете ключова дума" />
                        <input type="submit" value="Търси" />
                    </form>
                </div>

                <div class="results">
                    @if ($search)
                        <div class="info mobile-stickable center">
                            Търсене за: <strong>«{{ $search }}»</strong>.
                            <br />
                            @if ($results->total())
                                Резултати от <strong>{{ $results->firstItem() }}</strong> до <strong>{{ $results->lastItem() }}</strong> (от общо {{ $results->total() }}).
                            @else
                                Резултати: <strong>{{ $results->total() }}</strong>.
                            @endif
                        </div>
                    @endif

                    @foreach ($grouped as $bookId => $poems)
                        @php $book = $books[$bookId]; @endphp

                        <div class="book-result-wrapper">
                            <a href="{{ route('book', $book->slug) }}" class="book-title desktop-stickable">{{ $book->title }} ({{ $book->publish_year }})</a>
                            <div class="poem-result-wrapper">

                            @foreach ($poems as $poem)
                                <div class="poem-result">
                                    <a href="{{ route('poem', ['bookSlug' => $book->slug, 'poemSlug' => $poem->slug]) }}">{!! highlightSubstring(strip_tags($poem->title), $search) !!}</a>
                                    @if ($poem->dedication)
                                        <em>{!! highlightSubstring($poem->dedication, $search) !!}</em>
                                    @endif
                                    <div class="sample">{!! highlightSubstring($poem->showSearchContext($search), $search) !!}</div>
                                </div>
                            @endforeach

                            </div>

                            @if ($book->getCoverImage())
                            <div class="cover-wrapper">
                                <div class="img-wrapper desktop-stickable"><img src="{{ $book->getCoverImage() }}" alt="{{ $book->title }}" /></div>
                            </div>
                            @endif
                            
                        </div>
                    @endforeach

                    {{-- pagination --}}
                    {{ $results->withQueryString()->links() }}
                </div>

            </div>
        </section>

    </main>

@stop