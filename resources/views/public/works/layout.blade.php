@extends('public.layout.default')

@section('content')

    <main class="sidebar">

        <div class="aside-wrapper">
            <aside id="no-ajax">
                <div class="title-wrapper stickable">
                    <div class="title">
                        <a href="{{ route('book', ['bookSlug' => $book->slug]) }}" @class(['active' => !isset($poem)])>«{{ $book->title }}»</a>
                        <div class="filter">
                            <div class="filter-inner-wrapper">
                                <button type="button" class="show" title="Покажи/скрий филтър">Филтър</button>
                                <input type="text" id="filter-field" placeholder="Филтър" />
                            </div>
                            <button type="button" class="clear none" title="Изтрий">Изтрий</button>
                        </div>
                    </div>
                </div>
                <ol>
                    @foreach ($book->poems as $bookPoem)
                        <li><a href="{{ route('poem', ['bookSlug' => $book->slug, 'poemSlug' => $bookPoem->slug]) }}" data-dedication="{{ $bookPoem->dedication }}" @class(['active' => isset($poem) && ($poem->slug === $bookPoem->slug)])>{{ Str::of($bookPoem->title)->stripTags() }}</a></li>
                    @endforeach
                </ol>
            </aside>
            <div class="aside-toggler mobile-only"><span>Съдържание</span></div>
        </div>

        <section id="container" @class(['text' => true, 'monospace' => (isset($poem) && $poem->use_monospace_font)])>
            <div class="content-wrapper">

                @yield('work-content')

            </div>
        </section>

    </main>


@stop