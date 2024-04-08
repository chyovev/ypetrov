<header>
    <div class="nav-wrapper-outer">
        <div class="nav-wrapper-inner">

            <div class="logo-wrapper">
                <a href="{{ route('home') }}" class="logo">Йосиф Петров</a>
                <span @class(["none" => isset($search) && $search !== ''])>(1909 – 2004)</span>
            </div>

            @include('public.layout.navigation')

        </div>
    </div>

    <div class="header-image"> </div>
</header>