<div class="header">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ route('admin.home') }}">
                <b><img src="{{ asset('admin/images/logo.png') }}" alt="homepage" class="dark-logo" /></b>
                <span><img src="{{ asset('admin/images/logo-text.png') }}" alt="homepage" class="dark-logo" /></span>
            </a>
        </div>

        <div class="navbar-collapse">
            <ul class="navbar-nav mr-auto mt-md-0">
                <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
            </ul>

            <ul class="navbar-nav my-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-user"></i></a>
                    <div class="dropdown-menu dropdown-menu-right animated slideInRight">
                        <ul class="dropdown-user">
                            <li><a href="{{ route('admin.logout') }}"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

    </nav>
</div>