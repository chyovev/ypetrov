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
    @if (isset($images))
        @foreach ($images as $attachment)
            <meta property="og:image:width" content="{{ $attachment->getWidth() }}" />
            <meta property="og:image:height" content="{{ $attachment->getHeight() }}" />
            <meta property="og:image" content="{{ $attachment->getURL() }}" />        
        @endforeach
    @endif
    <meta property="og:image:width" content="768" />
    <meta property="og:image:height" content="1024" />
    <meta property="og:image" content="{{ url('images/og-image.jpg') }}" />
    <link rel="apple-touch-icon" sizes="120x120" href="{{ url('images/favicon/apple-touch-icon.png') }}" />
    <link rel="apple-touch-icon-precomposed" type="image/png" href="{{ url('images/favicon/apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ url('images/favicon/favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ url('images/favicon/favicon-16x16.png') }}" />
    <link rel="shortcut icon" href="{{ url('images/favicon/favicon.ico') }}" />
    <link rel="manifest" href="{{ url('images/favicon/site.webmanifest') }}" />
    <link rel="mask-icon" href="{{ url('images/favicon/favicon.png') }}" color="#F6F6F6" />
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