@extends('public.layout.default')

@section('content')

    <main>

        <section class="text textpage" id="container">
            <div class="content-wrapper">
                <h1>{{ $article->title }}</h1>

                <div class="subtitle">
                    {{ $article->press }}@if ($article->publish_date), {{ $article->publish_date?->format('d.m.Y г.')}}@endif
                </div>

                <hr />

                {!! $article->text !!}

                <x-public.comments :object="$article" />

            </div>
        </section>

    </main>

@stop