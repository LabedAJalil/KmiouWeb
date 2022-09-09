@extends('transporter.common.master')
@section('main-content')

<main>
        <!--? slider Area Start-->
        <div class="slider-area ">
            <div class="single-slider hero-overly slider-height2 d-flex align-items-center" data-background="{{asset('public/img/hero/about.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap">
                                <h2>Reported Shipment</h2>
                              <!--   <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Contact</a></li> 
                                    </ol>
                                </nav> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- slider Area End-->
        <!-- ================ contact section start ================= -->
        <section class="contact-section">
            <div class="container">
                <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->

                             <!-- Alert Message -->  
                              <div class="flash-message">
                                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                  @if(Session::has('alert-' . $msg))
                                  <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                  {{ Session::forget('alert-' . $msg) }}
                                   {{ Session::save() }}
                                  @endif
                                @endforeach
                              </div>
                            <!-- Alert Message -->

                            <div class="row">
                        @if($report_list != null && $report_list != '[]') 
                            @foreach($report_list as $key => $value)
                                <div class="col-md-4">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Shipment ID : {{$value['ship_id']}}</h5>
                                    </div>
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <img class="user-logo" 
                                                    @if($value['shipper_profile_pic'] != null && $value['shipper_profile_pic'] != '')
                                                    src="{{$value['shipper_profile_pic']}}"
                                                    @else
                                                    src="{{asset('public/images/user.png')}}"
                                                    @endif
                                                    >
                                                    <p class="user-name">{{$value['shipper_first_name']}} {{$value['shipper_last_name']}} </p>
                                                    <p class="book-date">{{date('d-M-Y h:i A',strtotime($value['created_at']))}} </p>
                                                </div>
                                                <div class="pickup-info"> 
                                                    <p> {{$value['amount']}} DA</p> 
                                                </div> 
                                                <div class="pickup-info">
                                                    <i class="fas fa-circle text-c-green f-10 m-r-15"></i>Pickup Location
                                                    <p class="pick-p">{{$value['pickup']}}</p>
                                                </div>
                                                <div class="drop-info">
                                                    <i class="fas fa-circle text-c-red f-10 m-r-15"></i>Drop Location
                                                    <p class="pick-p">{{$value['drop']}}</p>
                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('transporterShowReportShipmentDetails',array('id'=>$value['shipment_id']))}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            @else
                                <h2> Reported Shipment List Is Empty </h2>
                            @endif
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ================ contact section end ================= -->
    </main>

@endsection