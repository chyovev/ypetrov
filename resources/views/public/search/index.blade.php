@extends('public.layout.default')

@section('content')

    <main>

        <section class="text textpage" id="container">
            <div class="content-wrapper">

                <div class="search-form-fullpage center">
                    <h1>Търсене на стихотворение</h1>
                    <form action="{{ route('public.search') }}" method="GET">
                        <input type="text" name="s" value="{{ $search }}" placeholder="Въведете ключова дума" />
                        <input type="submit" value="Търси" />
                    </form>

                    @error('s')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="results">
                    @if ($search)
                        <div class="info mobile-stickable center">
                            Търсене за: <strong>«{{ $search }}»</strong>.
                            <br />
                            @if ($results->total())
                                Резултати от <strong>{{ $results->from() }}</strong> до <strong>{{ $results->to() }}</strong> (от общо {{ $results->total() }}).
                            @else
                                Резултати: <strong>{{ $results->total() }}</strong>.
                            @endif
                        </div>
                    @endif

                    @foreach ($results->getData() as $book)
                        <div class="book-result-wrapper">
                            <a href="{{ route('public.book', $book->get('slug')) }}" class="book-title desktop-stickable">{{ $book->get('title') }} ({{ $book->get('publish_year') }})</a>
                            <div class="poem-result-wrapper">

                            @foreach ($book->get('poems') as $poem)
                                <div class="poem-result">
                                    <a href="{{ route('public.poem', ['book' => $book->get('slug'), 'poem' => $poem->get('slug')]) }}">{!! $poem->get('title') !!}</a>
                                    @if ($poem->get('dedication'))
                                        <em>{!! $poem->get('dedication') !!}</em>
                                    @endif
                                    <div class="sample">{!! $poem->get('text') !!}</div>
                                </div>
                            @endforeach

                            </div>

                            @if ($book->get('cover_image'))
                            <div class="cover-wrapper">
                                <div class="img-wrapper desktop-stickable"><img src="{{ $book->get('cover_image') }}" alt="{{ $book->get('title') }}" /></div>
                            </div>
                            @endif
                            
                        </div>
                    @endforeach

                    {{-- pagination --}}
                    {{ $results->generatePagination() }}
                </div>

            </div>
        </section>

    </main>

@stop