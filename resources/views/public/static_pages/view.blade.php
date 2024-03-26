@extends('public.layout.default')

@section('content')

    <main>

        <section class="text textpage" id="container">
            <div class="content-wrapper">
                <h1>{{ $page->title }}</h1>

                {!! $page->text !!}

                <x-public.like-button :object="$page" :wide="true" />

                <x-public.comments :object="$page" />
            </div>
        </section>
        
    </main>

@stop