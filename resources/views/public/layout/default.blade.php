<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>Официален сайт в памет на поета Йосиф Петров (1909 – 2004)</title>
    <meta property="og:title" content="Официален сайт в памет на поета Йосиф Петров (1909 – 2004)" />
    <meta name="description" content="Йосиф Петров е български поет, общественик и политик" />
    <meta property="og:description" content="Йосиф Петров е български поет, общественик и политик{/if}" />
    <meta name="keywods" content="Йосиф Петров, поезия, поет, общественик, депутат, стихосбирки, стихотворения, VII Велико Народно събрание, Персин" />
    <script type="text/javascript" src="{{ url('js/script.js') }}"></script>
    <link type="text/css" rel="stylesheet" href="{{ url('css/style.css') }}" />
    <meta name="msapplication-TileColor" content="#F6F6F6" />
    <meta name="theme-color" content="#F6F6F6" />

</head>
<body>

    @include('public.layout.header')

    <main>
    
        @yield('content')
        
    </main>
    
    @include('public.layout.footer')
    
</body>
</html>