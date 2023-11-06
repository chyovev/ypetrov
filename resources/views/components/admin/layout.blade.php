<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('admin/images/favicon.png') }}">
    <title>{{ config('app.name') }}</title>
    <link href="{{ asset('admin/css/lib/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/helper.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/lib/sweetalert/sweetalert.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/lib/html5-editor/bootstrap-wysihtml5.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/lib/loudev-multiselect/multi-select.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/lib/datepicker/bootstrap-datepicker3.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('admin/css/custom.css') }}" rel="stylesheet" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:** -->
    <!--[if lt IE 9]>
    <script src="https:**oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https:**oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="fix-header fix-sidebar">
    <!-- Preloader - style you can find in spinners.css -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
			<circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> </svg>
    </div>
    <!-- Main wrapper  -->
    <div id="main-wrapper">
        <x-admin.header/>

        <x-admin.sidebar/>
        
        <!-- Page wrapper  -->
        <div class="page-wrapper">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">{{ $title }}</h3>
                </div>

                <div class="col-md-7 align-self-center">
                    <x-admin.breadcrumbs :$route :param="isset($param) ? $param : null" />
                </div>
            </div>

            <!-- End Bread crumb -->
            <!-- Container fluid  -->
            <div class="container-fluid">

                <x-admin.flash/>

                {{ $slot }}

            </div>
            <!-- End Container fluid  -->
        </div>
        <!-- End Page wrapper  -->
    </div>

    <!-- since the destroy endpoints expect a DELETE verb, and
         simply calling them from an <a> tag sends a GET verb,
         the work-around is to use the endpoint as an action for
         this hidden form and trigger a spoofed DELETE request -->
    <form id="destroy-form" method="POST" class="hide">
        @method('DELETE')
        @csrf
    </form>

    <script src="{{ asset('admin/js/lib/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/js/lib/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('admin/js/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('admin/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('admin/js/lib/sticky-kit-master/dist/sticky-kit.min.js') }}"></script>
    <script src="{{ asset('admin/js/lib/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('admin/js/lib/html5-editor/wysihtml5-0.3.0.js') }}"></script>
    <script src="{{ asset('admin/js/lib/html5-editor/bootstrap-wysihtml5.js') }}"></script>
    <script src="{{ asset('admin/js/lib/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('admin/js/lib/loudev-multiselect/jquery.multi-select.js') }}"></script>
    <script src="{{ asset('admin/js/lib/datepicker/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('admin/js/custom.min.js') }}"></script>
</body>

</html>