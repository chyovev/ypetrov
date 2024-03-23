@extends('public.layout.default')

@section('content')

    <main class="sidebar">

        <div class="aside-wrapper">
            <aside id="no-ajax">
                <div class="title-wrapper">
                    <div class="title">Видео</div>
                </div>
                <ol class="videos">
                    @foreach ($videos as $item)
                    <li>
                        <a href="{{ route('video', ['slug' => $item->slug]) }}" @class(['active' => ($item->slug === $video->slug)])>
                            <span>{{ $item->title }}</span>
                            <img src="{{ $item->getCoverImage() }}" alt="{{ $item->title }}" />
                        </a>
                    </li>
                    @endforeach
                </ol>
            </aside>
            <div class="aside-toggler mobile-only"><span>Още видеа</span></div>
        </div>

        <section class="text monospace" id="container">
            <div class="content-wrapper">
                <h1 class="center" id="title">{{ $video->title }}</h1>
                @if ($video->publish_date)
                    <div class="subtitle">
                        {{ $video->publish_date->format('d.m.Y г.') }}
                    </div>
                @endif

                @if ($video->summary)
                    <div id="summary">{!! nl2br(trim($video->summary)) !!}</div>
                @endif
                
                <div class="video">
                    <video preload="metadata" controls="controls" poster="{{ $video->getCoverImage() }}">
                        @foreach ($video->getVideos() as $attachment)
                            <source src="{{ $attachment->getURL() }}" type="{{ $attachment->mime_type }}">
                        @endforeach
                        <div>
                            Вашият браузър не поддържа вграждане на видео.<br />
                            Можете да свалите видеото <a href="{{ $video->getVideos()->first()?->getURL() }}">оттук</a>.
                        </div>
                    </video>
                </div>

                <x-public.comments :object="$video" />

            </div>
        </section>
    </main>

@stop