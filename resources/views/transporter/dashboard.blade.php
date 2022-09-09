@section('css-section')

<style type="text/css">
    
input[type="checkbox"].switch_1{
    font-size: 30px;
    -webkit-appearance: none;
       -moz-appearance: none;
            appearance: none;
    width: 3.5em;
    height: 1.5em;
    background: #ddd;
    border-radius: 3em;
    position: relative;
    cursor: pointer;
    outline: none;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
  }
  
  input[type="checkbox"].switch_1:checked{
    background: #00874A;
  }
  
  input[type="checkbox"].switch_1:after{
    position: absolute;
    content: "";
    width: 1.5em;
    height: 1.5em;
    border-radius: 50%;
    background: #fff;
    -webkit-box-shadow: 0 0 .25em rgba(0,0,0,.3);
            box-shadow: 0 0 .25em rgba(0,0,0,.3);
    -webkit-transform: scale(.7);
            transform: scale(.7);
    left: 0;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
  }
  
  input[type="checkbox"].switch_1:checked:after{
    left: calc(100% - 1.5em);
  }

  </style> 


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
                                <h2>Dashboard</h2>
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

                                <!-- <ul class="navbar-nav ml-auto col-md-2 text-right">
                                     <li>
                                        <select class="form-control" style="margin-bottom: 15px;" id="filter_type">
                                                <option value="0">24 hours</option>
                                                <option value="1">Last 7 Days</option>
                                                <option value="2">One Month</option>
                                                <option value="3">One year</option>
                                                <option value="4" selected>Lifetime</option>
                                        </select>
                                    </li>
                                </ul> -->

                            <div class="row">

                                <div class="col-md-3">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc">Shipments Request</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="total_request_shipment" >0</p>
                                                    </div>

                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('transporterShowRequestList')}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc">Pending Driver Assignment</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="pending_assign_driver_count" >0</p>
                                                    </div>

                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('transporterShowRequestList',['filter_type' => '3'])}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                	<div class="page-header-title">
                                        				<h5 class="m-b-10 dash-acc">Accepted Shipments</h5>
                                    				</div>
                                    				<div class="page-header-title">
                                        				<p class="m-b-10 dash-acc-ship-no" id="total_accepted_shipment" >0</p>
                                    				</div>

                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('transporterShowActiveShipment',['filter_type' => '1'])}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc">Bidded Trip Count</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="total_bidded_trip_count" >0</p>
                                                    </div>

                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('transporterShowRequestList',['filter_type' => '1'])}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3 mt-15">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc">Cancelled Shipments</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="total_cancelled_shipment" >0</p>
                                                    </div>

                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('transporterShowPastShipment',['filter_type' => '2'])}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="col-md-3 mt-15">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc">Total Drivers</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="total_driver" >2</p>
                                                    </div>

                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('transporterShowDriverList')}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
 -->

                                <!-- <div class="col-md-3 mt-15">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc">Pending Award Acceptance</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="pending_accepted_award_count" >2</p>
                                                    </div>

                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('transporterShowRequestList',['filter_type' => '2'])}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- <div class="col-md-3 mt-15">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc"> Reported Shipments</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="total_reported_shipment" >02</p>
                                                    </div>

                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('transporterShowReportShipment')}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                 <div class="col-md-11">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10 col-md-11-ac">Active Shipments</h5>
                                    </div>
                                </div>

                            @if($active_list != null && $active_list != '[]') 
                                @foreach($active_list as $key => $value)
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

                                                        
                                                        <p class="pending-lab pick-p disp-cont">
                                                            <br>
                                                        @if($value['status'] == '0')
                                                        Bidded
                                                        @elseif($value['status'] == '1')
                                                        Accepted
                                                        @elseif($value['status'] == '2')
                                                        On The Way
                                                        @elseif($value['status'] == '4')
                                                        Arrived
                                                        @elseif($value['status'] == '5')
                                                        Shipment Started
                                                        @elseif($value['status'] == '8')
                                                        Arrived at Drop off Location
                                                        @endif
                                                        </p>
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
                                                        <a href="{{route('transporterShowActiveShipmentDetails',array('id'=>$value['shipment_id']))}}">View More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                    <h2> Active Shipment List Is Empty </h2>
                            @endif
                                </div>
                                <!-- [ Main Content ] end -->
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


@section('js-section')  

<script type="text/javascript">
  $(document).ready(function(){  

        DashboardFilter();
    });

        $("#filter_type").change(function() {  
            
            DashboardFilter();
            
        });


        function DashboardFilter(){
            
            var filter_type = $('#filter_type').val();

            $.ajax({
                url:"{{route('transporterFilterDashboard')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",filter_type:filter_type},
                success:function(response){
                    
                    res = JSON.parse(response);
                    data = res.result;
                    if(res.success == '1'){
                        
                        $('#total_accepted_shipment').html(data.total_accepted_shipment);
                        $('#total_cancelled_shipment').html(data.total_cancelled_shipment);
                        $('#total_reported_shipment').html(data.total_reported_shipment);
                        $('#total_request_shipment').html(data.total_request_shipment);
                        $('#total_driver').html(data.total_driver);
                        $('#pending_assign_driver_count').html(data.pending_assign_driver_count);
                        $('#pending_accepted_award_count').html(data.pending_accepted_award_count);
                        $('#total_bidded_trip_count').html(data.total_bidded_trip_count);
                    }
                }
            });
        }

</script>
@endsection
    