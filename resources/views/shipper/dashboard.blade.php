@extends('shipper.common.master')
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
                              
                            <!-- [ Main Content ] start -->
                            
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
                                <!-- <div class="col-md-4 mb-5">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc">Instant Quote Shipments</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="instant_quote_count" >21</p>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
 -->
                                <div class="col-md-4 mb-5">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc">Fixed Shipments</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="fixed_shipment_count" >0</p>
                                                    </div>
                                                     <div class="view-more">
                                                        <a href="{{route('shipperShowActiveShipment',['filter_type' => '2'])}}">View More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-5">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc"> Bidded Shipments</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="auction_shipment_count" >0</p>
                                                    </div>
                                                     <div class="view-more">
                                                        <a href="{{route('shipperShowActiveShipment',['filter_type' => '3'])}}">View More</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
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
                                                    <a href="{{route('shipperShowActiveShipment',['filter_type' => '1'])}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
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
                                                    <a href="{{route('shipperShowCancelShipment')}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="col-md-4">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc"> Reported Shipments</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="total_reported_shipment" >0</p>
                                                    </div>

                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('shipperShowReportShipment')}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-4">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="user-img">
                                                    <div class="page-header-title">
                                                        <h5 class="m-b-10 dash-acc"> Received Offers</h5>
                                                    </div>
                                                    <div class="page-header-title">
                                                        <p class="m-b-10 dash-acc-ship-no" id="received_offer_count" >0</p>
                                                    </div>

                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('shipperShowActiveShipment',['filter_type' => '4'])}}">View More</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                

                            <div class="col-md-11">
                                <div class="page-header-title">
                                    <h5 class="m-b-10 col-md-11-ac">Active Shipments</h5>
                                </div>
                            </div>

                            @if($active_list != null && $active_list != '[]') 
                                @foreach($active_list as $key => $value)
                                 <div class="col-md-4"> 
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Shipment ID :  {{$value['shipment_id']}}</h5>
                                    </div>
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp mt-15">
                                                <div class="user-img">
                                                    @if($value['status_string'] != '')
                                                    <div class="pickup-info">
                                                        <p class="pending-lab pick-p" style="color:#00874A">
                                                        {{$value['status_string']}}
                                                        </p>
                                                    </div>
                                                    @else
                                                    <div class="pickup-info">
                                                        <p class="pending-lab pick-p" style="color:#FFC70D">Schedule For Delivery
                                                        </p>
                                                    </div>
                                                    @endif
                                                    <p class="book-date">{{date('d-M-Y h:i A',strtotime($value['created_at']))}} </p>
                                                </div>
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <div class="pickup-info">
                                                    <i class="fas fa-circle text-c-green f-10 m-r-15"></i>Pickup Location
                                                    <p class="pick-p">{{$value['pickup']}}</p>
                                                </div>
                                                <div class="drop-info">
                                                    <i class="fas fa-circle text-c-red f-10 m-r-15"></i>Drop Location
                                                    <p class="pick-p">{{$value['drop']}}</p>
                                                </div>
                                                <div class="view-more">
                                                    <a href="{{route('shipperShowActiveShipmentDetails',array('id'=>$value['shipment_id']))}}">View More</a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
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
                url:"{{route('shipperFilterDashboard')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",filter_type:filter_type},
                success:function(response){
                    
                    res = JSON.parse(response);
                    data = res.result;
                    if(res.success == '1'){
                        
                        $('#instant_quote_count').html(data.instant_quote_count);
                        $('#fixed_shipment_count').html(data.fixed_shipment_count);
                        $('#auction_shipment_count').html(data.auction_shipment_count);
                        $('#total_accepted_shipment').html(data.total_accepted_shipment);
                        $('#total_cancelled_shipment').html(data.total_cancelled_shipment);
                        $('#total_reported_shipment').html(data.total_reported_shipment);
                        $('#received_offer_count').html(data.received_offer_count);
                        
                    }
                }
            });
        }

</script>
@endsection
