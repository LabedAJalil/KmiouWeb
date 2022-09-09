@extends('driver.common.master')
@section('main-content')

<main>
        <!--? slider Area Start-->
        <div class="slider-area ">
            <div class="single-slider hero-overly slider-height2 d-flex align-items-center" data-background="{{asset('public/img/hero/about.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap">
                                <h2>Active Shipment</h2>
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

                                <ul class="navbar-nav ml-auto col-md-3 text-right" style="float:right">
                                     <li>
                                        <select class="form-control" style="margin-bottom: 15px;" id="filter_type" style=>
                                                <option value="0">All</option>
                                                <option value="1" <?php if(isset($filter_type) && $filter_type == '1'){ echo "selected"; }?> >Accepted</option>
                                                <option value="2">On The Way</option>
                                                <option value="4">Arrived at Pickup Location</option>
                                                <option value="5">Shipment Started</option>
                                                <option value="8">Arrived at Drop off Location</option>
                                        </select>
                                    </li>
                                    </ul>

                                    <ul class="navbar-nav ml-auto col-md-3 text-right" style="float:right;">
                                         
                                        @if($is_single_driver == "1")
                                         <li>
                                            <select class="form-control" style="margin-bottom: 15px;" id="departing_city" name="departing_city">
                                            <option value="">Departing City</option>
                                            @foreach($city_list as $city)
                                            <option value="{{$city->city_name}}">{{$city->city_name}}</option>
                                            @endforeach
                                            </select>
                                        </li>
                                        @endif
                                        <li>
                                             <input type="text" class="form-control" placeholder="Select To Date"  onfocus="(this.type='date')" name="to_date" id="to_date" style="margin-bottom: 15px;">
                                        </li>
                                    </ul>

                                    <ul class="navbar-nav ml-auto col-md-3 text-right">
                                        @if($is_single_driver == "1")
                                         <li>
                                             <select class="form-control" style="margin-bottom: 15px;" id="arriving_city" name="arriving_city">
                                                <option value="">Arriving City</option>
                                                @foreach($city_list as $city)
                                                <option {{$city->city_name}}>{{$city->city_name}}</option>
                                                @endforeach
                                                </select>
                                        </li>
                                        @endif
                                        <li>    
                                             <input type="date" class="form-control" placeholder="Select From Date" value="{{date('Y-m-d')}}" name="from_date" id="from_date" style="margin-bottom: 15px;">
                                        </li>
                                    </ul>

                        <div class="row active_shipment_list">
                        
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

        $("#filter_type ,#arriving_city ,#departing_city,#from_date,#to_date").change(function() {  
            
            ActiveShipmentFilter();
            
        });


        function ActiveShipmentFilter(){
            
            var filter_type = $('#filter_type').val();
            var arriving_city = $('#arriving_city').val();
            var departing_city = $('#departing_city').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url:"{{route('driverActiveShipmentFilter')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",filter_type:filter_type,arriving_city:arriving_city,departing_city:departing_city,from_date:from_date,to_date:to_date},
                success:function(response){
                    
                    res = JSON.parse(response);
                    data = res.result;
                    
                    if(res.success == '1'){
                        
                        $('.active_shipment_list').html("");

                        if(res.result.length >0){

                            $.each(res.result, function( k, v ) {

                                var profile_pic = ' src="{{asset("public/images/user.png")}}"';

                                if(v.shipper_profile_pic != null && v.shipper_profile_pic != ''){

                                    var profile_pic = ' src="'+v.shipper_profile_pic+'"';
                                }
                                    
                                var status = "";

                                if(v.status == '0'){

                                    status = "Schedule for Delivery";

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
                                }

                                $('.active_shipment_list').append('<div class="col-md-4"> <div class="page-header-title"> <h5 class="m-b-10">Shipment ID : '+v.ship_id+'</h5> </div> <div class="card Recent-Users"> <div class="card-block px-0 py-3"> <div class="card-info-shipp"> <div class="user-img"> <img class="user-logo" '+profile_pic+' > <p class="user-name">'+v.shipper_first_name+' '+v.shipper_last_name+'</p> <p class="book-date">'+v.created_at+'</p> <p class="pending-lab pick-p disp-cont"> '+status+' </p> </div> <div class="pickup-info"> <i class="fas fa-circle text-c-green f-10 m-r-15"></i>Pickup Location <p class="pick-p">'+v.pickup+'</p> </div> <div class="drop-info"> <i class="fas fa-circle text-c-red f-10 m-r-15"></i>Drop Location <p class="pick-p">'+v.drop+'</p> </div> <div class="view-more"> <a href="{{url("/driver/shipment/active")}}/'+v.shipment_id+'/details">View More</a> </div> </div> </div> </div> </div>');

                            });
                        }else{
                         
                                $('.active_shipment_list').append('<h2> Active Shipment List Is Empty </h2>');
                        }
                    }
                }
            });
        }

</script>
@endsection
