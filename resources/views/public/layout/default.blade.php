<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>@metaTitle</title>
    <meta property="og:title" content="@metaTitle" />
    <meta name="description" content="@metaDescription" />
    <meta property="og:description" content="@metaDescription" />
    <meta name="keywods" content="Йосиф Петров, поезия, поет, общественик, депутат, стихосбирки, стихотворения, VII Велико Народно събрание, Персин" />
    <script type="text/javascript" src="{{ url('js/script.js') }}"></script>
    <link type="text/css" rel="stylesheet" href="{{ url('css/style.css') }}" />
    <meta name="msapplication-TileColor" content="#F6F6F6" />
    <meta name="theme-color" content="#F6F6F6" />
    @if (isset($noindex))
        <meta name="robots" content="noindex" />
    @endif
</head>
<body>

    @include('public.layout.header')

    
    @yield('content')
        
    
    @include('public.layout.footer')
    
</body>
</html>