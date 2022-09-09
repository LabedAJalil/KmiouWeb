@extends('layouts.app')
@section('content')

<style>
.who-we-are-p{
max-width: 80%;
margin-left: auto;
margin-right: auto;
margin-top: 10px;
}
.custm-shpr{
margin-left: 25px;
margin-top: -2px;
}
.main-header .main-menu ul li a{
padding: 39px 6px;
}
.our-info-area .single-info{
display: flex;
align-items: center;
flex-direction: column;
}
.info-caption span{
text-align: center;
}

.info-caption p{
text-align: center;
}

.our-info-area .single-info .info-caption{
padding-left: 0px;
}
.story-ssc{
text-align: center;
color: #2c234d;
}
.suucc-story-title h2{
color: #2c234d;
}

.suucc-story-title p{
color: #2c234d;
}


.story-ssc h2{
color: #2c234d;
font-size: 40px;
}

.story-ssc p{
color: #00874A;
}

.team-area{
padding-bottom: 50px !important;
padding-top: 70px;
background: #f9f9f9;
}

.about-low-area.padding-bottom{
padding-top: 100px;
padding-bottom: 50px;
}

.sav-mon{
max-width: 800px;
margin-left: auto;
margin-right: auto;
}

.sav-mon p{
padding-right: 0px !important;
}

.sav-mon h2{
font-size: 45px;
}

.sav-mon i{
    width: 70px;
    height: 70px;
    background: #3c874b;
    margin-bottom: 20px;
    font-size: 40px;
    padding-top: 14px;
    color: #fff;
    border-radius: 50px;
}


</style>
<main>
    <!--? slider Area Start-->
    <div class="slider-area ">
        <div class="slider-active">
            <!-- Single Slider -->
            <div class="single-slider slider-height d-flex align-items-center">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-9 col-lg-9">
                            <div class="hero__caption">
                                <h1 >Safe & Reliable <span>Logistic</span> Solutions!</h1>
                            </div>
                            <!-- Hero form
                            <form action="#" class="search-box">
                                <div class="input-form">
                                    <input type="text" placeholder="Your Tracking ID">
                                </div>
                                <div class="search-form">
                                    <a href="#">Track & Trace</a>
                                </div>  
                            </form> 
                            Hero Pera
                            <div class="hero-pera">
                                <p>For order status inquiry</p>
                            </div> -->
                            <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="header-right-btn d-none d-lg-block ml-20">
                                    <a href="{{url('register')}}" class="btn header-btn"> Sign Up as a  Carrier </a>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="header-right-btn d-none d-lg-block ml-20">
                                    <a href="{{url('register')}}" class="trans-btn btn header-btn"> Sign Up as Shipper </a>
                                </div>
                            </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- slider Area End-->
    <!--? our info Start -->
    <div class="our-info-area pt-70 pb-40">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="single-info mb-30">
                        <div class="info-icon">
                            <span class="flaticon-place"></span>
                        </div>
                        <div class="info-caption">
                            <span>Tracking</span>
                            <p>Offering a unique tracking solution, we create flexibility by providing a visual overview of the supply chain for our shippers and carriers, allowing operational planning for all parties involved.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-info mb-30">
                        <div class="info-icon">
                            <span class="flaticon"><i class="fa fa-handshake-o" aria-hidden="true"></i></span>
                        </div>
                        <div class="info-caption">
                            <span>Service</span>
                            <p>Your dedicated contact person is at your disposal in the language of choice, working with you to develop a tailor-made transport solution.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-info mb-30">
                        <div class="info-icon">
                            <span class="flaticon-"><i class="fa fa-star" aria-hidden="true"></i></span>
                        </div>
                        <div class="info-caption">
                            <span>Quality</span>
                            <p>Top priority for both our shippers and carriers is the quality of our service. Our deep tech-integration enables full transparency of shipments and vehicles to guarantee highest service levels.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="single-info mb-30">
                        <div class="info-icon">
                            <span class="flaticon-"><i class="fa fa-money" aria-hidden="true"></i></span>
                        </div>
                        <div class="info-caption">
                            <span>Attractive conditions</span>
                            <p>We bypass middlemen in the market and directly connect shippers and carriers. Generating high transparency, we offer highly attractive financial conditions to both parties.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- our info End -->
    <!--? Categories Area Start -->
    <div class="categories-area section-padding30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Section Tittle -->
                    <div class="section-tittle text-center mb-80">
                        <h2>Who We Are</h2>
                        <p class="who-we-are-p">We are your digital land transportation partner . As Algerian pioneer digital logistic company, we offer our shippers access to our connected fleet of thousands of trucks and vehicles.</p>
                        <p class="who-we-are-p">by leveraging our inhouse technology we are taking the logistics in a new direction of transparency and efficiency to save money for our shippers and increase earnings for our transporters whilst reducing the carbon footprint.</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="single-cat text-center mb-50">
                        <div class="cat-icon">
                            <span class="flaticon-shipped"></span>
                        </div>
                        <div class="cat-cap">
                            <h5><a href="{{url('services')}}">Carriers</a></h5>
                            <p>Let's join forces and have you become a sennder carrier. <br> Click below to find out more.</p>
                            <a href="{{url('register')}}">Earn Money Now</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="single-cat text-center mb-50">
                        <div class="cat-icon">
                            <span class="flaticon-ship"></span>
                        </div>
                        <div class="cat-cap">
                            <h5><a href="{{url('services')}}">Shippers</a></h5>
                            <p>  Let us support you in daily challenge of allocating your FTL shipments. Click below to find out more.</p>
                            <a href="{{url('register')}}">Save Money Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Categories Area End -->
    <!--? contact-form start -->
    <!-- <section id="booktruck" class="contact-form-area section-bg  pt-115 pb-120 fix" data-background="{{asset('public/img/gallery/section_bg02.jpg')}}">
        <div class="container">
            <div class="row justify-content-end">
                <div class="col-xl-8 col-lg-9">
                    <div class="contact-form-wrapper">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section-tittle mb-50">
                                    <h2>Book New Shipment</h2>
                                </div>
                            </div>
                        </div>
                        <form action="#" class="contact-form">
                            <div class="row ">
                                <div class="col-lg-6 col-md-6">
                                    <div class="input-form">
                                        <label class="main-lable">Enter Pick-up Location</label>
                                        <input type="text" placeholder="Example: Dallas,Texs">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="input-form">
                                        <label class="main-lable">Enter Drop Location</label>
                                        <input type="text" placeholder="Example: New-your,NYC">
                                    </div>
                                </div>
                                <div class="map-info">
                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2957.61495542656!2d-71.11884837482297!3d42.37699974237274!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89e377427d7f0199%3A0x5937c65cee2427f0!2sHarvard%20University!5e0!3m2!1sen!2sin!4v1597051632747!5m2!1sen!2sin" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                                    </div>
                                <div class="select-truck">
                                    <div class="radio-wrapper mb-30 mt-15">
                                        <label>Choose Truck:</label>
                                    </div>
                                    <div class="truck-1 truck-sec">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1">
                                        <label class="custom-control-label-truck-img" for="customCheck1">
                                            <img class="truck-selc-img" src="{{asset('public/images/t1.png')}}">
                                        </label>
                                    </div>
                                    <div class="truck-2 truck-sec">
                                        <input type="checkbox" class="custom-control-input" id="customCheck2">
                                        <label class="custom-control-label-truck-img" for="customCheck2">
                                            <img class="truck-selc-img" src="{{asset('public/images/t2.png')}}">
                                        </label>
                                    </div>
                                    <div class="truck-3 truck-sec">
                                        <input type="checkbox" class="custom-control-input" id="customCheck3">
                                        <label class="custom-control-label-truck-img" for="customCheck3">
                                            <img class="truck-selc-img" src="{{asset('public/images/t3.png')}}">
                                        </label>
                                    </div>
                                    <div class="truck-4 truck-sec">
                                        <input type="checkbox" class="custom-control-input" id="customCheck4">
                                        <label class="custom-control-label-truck-img" for="customCheck4">
                                            <img class="truck-selc-img" src="{{asset('public/images/t4.png')}}">
                                        </label>
                                    </div>
                                    <div class="truck-5 truck-sec">
                                        <input type="checkbox" class="custom-control-input" id="customCheck5">
                                        <label class="custom-control-label-truck-img" for="customCheck5">
                                            <img class="truck-selc-img" src="{{asset('public/images/t5.png')}}">
                                        </label>
                                    </div>
                                </div>
                                </div>
                                <div class="col-lg-12">
                                    <a href="{{url('login')}}" class="request-truck-find submit-btn">Request a Truck</a>
                                </div>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- contact-form end -->




    <div class="team-area section-padding30">
        <div class="container">
            <div class="row justify-content-center">
                <div class="cl-xl-7 col-lg-8 col-md-10">
                    <!-- Section Tittle -->
                    <div class="section-tittle suucc-story-title text-center mb-70">
                        <h2>Our Success Story</h2>
                        <p>Key figures reflect our strong track record established by closely cooperating with our shippers and carriers.</p>
                    </div> 
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="story-ssc">
                        <h2> > 10,000 </h2>
                        <p>CONNECTED TRUCKS</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="story-ssc">
                        <h2> 3 </h2>
                        <p>OFFICES SERVING WORLDWIDE</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="story-ssc">
                        <h2> > 1200 </h2>
                        <p>CONNECTED TRUCKS</p>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="story-ssc">
                        <h2> > 350,000 </h2>
                        <p>COMPLETED FTL SHIPMENTS</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--? About Area Start -->
    <div class="about-low-area padding-bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 text-center">
                    <div class="about-caption mb-50">
                        <!-- Section Tittle -->
                        <div class="section-tittle mb-35">
                            <span>Want access to consistent deliveries and high quality loads?</span>
                            <h2>SAVE MONEY AND TIME BY PARTNERING WITH KMIOU</h2>
                        </div>
                        <div class="row sav-mon">
                            <div class="col-md-4">
                                <i class="fas fa-clock"></i>
                                <h4>Get Paid in 20 days</h4>
                                <!-- <p>Manage your cash flow and enjoy full flexibility with reliable payment terms of less than 3 days.</p> -->
                            </div>

                            <div class="col-md-4">
                                <i class=" fas fa-percent"></i>
                                <h4>Price reduction on tires and lubricant</h4>
                                <!-- <p>Year-round cash back offers on MICHELIN brand tires for commercial vehicles across Europe.</p> -->
                            </div>

                            <div class="col-md-4">
                                <i class="far fa-credit-card"></i>
                                <h4>Exclusive fuel card offers</h4>
                                <!-- <p>Gasoline discounts with no minimum volume at 100,000+ acceptance points across Europe.</p> -->
                            </div>
                        </div>
                        <a href="{{url('about')}}" class="btn mt-4">More About Us</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    





<!--     <div class="testimonial-area testimonial-padding section-bg" data-background="{{asset('public/img/gallery/section_bg04.jpg')}}">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-xl-7 col-lg-7">
                    <div class="section-tittle section-tittle2 mb-25">
                        <span>Clients Testimonials</span>
                        <h2>What Our Clients Say!</h2>
                    </div> 
                    <div class="h1-testimonial-active mb-70">
                        <div class="single-testimonial ">
                            <div class="testimonial-caption ">
                                <div class="testimonial-top-cap">
                                    <p>Srem ipsum adolor dfsit amet, consectetur adipiscing elit, sed dox beiusmod tempor incci didunt ut labore et dolore magna aliqua. Quis cipsucm suspendisse ultrices gravida. Risus commodo vivercra maecenas accumsan lac.</p>
                                </div>
                                <div class="testimonial-founder d-flex align-items-center">
                                    <div class="founder-img">
                                        <img src="{{asset('public/img/gallery/Homepage_testi.png')}}" alt="">
                                    </div>
                                    <div class="founder-text">
                                        <span>Jhaon smith</span>
                                        <p>Creative designer</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="single-testimonial ">
                            <div class="testimonial-caption ">
                                <div class="testimonial-top-cap">
                                    <p>Srem ipsum adolor dfsit amet, consectetur adipiscing elit, sed dox beiusmod tempor incci didunt ut labore et dolore magna aliqua. Quis cipsucm suspendisse ultrices gravida. Risus commodo vivercra maecenas accumsan lac.</p>
                                </div>
                                <div class="testimonial-founder d-flex align-items-center">
                                    <div class="founder-img">
                                        <img src="{{asset('public/img/gallery/Homepage_testi.png')}}" alt="">
                                    </div>
                                    <div class="founder-text">
                                        <span>Jhaon smith</span>
                                        <p>Creative designer</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-5 col-md-8">
                    <div class="testimonial-form text-center">
                        <h3>Always listening, always understanding.</h3>
                        <input type="text" placeholder="Incoterms">
                        <button name="submit" class="submit-btn">Request a Quote</button>
                    </div>
                </div>
            </div>
        </div>
    </div> -->




<!--     <div class="home-blog-area section-padding30">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-tittle text-center mb-70">
                        <span>Our Recent news</span>
                        <h2>Blog</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="home-blog-single mb-30">
                        <div class="blog-img-cap">
                            <div class="blog-img">
                                <img src="{{asset('public/img/gallery/blog01.png')}}" alt="">
                            </div>
                        </div>
                        <div class="blog-caption">
                            <div class="blog-date text-center">
                                <span>27</span>
                                <p>SEP</p>
                            </div>
                            <div class="blog-cap">
                                <ul>
                                    <li><a href="#"><i class="ti-user"></i> Jessica Temphers</a></li>
                                    <li><a href="#"><i class="ti-comment-alt"></i> 12</a></li>
                                </ul>
                                <h3><a href="{{url('blog_details')}}">Here’s what you should know before.</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="home-blog-single mb-30">
                        <div class="blog-img-cap">
                            <div class="blog-img">
                                <img src="{{asset('public/img/gallery/blog1.png')}}" alt="">
                            </div>
                        </div>
                        <div class="blog-caption">
                            <div class="blog-date text-center">
                                <span>27</span>
                                <p>SEP</p>
                            </div>
                            <div class="blog-cap">
                                <ul>
                                    <li><a href="#"><i class="ti-user"></i> Jessica Temphers</a></li>
                                    <li><a href="#"><i class="ti-comment-alt"></i> 12</a></li>
                                </ul>
                                <h3><a href="{{url('blog_details')}}">Here’s what you should know before.</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="home-blog-single mb-30">
                        <div class="blog-img-cap">
                            <div class="blog-img">
                                <img src="{{asset('public/img/gallery/blog02.png')}}" alt="">
                            </div>
                        </div>
                        <div class="blog-caption">
                            <div class="blog-date text-center">
                                <span>27</span>
                                <p>SEP</p>
                            </div>
                            <div class="blog-cap">
                                <ul>
                                    <li><a href="#"><i class="ti-user"></i> Jessica Temphers</a></li>
                                    <li><a href="#"><i class="ti-comment-alt"></i> 12</a></li>
                                </ul>
                                <h3><a href="{{url('blog_details')}}">Here’s what you should know before.</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!-- Blog Area End -->
</main>
<!-- Scroll Up -->
@endsection

@section('js-section')
<script>
    $(document).ready(function(){

        window.location.href = 'https://www.kmiou.com/#googtrans(fr)';
    });
</script>
@endsection