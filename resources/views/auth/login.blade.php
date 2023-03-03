<!DOCTYPE html>
<html lang="en">

<head>
    <title>Login Inventaris</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('csslogin/css/css-A.style.css.pagespeed.cf.eQk9-CoeFP.css') }}">
</head>

<body class="img js-fullheight" style="background-image:url({{ asset('csslogin/bglogin.jpg') }})">
    <section class="ftco-section">
        <div class="container">
            @if (session('pesaninfo'))
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center mb-5">
                        {!! session('pesaninfo') !!}
                    </div>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <img src="{{ asset('logo.png') }}" style="width: 250px; margin-bottom:10px;" />
                    <h2 class="heading-section">UD. BUNGAN JEPUN</h2>
                    <p style="color: white; letter-spacing: 2px;">Admin Login Inventaris</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">
                    <div class="login-wrap p-0">
                        <form class="signin-form" enctype="multipart/form-data" method="post" action='{{ url("auth/actlogin") }}'>
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" placeholder="Username" name="username" required>
                            </div>
                            <div class="form-group">
                                <input id="password-field" type="password" class="form-control" name="password" placeholder="Password" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary submit px-3">Login</button>
                            </div>
                        </form>

                        <p class="w-100 text-center">&mdash; Sistem by Dhimas &mdash;</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('csslogin/js/7077-js-jquery.min.js') }}"></script>
    <script src="{{ asset('csslogin/js/7987-js-popper.js+bootstrap.min.js+main.js.pagespeed.jc.9eD6_Mep8S.js') }}"></script>
    <script>eval(mod_pagespeed_T07FyiNNgg);</script>
    <script>eval(mod_pagespeed_zB8NXha7lA);</script>
    <script>eval(mod_pagespeed_xfgCyuItiV);</script>
    <script defer src="https://static.cloudflareinsights.com/beacon.min.js/v652eace1692a40cfa3763df669d7439c1639079717194" integrity="sha512-Gi7xpJR8tSkrpF7aordPZQlW2DLtzUlZcumS8dMQjwDHEnw9I7ZLyiOj/6tZStRBGtGgN6ceN6cMH8z7etPGlw==" data-cf-beacon='{"rayId":"6d04b020efb6e38e","token":"cd0b4b3a733644fc843ef0b185f98241","version":"2021.12.0","si":100}' crossorigin="anonymous"></script>
</body>

</html>



<!-- <html lang="en">

    <head>
        <base href="./">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta name="description" content="admin panel untuk maintenance sistem">
        <meta name="author" content="Admin Sistem">
        <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">

        <title>Admin Boat</title>
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">

        <link rel="stylesheet" href="https://unpkg.com/@coreui/icons@2.0.0-beta.3/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@5.8.55/css/materialdesignicons.min.css">
        <link href="{{ asset('cssadmin/css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('cssadmin/plugins/font-awesome/css/font-awesome.css') }}" rel="stylesheet">

        <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-118965717-3"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            // Shared ID
            gtag('config', 'UA-118965717-3');
            // Bootstrap ID
            gtag('config', 'UA-118965717-5');
        </script>


    </head>

    <body class="c-app flex-row align-items-center">
        <div class="container">

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card-group">
                        <div class="card p-4">
                            <div class="card-body">

                                <form id="form1" enctype="multipart/form-data" method="post" action='{{ url("admin/auth/actlogin") }}' id="form1" >

                                @csrf

                                <h1>Login Admin</h1>
                                <p class="text-muted">CV. AKARDAYA MANDIRI BADUNG</p>

                                @if (session('pesaninfo'))
                                    <div class="row">
                                        <div class="col-md-12">
                                            {!! session('pesaninfo') !!}
                                        </div>
                                    </div>
                                @endif

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <svg class="c-icon">
                                                <use xlink:href="{{ asset('cssadmin/vendors/@coreui/icons/svg/free.svg#cil-user') }}"></use>
                                            </svg>
                                        </span>
                                    </div>
                                    <input class="form-control" type="text" placeholder="Useradmin" name="useradmin">
                                </div>
                                <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <svg class="c-icon">
                                                <use xlink:href="{{ asset('cssadmin/vendors/@coreui/icons/svg/free.svg#cil-lock-locked') }}"></use>
                                            </svg>
                                        </span>
                                    </div>
                                    <input class="form-control" type="password" placeholder="Password" name="password">
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <button class="btn btn-primary px-4" type="submit">Login</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card text-white bg-warning py-5 d-md-down-none" style="width:44%; background-color: #520000">
                            <div class="card-body text-center">
                                <div>
                                    <img src="{{ asset('cssfront/img/logo.png') }}" style="width: 100%;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    @by Agus Kurniawan
                </div>
            </div>
        </div>

    </body>

</html>
 -->
