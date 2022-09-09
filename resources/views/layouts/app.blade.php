<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>KMIOU</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="manifest" href="webmanifest"> -->
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
    <link rel="stylesheet" href="{{asset('public/css/parsley.css')}}">
    
    <!-- Favicon icon -->
    <link rel="icon" href="{{asset('public/admin/images/favicon.png')}}" type="image/x-icon">
    
    <link rel="stylesheet" href="{{asset('public/css/bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('public/css/responsive.bootstrap4.min.css')}}">

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
<style>
.for-hd-btn{
padding: 25px 15px !important;
}
.for-btn-regis{
margin-right: 20px;
}
.main-header .main-menu{
margin-right: 10px;
}
.translated-ltr{

}
</style>
        <!-- Header Start -->
       <div class="header-area">
            <div class="main-header ">
                <div class="header-top d-none d-lg-block">
                   <div class="container">
                       <div class="col-xl-12">
                            <div class="row d-flex justify-content-between align-items-center">
                                <div class="header-info-left">
                                    <ul>     
                                        <li>Phone: +213 21 49 20 12</li>
                                        <li>Email: admin@kmiou.com</li>
                                    </ul>
                                </div>
                                <div class="header-info-right">
                                    <ul class="header-social">    
                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                       <li> <a href="#"><i class="fab fa-google-plus-g"></i></a></li>
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
                                    <a href="{{url('/')}}"><img src="{{asset('public/img/logo/logo.png')}}" alt=""></a>
                                </div>
                            </div>
                            <div class="col-xl-10 col-lg-10">
                                <div class="menu-wrapper  d-flex align-items-center justify-content-end">
                                    <!-- Main-menu -->
                                    <div class="main-menu d-none d-lg-block">
                                        <nav> 
                                            <ul id="navigation">                                                                                          
                                                <li><a href="{{url('/')}}">Home</a></li>
                                                <li><a href="{{url('about')}}">About</a></li>
                                                <li><a href="{{url('services')}}">Services</a></li>
                                                <!-- <li><a href="javascript:void(0);">Blog</a>
                                                    <ul class="submenu">
                                                        <li><a href="{{url('blog')}}">Blog</a></li>
                                                       <li><a href="{{url('blog_details')}}">Blog Details</a></li>
                                                        <li><a href="{{url('elements')}}">Element</a></li>
                                                    </ul>
                                                </li> -->
                                                <!-- <li><a href="{{url('privacy_policy')}}">Privacy Policy</a></li>
                                                <li><a href="{{url('terms_cond')}}">Terms & Cond</a></li> -->
                                                <li><a href="{{url('contact')}}">Contact</a></li>
                                                <li><a href="{{url('login')}}">Login</a></li>
                                            </ul>
                                        </nav>
                                    </div>
                                    <!-- Header-btn -->
                                    <div class="header-right-btn d-none d-lg-block ml-20">
                                        <a href="{{url('register')}}" class="btn for-btn-regis for-hd-btn header-btn">For Carriers</a>
                                        <a href="{{url('register')}}" class="btn for-hd-btn header-btn">For Shippers</a>
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

@yield('content')

 <footer>
    <!--? Footer Start-->
    <div class="footer-area footer-bg">
        <div class="container">
            <div class="footer-top footer-padding">
                <!-- footer Heading -->
                <div class="footer-heading">
                    <div class="row justify-content-between">
                        <div class="col-xl-6 col-lg-8 col-md-8">
                            <div class="wantToWork-caption wantToWork-caption2">
                                <h2>We Understand The Importance Approaching Each Work!</h2>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-4">
                            <span class="contact-number f-right">+ 213 21 49 20 12</span>
                        </div>
                    </div>
                </div>
                <!-- Footer Menu -->
                <div class="row d-flex justify-content-between">
                    <div class="col-xl-3 col-lg-4 col-md-5 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <!-- logo -->
                            <div class="footer-logo">
                                <a href="{{url('/')}}"><img src="{{asset('public/img/logo/logo2_footer.png')}}" alt=""></a>
                            </div>
                        
                            <!-- Footer Social -->
                            <div class="footer-social ">
                                <a href="https://www.facebook.com"><i class="fab fa-facebook-f"></i></a>
                                <a href=""><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fas fa-globe"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>INFO</h4>
                                <ul>
                                    <li><a href="#">About Us</a></li>
                                    <li><a href="#">Company</a></li>
                                    <li><a href="#"> Press & Blog</a></li>
                                    <li><a href="{{url('privacy_policy')}}">Privacy Policy</a></li>
                                    <li><a href="{{url('terms_cond')}}">Terms & Cond</a></li> 
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>Open hour</h4>
                                <ul>
                                    <li><a href="#">Sunday to Friday <br> 08am to 06pm</a></li>
                                    <li><a href="mailto:admin@kmiou.com">For 24/7 Contact <br> admin@kmiou.com</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="single-footer-caption mb-50">
                            <div class="footer-tittle">
                                <h4>CONTACT</h4>
                                <ul>
                                    <li><p style="margin-bottom:0px; color:#868c98;">+ 213 21 49 20 12</p></li>
                                    <li><p style="margin-bottom:0px; color:#868c98;">admin@kmiou.com</p></li>
                                    <li><p style="margin-bottom:0px; color:#868c98;">Residence Chabani Val d’Hydra Algiers Algeria 🇩🇿</p></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="row d-flex align-items-center">
                    <div class="col-lg-12">
                        <div class="footer-copy-right text-center">
                            <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
  Copyright &copy;2020 KMIOU, All rights reserved.
  <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End-->
</footer>
<!-- Scroll Up -->
<div id="back-top" >
    <a title="Go to Top" href="#"> <i class="fas fa-level-up-alt"></i></a>
</div>

    <!-- JS here -->

    <script src="https://use.fontawesome.com/73acb8139d.js"></script>


    <script src="{{asset('public/js/vendor/modernizr-3.5.0.min.js')}}"></script>
    <!-- Jquery, Popper, Bootstrap -->
    <script src="{{asset('public/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('public/js/popper.min.js')}}"></script>
    <script src="{{asset('public/js/bootstrap.min.js')}}"></script>
    <!-- Jquery Mobile Menu -->
    <script src="{{asset('public/js/jquery.slicknav.min.js')}}"></script>

    <!-- Jquery Slick , Owl-Carousel Plugins -->
    <script src="{{asset('public/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('public/js/slick.min.js')}}"></script>
    <!-- One Page, Animated-HeadLin -->
    <script src="{{asset('public/js/wow.min.js')}}"></script>
    <script src="{{asset('public/js/animated.headline.js')}}"></script>
    <script src="{{asset('public/js/jquery.magnific-popup.js')}}"></script>

    <!-- Nice-select, sticky -->
    <script src="{{asset('public/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{asset('public/js/jquery.sticky.js')}}"></script>
    
    <!-- contact js -->
    <script src="{{asset('public/js/contact.js')}}"></script>
    <script src="{{asset('public/js/jquery.form.js')}}"></script>
    <script src="{{asset('public/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('public/js/mail-script.js')}}"></script>
    <script src="{{asset('public/js/jquery.ajaxchimp.min.js')}}"></script>
    
    <!-- Jquery Plugins, main Jquery -->    
    <script src="{{asset('public/js/plugins.js')}}"></script>
    <script src="{{asset('public/js/main.js')}}"></script>

    
    <script src="{{asset('public/js/google_place_api.js')}}"></script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script src="{{asset('public/js/vendor-all.min.js')}}"></script>    

    <script src="{{asset('public/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('public/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('public/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('public/js/responsive.bootstrap4.min.js')}}"></script>

    
    <script src="{{asset('public/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

    <script src="{{asset('public/js/pcoded.min.js')}}"></script>  

    <script type="text/javascript">
	/* ------------------ for remove google translate header ----------------*/
    $(document).ready(function(){
    	
    	 $('.goog-close-link').trigger("click");
	 }); 

	</script>
    
    @yield('js-section')


</body>
</html>