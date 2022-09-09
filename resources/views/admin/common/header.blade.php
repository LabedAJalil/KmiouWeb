<!DOCTYPE html>
<html lang="en">

<head>
    <title>KMIOU</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="author" content="CodedThemes"/>

    <!-- Favicon icon -->
    <link rel="icon" href="{{asset('public/admin/images/favicon.png')}}" type="image/x-icon">
    <!-- fontawesome icon -->
    <link rel="stylesheet" href="{{asset('public/admin/fonts/fontawesome/css/fontawesome-all.min.css')}}">
    <!-- animation css -->
    <link rel="stylesheet" href="{{asset('public/admin/plugins/animation/css/animate.min.css')}}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{asset('public/admin/css/style.css')}}">

    <link rel="stylesheet" href="{{asset('public/css/bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/responsive.bootstrap4.min.css')}}">

    
    <link rel="stylesheet" href="{{asset('public/css/nice-select.css')}}">

    @yield('css-section')
    
    <style>
        .js_enabled #cookie-message {
    display: none;
}
    </style>

</head>

<body>
    <div id="cookie-message">
    <p>
        Your cookie message goes here
    </p>
    </div>
<script>
    // Detect JS support
    document.body.className = document.body.className + " js_enabled";
</script>
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar">
        <div class="navbar-wrapper">
            <div class="navbar-brand header-logo">
                <a href="javascript:void(0);" class="b-brand">
                    <div class="b-bg">
                        <img src="{{asset('public/images/logo.png')}}">
                    </div>
                    <span class="b-title">KMIOU</span>
                </a>
                <a class="mobile-menu" id="mobile-collapse" href="javascript:"><span></span></a>
            </div>
            <div class="navbar-content scroll-div">
                <ul class="nav pcoded-inner-navbar">
                    <li class="nav-item pcoded-menu-caption">
                        <label>Navigation</label>
                    </li>
                    <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item active">
                        <a href="{{route('adminDashboard')}}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                    </li>
                    <li class="nav-item pcoded-menu-caption">
                        <label>Shipments</label>
                    </li>
                    
                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0);" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Transporter</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('transporterUserList')}}" class="">Transporter List</a></li>
                        </ul>
                    </li>
                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0);" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Driver</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('driverUserList')}}" class="">Driver List</a></li>
                        </ul>
                    </li>
                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:void(0);" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Shipper</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('userList')}}" class="">Available Shipper List</a></li>
                            <!-- <li class=""><a href="{{route('newUserList')}}" class="">New Users List</a></li> -->
                        </ul>
                    </li>
                    
                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Bookings</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('shipmentQuoteRequestList')}}" class="">Instant Quote Request</a></li>
                            <li class=""><a href="{{route('shipmentRequestList')}}" class="">Shipments Request</a></li>
                            <li class=""><a href="{{route('shipmentApprovedList')}}" class="">Active Shipments</a></li>
                            <li class=""><a href="{{route('shipmentCompletedList')}}" class="">Past Shipments</a></li>
                            <li class=""><a href="{{route('shipmentCancelledList')}}" class="">Cancelled Shipments</a></li>
                            <li class=""><a href="{{route('shipmentReportedList')}}" class="">Reported Shipments</a></li>
                        </ul>
                    </li>
                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Coupon</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('couponList')}}" class="">Promo Code List</a></li>
                        </ul>
                    </li>

                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Truck</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('truckList')}}" class="">truck List</a></li>
                        </ul>
                    </li>

                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Goods Type</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('goodsTypeList')}}" class="">Goods Type List</a></li>
                        </ul>
                    </li>

                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Shipment Surge Price</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('shipmentSurgePriceList')}}" class="">Surge Price Request</a></li>
                        </ul>
                    </li>

                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Payment</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('paymentInfoList')}}" class="">Payment Info List</a></li>
                        </ul>
                    </li>

                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Performance Report</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('performanceReportList')}}" class="">Performance Report List</a></li>
                        </ul>
                    </li>

                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-box"></i></span><span class="pcoded-mtext">Commission</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('commissionList')}}" class="">Users Commission List</a></li>
                        </ul>
                    </li>
                    <li class="nav-item pcoded-menu-caption">
                        <label>Profile</label>
                    </li>
                    <!-- <li data-username="form elements advance componant validation masking wizard picker select" class="nav-item">
                        <a href="form_elements.html" class="nav-link "><span class="pcoded-micon"><i class="feather icon-file-text"></i></span><span class="pcoded-mtext">Profile</span></a>
                    </li> -->
                    <li data-username="basic components Button Alert Badges breadcrumb Paggination progress Tooltip popovers Carousel Cards Collapse Tabs pills Modal Grid System Typography Extra Shadows Embeds" class="nav-item pcoded-hasmenu">
                        <a href="javascript:" class="nav-link "><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">Settings</span></a>
                        <ul class="pcoded-submenu">
                            <li class=""><a href="{{route('showSendNotification')}}" class="">Send Notification</a></li>
                            <li class=""><a href="{{route('surgePriceList')}}" class="">Surge Price List</a></li>
                            <li class=""><a href="{{url('admin/edit_profile')}}" class="">Edit Profile</a></li>
                            <li class=""><a href="{{route('showChangePassword',array('user_id'=> Auth::guard('admin')->user()->id ))}}" class="">Change Password</a></li>
                            <li class=""><a href="{{url('admin/privacy_policy')}}" class="">Privacy Policy</a></li>
                            <li class=""><a href="{{url('admin/terms_cond')}}" class="">Terms & Conditions</a></li>
                            <li class=""><a href="{{url('admin/contact_support')}}" class="">Contact Support</a></li>
                            <li class=""><a href="{{route('supportnumberlist')}}" class="">Support Number</a></li>
                        </ul>
                    </li>
                    <li data-username="Charts Morris" class="nav-item"><a href="{{url('admin/help_feedback')}}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-help-circle"></i></span><span class="pcoded-mtext">Help & Feedback</span></a></li>
                    <li data-username="Maps Google" class="nav-item"><a href="{{route('doAdminLogout')}}" class="nav-link "><span class="pcoded-micon"><i class="feather icon-log-out"></i></span><span class="pcoded-mtext">Logout</span></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- [ navigation menu ] end -->

    @include ('admin.common.menu')