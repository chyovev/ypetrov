@extends('public.layout.default')

@section('content')

    <main class="sidebar">

        <div class="aside-wrapper">
            <aside id="no-ajax">
                <div class="title-wrapper">
                    <div class="title">Галерия</div>
                </div>
                <ol class="images op-0-fadein">
                    @foreach ($gallery as $item)
                    <li>
                        <a href="javascript: void(0);" @class(['thumb' => true, 'active' => $loop->first]) id="thumb-{{ $loop->index }}">
                            <img src="{{ $item->getImageThumbURL() }}" alt="Изображение #{{ $loop->iteration }}" />
                        </a>
                    </li>
                    @endforeach
                </ol>

                @if ($gallery->count() > 1)
                    <div class="center op-0-fadein"><span id="current-image">1</span>/{{ $gallery->count() }}</div>
                @endif

            </aside>
            <div class="aside-toggler mobile-only"><span>Галерия</span></div>
        </div>

        <section @class(['text' => true, 'error' => !$gallery->count()]) id="container">
            <div class="content-wrapper">
                @if ($gallery->count())
                <h1 class="center" id="title">Снимки на Йосиф Петров</h1>

                <div class="gallery-wrapper-outer">

                    <div class="swipe-nav prev" title="Предишна"> </div>

                    <div class="gallery-wrapper-inner">
                        <div id="swipe-gallery">
                            <div class="swipe-wrap op-0-fadein">
                                @foreach ($gallery as $item)
                                    @php
                                        $src = 'src="' . $item->getImageURL() .'"';
                                        if (!$loop->first) {
                                            $src = "data-{$src}";
                                        }
                                    @endphp
                                    <div><img {!! $src !!} alt="Изображение #{{ $loop->iteration }}" title="{{ $item->title }}" /><span>{!! $item->title !!}</span></div>
                                @endforeach
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="swipe-nav next" title="Следваща"> </div>
                @else
                    <div class="title">Галерия</div><br />
                    <p>В момента няма добавени снимки в галерията.</p>
                @endif
                </div>
            </div>
        </section>
    </main>

@stop