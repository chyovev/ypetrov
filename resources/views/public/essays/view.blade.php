@extends('public.layout.default')

@section('content')

    <main>

        <section class="text textpage" id="container">
            <div class="content-wrapper">
                <h1>{{ $essay->title }}</h1>

                <hr />

                {!! $essay->text !!}

            </div>
        </section>

    </main>

@stop