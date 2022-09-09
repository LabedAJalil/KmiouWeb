<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>KMIOU</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('public/img/favicon.ico')}}">

    <!-- CSS here -->
    <link rel="stylesheet" href="{{asset('public/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/slicknav.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/magnific-popup.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/fontawesome-all.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/themify-icons.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/slick.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/nice-select.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/style.css')}}">

    @yield('css-section')

</head>
<body> 
<!--? Preloader Start -->
<div id="preloader-active">
    <div class="preloader d-flex align-items-center justify-content-center">
        <div class="preloader-inner position-relative">
            <div class="preloader-circle"></div>
            <div class="preloader-img pere-text">
                <img src="{{asset('public/img/logo/loder.jpg')}}" alt="">
            </div>
        </div>
    </div>
</div>
<!-- Preloader Start -->
<header>
    <!-- Header Start -->
    <div class="header-area">
        <div class="main-header ">
            <div class="header-top d-none d-lg-block">
                <div class="container">
                    <div class="col-xl-12">
                        <div class="row d-flex justify-content-between align-items-center">
                            <div class="header-info-left">
                                <ul>     
                                    <li>Phone: +99 (0) 101 0000 888</li>
                                    <li>Email: noreply@yourdomain.com</li>
                                </ul>
                            </div>
                            <div class="header-info-right">
                                <ul class="header-social">    
                                    <li><a href="javascript:void(0);"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="javascript:void(0);"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="javascript:void(0);"><i class="fab fa-linkedin-in"></i></a></li>
                                    <li> <a href="javascript:void(0);"><i class="fab fa-google-plus-g"></i></a></li>
                                </ul>
                            </div>
                            <div id="google_translate_element"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="header-bottom  header-sticky">
                <div class="container">
                    <div class="row align-items-center">
                        <!-- Logo -->
                        <div class="col-xl-2 col-lg-2">
                            <div class="logo">
                                <a href="javascript:void(0);"><img src="{{asset('public/img/logo/logo.png')}}" alt=""></a>
                            </div>
                        </div>
                        <div class="col-xl-10 col-lg-10">
                            <div class="menu-wrapper  d-flex align-items-center justify-content-end">
                                <!-- Main-menu -->
                                <div class="main-menu d-none d-lg-block">
                                    <nav> 
                                        <ul id="navigation">                                                                                          
                                            <li><a href="{{route('driverShowDashboard')}}">Home</a></li>
                                            <li><a href="javascript:void(0);">Your Shipments</a>
                                                 <?php 
                                                    $notiCount = App\Helper::get_user_request_count(Auth::guard('driver')->user()->id);
                                                    ?>@if($notiCount > 0)  <div class="noti-count">{{$notiCount}}</div>@endif
                                                <ul class="submenu">
                                                    <li><a href="{{route('driverShowRequestList')}}">Request</a></li>
                                                    <li><a href="{{route('driverShowActiveShipment')}}">Active Shipments</a></li>
                                                    <li><a href="{{route('driverShowPastShipment')}}">Past Shipments</a></li>
                                                    <!-- <li><a href="{{route('driverShowCancelShipment')}}">Cancel Shipments</a></li>
                                                    <li><a href="{{route('driverShowReportShipment')}}">Report Shipments</a></li> -->
                                                </ul>
                                            </li>
                                            <li><a href="javascript:void(0);">Settings</a>
                                                <ul class="submenu">
                                                    <li><a href="{{route('driverShowProfile')}}">My Profile</a></li>
                                                    <li><a href="{{route('driverShowChangePassword')}}">Change Password</a></li>
                                                    <li><a href="{{route('driverShowTransporterList')}}">Connected Transporter</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="{{route('driverShowHelpFeedback')}}">Help & Feedback</a></li>
                                            <li><a href="{{route('doDriverLogout')}}">Logout</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <!-- Header-btn -->
                                <div class="header-right-btn d-none d-sm-block ml-20">
                                    <a href="{{route('driverShowNotificationList')}}" class="noti-bell"><i class="fa fa-bell"> </i> 
                                        <?php 
                                        $notiCount = App\Helper::getNotificationCount(Auth::guard('driver')->user()->id);
                                        ?>
                                   @if($notiCount > 0)
                                        <div class="noti-count">{{$notiCount}}</div>
                                   @endif
                                    </a>
                                </div>
                            </div>
                        </div> 
                        <!-- Mobile Menu -->
                        <div class="col-12">
                            <div class="mobile_menu d-block d-lg-none"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->
</header>