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
                                <h2>
                                    @if($filter_type == '1')
                                        Accepted Shipment
                                    @elseif($filter_type == '2')
                                        Fixed Shipment
                                    @elseif($filter_type == '3')
                                        Bidded Shipment
                                    @elseif($filter_type == '4')
                                        Received Offers
                                    @else
                                        Active Shipment
                                    @endif
                                </h2>
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
                                <input type="hidden" id="filter_type" value="{{$filter_type}}">
                            <!-- <ul class="navbar-nav ml-auto col-md-3 text-right">
                                     <li>
                                        <select class="form-control" id="filter_type"
                                        @if($filter_type == '1' || $filter_type == '2' || $filter_type == '3' || $filter_type == '4' )
                                        style="display: none !important; margin-bottom: 15px;"
                                        @else
                                        style="margin-bottom: 15px;"
                                        @endif >
                                                <option value="0">All</option>
                                                <option value="9">Awaiting Bid</option>
                                                <option value="11">Bid Received</option>
                                                <option value="10">Bidder Awarded</option>
                                                <option value="12">Schedule for Delivery</option>
                                                <option value="1" <?php if($filter_type == '1'){echo ("selected");}?> >Accepted</option>
                                                <option value="2">On The Way</option>
                                                <option value="4">Arrived at Pickup Location</option>
                                                <option value="5">Shipment Started</option>
                                                <option value="8">Arrived at Drop off Location</option>
                                        </select>
                                    </li>
                                </ul> -->
                                <ul class="navbar-nav ml-auto col-md-3 text-right" style="float:right;">
                                    <li>
                                         <input type="text" class="form-control" placeholder="Select To Date"  onfocus="(this.type='date')" name="to_date" id="to_date" style="margin-bottom: 15px;">
                                    </li>
                                </ul>

                                <ul class="navbar-nav ml-auto col-md-3 text-right">
                                    <li>    
                                         <input type="date" class="form-control" placeholder="Select From Date" value="{{date('Y-m-d')}}" name="from_date" id="from_date" style="margin-bottom: 15px;">
                                    </li>
                                </ul>
                            
                            <div class="row active_shipment_list">

                           
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

        ActiveShipmentFilter();
    });

        $("#filter_type").change(function() {  
            
            ActiveShipmentFilter();
            
        });

        $("#from_date").change(function() {  
            
            ActiveShipmentFilter();
            
        });

        $("#to_date").change(function() {  
            
            ActiveShipmentFilter();
            
        });


        function ActiveShipmentFilter(){
            
            var filter_type = $('#filter_type').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url:"{{route('shipperActiveShipmentFilter')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",filter_type:filter_type,from_date:from_date,to_date:to_date},
                success:function(response){
                    
                    res = JSON.parse(response);
                    data = res.result;
                    
                    if(res.success == '1'){
                        
                        $('.active_shipment_list').html("");

                        if(res.result.length >0){

                            $.each(res.result, function( k, v ) {
                                    
                                var status = "";

                                if(v.status == '0'){
                                   
                                   if(v.quotation_type == '0'){

                                      if(v.bidder_count == '0'){

                                        status = 'Awaiting Bid';

                                      }else{

                                        if(v.bid_status == '1'){

                                          status = 'Bidder Awarded';

                                        }else if(v.bid_status == '0'){
                                         
                                          status = 'Bid Received';

                                        }
                                      }

                                    }else{

                                      status = "Waiting For Acceptance";
                                    }

                                }else if(v.status == '1'){

                                    status = "Accepted";

                                }else if(v.status == '2'){

                                    status = "On The Way";

                                }else if(v.status == '4'){

                                    status = "Arrived at Pickup Location";

                                }else if(v.status == '5'){

                                    status = "Shipment Started";

                                }else if(v.status == '8'){

                                    status = "Arrived at Drop off Location";
                                
                                }else if(v.status == '9'){

                                    status = "On The Way To PickUp";
                                }

                                if((filter_type == '9' && status != 'Awaiting Bid') || (filter_type == '10' && status != 'Bidder Awarded') || (filter_type == '11' && status != 'Bid Received') || (filter_type == '12' && status != 'Schedule for Delivery')|| (filter_type == '4' && status == 'Awaiting Bid')  ){
                                    return;
                                }

                                $('.active_shipment_list').append('<div class="col-md-4"> <div class="page-header-title"> <h5 class="m-b-10">Shipment ID : '+v.ship_id+'</h5> </div> <div class="card Recent-Users"> <div class="card-block px-0 py-3"> <div class="card-info-shipp"> <div class="user-img"> <p class="book-date">'+v.created_at+'</p> <p class="pending-lab pick-p disp-cont"> '+status+' </p> </div> <div class="pickup-info"> <i class="fas fa-circle text-c-green f-10 m-r-15"></i>Pickup Location <p class="pick-p">'+v.pickup+'</p> </div> <div class="drop-info"> <i class="fas fa-circle text-c-red f-10 m-r-15"></i>Drop Location <p class="pick-p">'+v.drop+'</p> </div> <div class="view-more"> <a href="{{url("/shipper/shipment/active")}}/'+v.shipment_id+'/details">View More</a> </div> </div> </div> </div> </div>');

                            });
                        }else{
                         
                                var is_accepted = "{{$filter_type}}";
                                
                                if(is_accepted == '1'){
                                    $('.active_shipment_list').append('<h2> Accepted Shipment List Is Empty </h2>');
                                }else if(is_accepted == '2'){
                                    $('.active_shipment_list').append('<h2> Fixed Shipment List Is Empty </h2>');
                                }else if(is_accepted == '3'){
                                    $('.active_shipment_list').append('<h2> Bidded Shipment List Is Empty </h2>');
                                }else if(is_accepted == '4'){
                                    $('.active_shipment_list').append('<h2> Received Offers List Is Empty </h2>');
                                }else{
                                    $('.active_shipment_list').append('<h2> Active Shipment List Is Empty </h2>');
                                }
                        }
                    }
                }
            });
        }

</script>
@endsection
