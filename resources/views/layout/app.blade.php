<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if ((request()->getHost() != 'localhost'))
    <!-- Start cookieyes banner -->
    <script id="cookieyes" type="text/javascript" src="https://cdn-cookieyes.com/client_data/0b31c6d330d72ed32b2e2498/script.js"></script>
    <!-- End cookieyes banner -->,

    <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-0D9MEZTBX8"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'G-0D9MEZTBX8');
        </script>
    @endif

    <!-- Title -->
    <title>Fixihem</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="./favicon.ico">

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="/assets/css/vendor.min.css">

    <!-- CSS Unify Template -->
    <link rel="stylesheet" href="/assets/css/theme.min.css?v=1.0">
    <link rel="stylesheet" href="/assets/vendor/hs-mega-menu/dist/hs-mega-menu.min.css">

    <link rel="stylesheet" href="/assets/css/all.css"/>
    <link rel="stylesheet" href="/assets/css/custom.css">

    <link rel="stylesheet" href="{{ asset('assets/css/datepicker.css') }}">
</head>

<body>
<!-- ========== HEADER ========== -->
<header id="header" class="navbar navbar-expand-lg navbar-shadow navbar-sticky-top navbar-show-hide navbar-light bg-white" style="z-index: 999999"
        data-hs-header-options='{
          "fixMoment": 500,
          "fixEffect": "slide"
        }'>
    <div class="container">
        <div class="navbar-nav-wrap">
            <div class="navbar-brand-wrapper">
                <!-- Logo -->
                <a class="navbar-brand" href="../index.html" aria-label="Unify">
                    <img class="navbar-brand-logo" src="{{ asset('assets/img/logo_small.png') }}" alt="Logo">
                </a>
                <!-- End Logo -->
            </div>

            <!-- Toggle -->
            <button type="button" class="navbar-toggler ms-auto" data-bs-toggle="collapse" data-bs-target="#navbarNavMenuLeftAligned" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navbarNavMenuLeftAligned">
                <span class="navbar-toggler-default">
                    <i class="bi-list"></i>
                </span>
                <span class="navbar-toggler-toggled">
                    <i class="bi-x"></i>
                </span>
            </button>
            <!-- End Toggle -->

            <nav class="navbar-nav-wrap-col collapse navbar-collapse" id="navbarNavMenuLeftAligned">
                <!-- Navbar -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Hem</a>
                    </li>
                </ul>
                <!-- End Navbar -->
            </nav>
        </div>
    </div>
</header>
<!-- ========== END HEADER ========== -->

@yield('content')

<!-- ========== FOOTER ========== -->
<footer class="container py-4">
    <div class="row align-items-md-center text-center text-md-start">
        <div class="col-md mb-3 mb-md-0">
            <p class="fs-5 mb-0">&copy; {{ date('Y') }} Fixihem. Alla rättigheter förbehållna.</p>
        </div>

        <!--<div class="col-md d-md-flex justify-content-md-end">
            <ul class="list-inline mb-0">
                <li class="list-inline-item">
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle" href="#">
                        <i class="bi-facebook"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle" href="#">
                        <i class="bi-twitter"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle" href="#">
                        <i class="bi-github"></i>
                    </a>
                </li>
                <li class="list-inline-item">
                    <a class="btn btn-icon btn-sm btn-ghost-secondary rounded-circle" href="#">
                        <i class="bi-slack"></i>
                    </a>
                </li>
            </ul>
        </div>-->
    </div>
</footer>
<!-- ========== END FOOTER ========== -->

@if (session()->has('status'))
    <div id="toast" class="toast shadow" role="alert" aria-live="assertive" aria-atomic="true" data-delay="10000" style="z-index: 9999999">
        <div class="toast-header bg-gradient-light text-gray-800">
            <span class="me-auto"><i class="fas fa-info-circle"></i> Meddelande</span>
            <i type="button" class="fa-solid fa-times" data-bs-dismiss="toast" aria-label="Close"></i>
        </div>
        <div class="toast-body bg-white p-3 text-muted">
            {{ session('status') }}
        </div>
    </div>
@endif

<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <img src="..." class="rounded me-2" alt="...">
        <strong class="me-auto">Bootstrap</strong>
        <small>11 mins ago</small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        Hello, world! This is a toast message.
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- JS Implementing Plugins -->
<script src="/assets/vendor/hs-mega-menu/dist/hs-mega-menu.min.js"></script>
<script src="/assets/vendor/hs-header/dist/hs-header.min.js"></script>

<!-- JS Implementing Plugins -->
<script src="/assets/js/vendor.min.js"></script>

<!-- JS Unify -->
<script src="/assets/js/theme.min.js"></script>

<!-- JS Plugins Init. -->
<script>
    window.onload = (event) => {
        var toastLiveExample = document.getElementById('toast')
        var toast = new bootstrap.Toast(toastLiveExample)
        toast.show()
    }
</script>

@stack('js')
</body>
</html>
