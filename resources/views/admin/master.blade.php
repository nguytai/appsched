<!doctype html>
<html class="fixed">
<head>

    <!-- Basic -->
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard | Porto Admin - Responsive HTML5 Template 1.5.1</title>
    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Porto Admin - Responsive HTML5 Template">
    <meta name="author" content="okler.net">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Web Fonts  -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light" rel="stylesheet" type="text/css">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.css" />

    <link rel="stylesheet" href="/assets/vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="/assets/vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />

    <!-- Specific Page Vendor CSS -->
    <link rel="stylesheet" href="/assets/vendor/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" href="/assets/vendor/jquery-ui/jquery-ui.theme.css" />
    <link rel="stylesheet" href="/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
    <link rel="stylesheet" href="/assets/vendor/morris.js/morris.css" />
    <!-- Theme CSS -->
    <link rel="stylesheet" href="/assets/stylesheets/theme.css" />

    <!-- Skin CSS -->
    <link rel="stylesheet" href="/assets/stylesheets/skins/default.css" />

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="/assets/stylesheets/theme-custom.css">

    <!-- Head Libs -->
    <script src="/assets/vendor/modernizr/modernizr.js"></script>

    <!-- Custom CSS-->
    @yield("custom_css")
</head>
<body>
<section class="body">

    <!-- start: header -->
    <header class="header">
        <div class="logo-container">
            <a href="../" class="logo">
                <img src="/assets/images/logo.png" height="35" alt="Porto Admin" />
            </a>
            <div class="visible-xs toggle-sidebar-left" data-toggle-class="sidebar-left-opened" data-target="html" data-fire-event="sidebar-left-opened">
                <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
            </div>
        </div>

        <!-- start: search & user box -->
        <div class="header-right">
            <span class="separator"></span>

            <div id="userbox" class="userbox">
                <a href="#" data-toggle="dropdown">
                    <figure class="profile-picture">
                        <img src="/assets/images/!logged-user.jpg" alt="Joseph Doe" class="img-circle" data-lock-picture="assets/images/!logged-user.jpg" />
                    </figure>
                    <div class="profile-info" data-lock-name="John Doe" data-lock-email="johndoe@okler.com">
                        <span class="name">{{ $user['name'] }}</span>
                        <span class="role">{{ $user['account_type'] }}</span>
                    </div>

                    <i class="fa custom-caret"></i>
                </a>

                <div class="dropdown-menu">
                    <ul class="list-unstyled">
                        <li class="divider"></li>
                        <li>
                            <a role="menuitem" tabindex="-1" href="logout"><i class="fa fa-power-off"></i> Logout</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- end: search & user box -->
    </header>
    <!-- end: header -->

    <div class="inner-wrapper">
        <!-- start: sidebar -->
        <aside id="sidebar-left" class="sidebar-left">

            <div class="sidebar-header">
                <div class="sidebar-title">
                    Navigation
                </div>
                <div class="sidebar-toggle hidden-xs" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
                    <i class="fa fa-bars" aria-label="Toggle sidebar"></i>
                </div>
            </div>

            <div class="nano">
                <div class="nano-content">
                    <nav id="menu" class="nav-main" role="navigation">
                        <ul class="nav nav-main">
                            <li class='@yield("dashboard_nav")'>
                                <a href="/admin">
                                    <i class="fa fa-home" aria-hidden="true"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>
                            <li class='@yield("schedule_nav")'>
                                <a href="schedule">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                    <span>Schedule</span>
                                </a>
                            </li>
                            <li class='@yield("services_nav")'>
                                <a href="services">
                                    <i class="fa fa-list-alt" aria-hidden="true"></i>
                                    <span>Services</span>
                                </a>
                            </li>
                            <li class='@yield("appointments_nav")'>
                                <a href="appointments">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                                    <span>My Appointments List</span>
                                </a>
                            @if($user['account_type'] == "admin")
                                <li class='@yield("stylists_nav")'>
                                    <a href="stylists">
                                        <i class="fa fa-user" aria-hidden="true"></i>
                                        <span>Stylist List</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            </div>
        </aside>
        <!-- end: sidebar -->

        <section role="main" class="content-body">
            <header class="page-header">
                <h2>@yield("page_header")</h2>

                <div class="right-wrapper pull-right">
                    <ol class="breadcrumbs">
                        <li>
                            <a href="/dashboard">
                                <i class="fa fa-home"></i>
                            </a>
                        </li>
                        <li><span>Dashboard</span></li>
                    </ol>

                    <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fa fa-chevron-left"></i></a>
                </div>
            </header>

            <!-- start: page -->
                @yield("body")
            <!-- end: page -->
        </section>
    </div>

</section>

<!-- Vendor -->
<script src="/assets/vendor/jquery/jquery.js"></script>
<script src="/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="/assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="/assets/vendor/nanoscroller/nanoscroller.js"></script>
<script src="/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="/assets/vendor/magnific-popup/jquery.magnific-popup.js"></script>
<script src="/assets/vendor/jquery-placeholder/jquery-placeholder.js"></script>

<!-- Specific Page Vendor -->
<script src="/assets/vendor/jquery-ui/jquery-ui.js"></script>
<script src="/assets/vendor/jqueryui-touch-punch/jqueryui-touch-punch.js"></script>
<script src="/assets/vendor/jquery-appear/jquery-appear.js"></script>
<script src="/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.js"></script>
<script src="/assets/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.js"></script>
<script src="/assets/vendor/flot/jquery.flot.js"></script>
<script src="/assets/vendor/flot.tooltip/flot.tooltip.js"></script>
<script src="/assets/vendor/flot/jquery.flot.pie.js"></script>
<script src="/assets/vendor/flot/jquery.flot.categories.js"></script>
<script src="/assets/vendor/flot/jquery.flot.resize.js"></script>
<script src="/assets/vendor/jquery-sparkline/jquery-sparkline.js"></script>
<script src="/assets/vendor/raphael/raphael.js"></script>
<script src="/assets/vendor/morris.js/morris.js"></script>
<script src="/assets/vendor/gauge/gauge.js"></script>
<script src="/assets/vendor/snap.svg/snap.svg.js"></script>
<script src="/assets/vendor/liquid-meter/liquid.meter.js"></script>
<script src="/assets/vendor/jqvmap/jquery.vmap.js"></script>
<script src="/assets/vendor/jqvmap/data/jquery.vmap.sampledata.js"></script>
<script src="/assets/vendor/jqvmap/maps/jquery.vmap.world.js"></script>
<script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.africa.js"></script>
<script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.asia.js"></script>
<script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.australia.js"></script>
<script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.europe.js"></script>
<script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.north-america.js"></script>
<script src="/assets/vendor/jqvmap/maps/continents/jquery.vmap.south-america.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="/assets/javascripts/theme.js"></script>

<!-- Theme Custom -->
<script src="/assets/javascripts/theme.custom.js"></script>

<!-- Theme Initialization Files -->
<script src="/assets/javascripts/theme.init.js"></script>

<!-- Examples -->
@yield("custom_scripts")
</body>
</html>