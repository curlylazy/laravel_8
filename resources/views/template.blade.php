<html lang="en">

    <head>
        <base href="./">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
        <meta name="description" content="admin panel untuk maintenance sistem">
        <meta name="author" content="Admin Sistem">
        <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">

        <title>Admin Inventaris</title>
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

        <style>
            .disable{
                pointer-events: none;
            }

            .hidden{
                display: none;
            }
        </style>

        @stack('stylecss')

    </head>

    <body class="c-app c-dark-theme">
        <div class="c-sidebar c-sidebar-dark c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
            <div class="c-sidebar-brand d-lg-down-none">
                <img src="{{ asset('logo.png') }}" style="width: 150px;" />
            </div>
            <ul class="c-sidebar-nav">
                <!-- <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="index.html">
                    <svg class="c-sidebar-nav-icon">
                        <use xlink:href="{{ asset('cssadmin/vendors/@coreui/icons/svg/free.svg#cil-speedometer') }}"></use>
                    </svg> Dashboard<span class="badge badge-info">NEW</span></a>
                </li> -->
                <li class="c-sidebar-nav-title">User Menu</li>
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link" href='{{ url("dashboard") }}'><i class="c-sidebar-nav-icon cil-speedometer"></i> Dashboard</a>
                    <a class="c-sidebar-nav-link" href='{{ url("auth/profile") }}'><i class="c-sidebar-nav-icon cil-user-female"></i> Profile</a>
                </li>

                @if(session('akses') == 'ADMIN')
                    <li class="c-sidebar-nav-title">Master</li>
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link" href='{{ url("kategori/list") }}'><i class="c-sidebar-nav-icon cil-tag"></i> Kategori Item</a>
                        <a class="c-sidebar-nav-link" href='{{ url("item/list") }}'><i class="c-sidebar-nav-icon cil-briefcase"></i> Item</a>
                        <a class="c-sidebar-nav-link" href='{{ url("admin/list") }}'><i class="c-sidebar-nav-icon cil-user"></i> User</a>
                        <a class="c-sidebar-nav-link" href='{{ url("supplier/list") }}'><i class="c-sidebar-nav-icon cil-user"></i> Supplier</a>
                        <a class="c-sidebar-nav-link" href='{{ url("pelanggan/list") }}'><i class="c-sidebar-nav-icon cil-user"></i> Pelanggan</a>
                    </li>
                @endif

                @if(session('akses') == 'ADMIN')
                    <li class="c-sidebar-nav-title">Transaksi</li>
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link" href='{{ url("/item/stoklist") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Item Stok</a>
                        <a class="c-sidebar-nav-link" href='{{ url("/itemmasuk/list") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Item Masuk</a>
                        <a class="c-sidebar-nav-link" href='{{ url("/itemkeluar/list") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Item Keluar</a>
                    </li>
                @endif

                @if(session('akses') == 'MANAJEMEN')
                    <li class="c-sidebar-nav-title">Laporan</li>
                    <li class="c-sidebar-nav-item">
                        <a class="c-sidebar-nav-link" href='{{ url("/laporan/grafikstokitem") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Grafik Stok Item</a>
                        <a class="c-sidebar-nav-link" href='{{ url("/laporan/grafikitemmasuk") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Grafik Item Masuk</a>
                        <a class="c-sidebar-nav-link" href='{{ url("/laporan/grafikitemkeluar") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Grafik Item Keluar</a>
                        <a class="c-sidebar-nav-link" href='{{ url("/laporan/pelanggan") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Laporan Pelanggan</a>
                        <a class="c-sidebar-nav-link" href='{{ url("/laporan/supplier") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Laporan Supplier</a>
                        <a class="c-sidebar-nav-link" href='{{ url("/laporan/stokitem") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Laporan Stok Item</a>
                        <a class="c-sidebar-nav-link" href='{{ url("/laporan/itemmasuk") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Laporan Item Masuk</a>
                        <a class="c-sidebar-nav-link" href='{{ url("/laporan/itemkeluar") }}'><i class="c-sidebar-nav-icon cil-featured-playlist"></i> Laporan Item Keluar</a>
                    </li>
                @endif

            </ul>
            <button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>
        </div>

        <div class="c-wrapper c-fixed-components">
            <header class="c-header c-header-light c-header-fixed c-header-with-subheader">
                <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
                    <svg class="c-icon c-icon-lg">
                        <use xlink:href="{{ asset('cssadmin/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
                    </svg>
                </button>
                <a class="c-header-brand d-lg-none" href="#">
                    <svg width="118" height="46" alt="CoreUI Logo">
                        <use xlink:href="{{ asset('cssadmin/assets/brand/coreui.svg#full') }}"></use>
                    </svg>
                </a>
                <button class="c-header-toggler c-class-toggler mfs-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true">
                    <svg class="c-icon c-icon-lg">
                        <use xlink:href="{{ asset('cssadmin/vendors/@coreui/icons/svg/free.svg#cil-menu') }}"></use>
                    </svg>
                </button>


                <!-- <ul class="c-header-nav d-md-down-none">
                    <li class="c-header-nav-item px-3"><a class="c-header-nav-link" onclick="return confirm('Log out dari sistem ?');" href="{{ url('auth/actlogout') }}"> Log Out</a></li>
                </ul> -->

                <ul class="c-header-nav ml-auto mr-4">
                    <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link" href="#">{{ session('akses') }} - {{ session('nama') }}</a></li>
                    <li class="c-header-nav-item px-3"><a class="c-header-nav-link" onclick="return confirm('Log out dari sistem ?');" href="{{ url('auth/actlogout') }}">Log Out</a></li>
                </ul>

                <div class="c-subheader px-3">
                    @yield('breadcumb')
                </div>
            </header>

            <div class="c-body">
                <main class="c-main">
                    <div class="container-fluid">
                        <div class="fade-in">
                            @yield('content')
                        </div>
                    </div>
                </main>
                <footer class="c-footer">
                    <div><a href="https://coreui.io">CoreUI</a> © 2021 Dhimas - UD. Bungan Jepun</div>
                    <div class="ml-auto">Powered by&nbsp;<a href="https://coreui.io/">CoreUI</a></div>
                </footer>
            </div>
        </div>

        <script src="{{ asset('cssadmin/plugins/jquery/dist/jquery.min.js') }}"></script>

        <!-- CoreUI and necessary plugins-->
        <script src="{{ asset('cssadmin/vendors/@coreui/coreui/js/coreui.bundle.min.js') }}"></script>
        <!--[if IE]><!-->
        <script src="{{ asset('cssadmin/vendors/@coreui/icons/js/svgxuse.min.js') }}"></script>
        <!--<![endif]-->
        <!-- Plugins and scripts required by this view-->
        <script src="{{ asset('cssadmin/vendors/@coreui/chartjs/js/coreui-chartjs.bundle.js') }}"></script>
        <script src="{{ asset('cssadmin/vendors/@coreui/utils/js/coreui-utils.js') }}"></script>
        <!-- <script src="{{ asset('cssadmin/js/main.js') }}"></script> -->

        <!-- Date Picker -->
        <link href="{{ asset('cssadmin/plugins/datepicker/jquery.datetimepicker.css') }}" rel="stylesheet">
        <script src="{{ asset('cssadmin/plugins/datepicker/jquery.datetimepicker.full.js') }}"></script>

        <!-- autonumeric -->
        <script type="text/javascript" src="{{ asset('cssadmin/plugins/autonumeric/autoNumeric.min.js') }}"></script>

        <!-- High Chart -->
        <script src="{{ asset('cssadmin/plugins/highchart/js/highcharts.js') }}"></script>
        <script src="{{ asset('cssadmin/plugins/highchart/js/modules/exporting.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('cssadmin/plugins/highchart/js/highcharts.css') }}" type="text/css"/>

        <style>
            div.dataTables_wrapper div.dataTables_length select {
                width: 50px !important;
                display: inline-block;
            }

            .readonly
            {
                pointer-events: none;
            }
        </style>

        <link href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
        <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>

        <!--alerts CSS -->
        <link href="{{ asset('cssadmin/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
        <script src="{{ asset('cssadmin/plugins/sweetalert/sweetalert.min.js') }}"></script>
        <script src="{{ asset('cssadmin/plugins/sweetalert/jquery.sweet-alert.custom.js') }}"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('.datepicker').datetimepicker({
                    timepicker: false,
                    format: 'Y-m-d'
                });
            });
        </script>

        @stack('scripts')
    </body>

</html>
