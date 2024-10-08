@extends('public.works.layout')

@section('work-content')

    <div class="poem-wrapper">
        <h1 class="stickable" id="title">{{ $book->title }}</h1>

        <div id="body">
            <div class="book">

                @if ($cover = $book->getCoverImage())
                    <div class="cover"><img src="{{ $cover }}" alt="{{ $book->title }}" /></div>
                @endif
                
                <div class="info">
                    <div><strong>Заглавие:</strong> {{ $book->title }}</div>
                    @if ($book->publisher)
                        <div><strong>Издателство:</strong> {{ $book->publisher }}</div>
                    @endif
                    <div><strong>Година на издаване:</strong> {{ $book->publish_year }}</div>
                    <div><strong>Стихотворения:</strong> {{ $book->poems->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    <x-public.like-button :object="$book" />

    <x-public.comments :object="$book" />

@stop