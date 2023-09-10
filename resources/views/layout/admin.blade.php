<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Admin - @yield('title')</title>

    <!-- Google Tag Manager
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-KS8CR69');</script>-->
    <!-- End Google Tag Manager -->

    <meta name="description" content="...">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, maximum-scale=5, initial-scale=1">
    <!--[if IE]>
    <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'><![endif]-->
    <meta name="msapplication-TileColor" content="#4cbbb8">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#4cbbb8">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- speed up external res -->
    <link rel="dns-prefetch" href="{{ url('https://fonts.googleapis.com/') }}">
    <link rel="dns-prefetch" href="{{ url('https://fonts.gstatic.com/') }}">
    <link rel="preconnect" href="{{ url('https://fonts.googleapis.com/') }}">
    <link rel="preconnect" href="{{ url('https://fonts.gstatic.com/') }}">
    <!-- preloading icon font is helping to speed up a little bit -->
    <link rel="preload" href="{{ asset('assets/admin/fonts/flaticon/Flaticon.woff2') }}" as="font" type="font/woff2"
          crossorigin>

    <link rel="stylesheet" href="{{ asset('assets/admin/css/core.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/vendor_bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/custom.css') }}">
    <link href="{{ url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700&display=swap') }}"
          rel="stylesheet">
    <link href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css') }}" rel="stylesheet">
    <link href="{{ url('https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.1/ui/trumbowyg.min.css') }}"
          rel="stylesheet">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jodit/3.6.1/jodit.min.css"/>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/brands.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/solid.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/light.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/thin.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/regular.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/datepicker.css') }}">

</head>

<body class="layout-admin layout-padded aside-sticky"
      data-s2t-class="btn-primary btn-sm bg-gradient-default rounded-circle border-0">

<div id="wrapper" class="d-flex align-items-stretch flex-column">

    <!--  header -->
    <header id="header" class="d-flex align-items-center bg-transparent">

        <!-- Navbar -->
        <div class="px-3 px-lg-0 w-100 position-relative">

            <nav class="navbar navbar-expand-lg navbar-light justify-content-between h-auto">

                <!-- logo, navigation toggler -->
                <div class="align-items-start">

                    <!-- sidebar toggler -->
                    <a href="#aside-main"
                       class="btn-sidebar-toggle h-100 d-inline-block d-lg-none justify-content-center align-items-center p-2">
                        <span>
                            <svg width="25" height="25" viewBox="0 0 20 20">
                                <path
                                    d="M 19.9876 1.998 L -0.0108 1.998 L -0.0108 -0.0019 L 19.9876 -0.0019 L 19.9876 1.998 Z"></path>
                                <path
                                    d="M 19.9876 7.9979 L -0.0108 7.9979 L -0.0108 5.9979 L 19.9876 5.9979 L 19.9876 7.9979 Z"></path>
                                <path
                                    d="M 19.9876 13.9977 L -0.0108 13.9977 L -0.0108 11.9978 L 19.9876 11.9978 L 19.9876 13.9977 Z"></path>
                                <path
                                    d="M 19.9876 19.9976 L -0.0108 19.9976 L -0.0108 17.9976 L 19.9876 17.9976 L 19.9876 19.9976 Z"></path>
                            </svg>
                        </span>
                    </a>

                    <!-- logo : mobile only -->
                    <a class="navbar-brand d-inline-block d-lg-none mx-2" href="/">
                        <img src="{{ asset('/assets/images/putsamer.png') }}" class="img-fluid" alt="...">
                    </a>

                </div>

            </nav>

        </div>

    </header>

    <div id="wrapper_content" class="d-flex flex-fill">

        <aside id="aside-main" class="aside-start aside-hide-xs bg-white shadow-sm d-flex flex-column px-2 h-auto">

            <div class="py-2 px-3 mb-3 mt-1">
                <a href="/">
                    <img src="{{ asset('/assets/images/putsamer.png') }}" class="img-fluid" alt="...">
                </a>
            </div>

            <div class="aside-wrapper scrollable-vertical scrollable-styled-light align-self-baseline h-100 w-100">

                <nav class="nav-deep nav-deep-sm nav-deep-light">
                    <ul class="nav flex-column">

                        <li class="nav-item">
                            <a class="nav-link" href="/admin">
                                <i class="fa-solid fa-house fa-fw"></i>
                                <span>Start</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('today') }}">
                                <i class="fa-solid fa-calendar-day fa-fw"></i>
                                <span>Dagens</span>
                                <span
                                    class="badge bg-secondary float-end fw-normal">{{ request()->today_count }}</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class="fa-regular fa-calendar-clock fa-fw"></i>
                                <span>Bokningar</span>
                                <span class="group-icon float-end">
                                    <i class="fi fi-arrow-end"></i>
                                    <i class="fi fi-arrow-down"></i>
                                </span>
                            </a>

                            <ul class="nav flex-column">

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings', ['status' => 1]) }}">
                                        Obekräftade
                                        <span class="badge bg-secondary float-end fw-normal">
                                            {{ count(request()->bookings->where('status', 1)) }}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings', ['status' => 2]) }}">
                                        Bekräftade
                                        <span class="badge bg-secondary float-end fw-normal">
                                            {{ count(request()->bookings->where('status', 2)) }}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings', ['status' => 3]) }}">
                                        Utförda
                                        <span class="badge bg-secondary float-end fw-normal">
                                            {{ count(request()->bookings->where('status', 3)) }}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings', ['status' => 4]) }}">
                                        Fakturerade
                                        <span class="badge bg-secondary float-end fw-normal">
                                            {{ count(request()->bookings->where('status', 4)) }}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings', ['status' => 5]) }}">
                                        Betalda
                                        <span class="badge bg-secondary float-end fw-normal">
                                            {{ count(request()->bookings->where('status', 5)) }}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings', ['status' => 6]) }}">
                                        Pausade
                                        <span class="badge bg-secondary float-end fw-normal">
                                            {{ count(request()->bookings->where('status', 6)) }}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('bookings', ['status' => 7]) }}">
                                        Makulerade
                                        <span class="badge bg-secondary float-end fw-normal">
                                            {{ count(request()->bookings->where('status', 7)) }}
                                        </span>
                                    </a>
                                </li>

                            </ul>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reserved') }}">
                                <i class="fa-regular fa-calendar-circle-plus fa-fw"></i>
                                <span>Reservera</span>
                            </a>
                        </li>

                    </ul>
                </nav>

            </div>

        </aside>

        <main id="middle" class="flex-fill mx-auto pt-0 pb-5">

            <div class="container">
                <div class="bg-white rounded p-3 mb-3">
                    <div class="d-flex flex-column flex-md-row justify-content-center justify-content-md-start">


                    </div>

                </div>

                @yield('content')
            </div>


        </main>

    </div>

    @if (session()->has('status'))
        <div class="toast shadow" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="toast-header bg-gradient-light text-gray-800">
                <span class="me-auto"><i class="fas fa-info-circle"></i> Meddelande</span>
                <i type="button" class="fa-solid fa-times" data-bs-dismiss="toast" aria-label="Close"></i>
            </div>
            <div class="toast-body bg-white p-3 text-muted">
                {{ session('status') }}
            </div>
        </div>
    @endif
</div>

<script src="{{ asset('assets/admin/js/core.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/vendor_bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.1/trumbowyg.min.js') }}"></script>
<script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.25.1/langs/sv.min.js') }}"></script>
<script src="{{ url('https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js') }}"></script>
<script async type="text/javascript"
        src="{{ url('https://cdn.jsdelivr.net/gh/avalon-studio/Bootstrap-Lightbox/bs5lightbox.js') }}"
        crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jodit/3.6.1/jodit.min.js"></script>

@yield('script')
@yield('script2')

<script>
    $('nav li').removeClass('active');
    $('nav li a[href="' + document.location.href + '"]').parents('li').addClass('active');
</script>
</body>
</html>
