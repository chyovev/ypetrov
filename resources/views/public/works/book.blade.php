@extends('public.layout.default')

@section('content')

    <main class="sidebar">

        <div class="aside-wrapper">
            <aside>
                <div class="title-wrapper stickable">
                    <div class="title">
                        <a href="{{ route('book', ['bookSlug' => $book->slug]) }}" class="active">«{{ $book->title }}»</a>
                        <div class="filter">
                            <div class="filter-inner-wrapper">
                                <input type="text" id="filter-field" placeholder="Филтър" />
                                <button type="button" class="show" title="Покажи/скрий филтър">Филтър</button>
                            </div>
                            <button type="button" class="clear none" title="Изтрий">Изтрий</button>
                        </div>
                    </div>
                </div>
                <ol>
                    @foreach ($book->poems as $poem)
                        <li><a href="#" data-dedication="{{ $poem->dedication }}">{{ Str::of($poem->title)->stripTags() }}</a></li>
                    @endforeach
                </ol>
            </aside>
            <div class="aside-toggler mobile-only"><span>Съдържание</span></div>
        </div>

        <section class="text" id="container">
            <div class="content-wrapper">
                <div class="poem-wrapper">
                    <h1 class="stickable" id="title">{{ $book->title }}</h1>

                    <div id="body">
                        <div class="book">

                            @if ($book->getCoverImage())
                                <div class="cover"><img src="{{ $book->getCoverImage()->getURL() }}" alt="{{ $book->title }}" /></div>
                            @endif
                            
                            <div class="info">
                                <div><strong>Заглавие:</strong> {{ $book->title }}</div>
                                @if ($book->publisher)
                                    <div><strong>Издателство:</strong> {{ $book->publisher }}</div>
                                @endif
                                <div><strong>Година на издаване:</strong> {{ $book->published_year }}</div>
                                <div><strong>Стихотворения:</strong> {{ $book->poems->count() }}</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>


@stop