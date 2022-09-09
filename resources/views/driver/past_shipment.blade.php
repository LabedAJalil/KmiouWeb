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
                                <h2>Past Shipment</h2>
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
                                    <select class="form-control" style="margin-bottom: 15px;" id="filter_type">
                                            <option value="0">All</option>
                                            <option value="1">Past Shipment</option>
                                            <option value="2">Cancelled Shipment</option>
                                            <option value="3">Reported Shipment</option>
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
                                     <input type="date" class="form-control" placeholder="Select From Date" name="from_date" id="from_date" style="margin-bottom: 15px;">
                                </li>
                            </ul>

                            <ul class="navbar-nav ml-auto col-md-3 text-right">
                            </ul>

                            <div class="row past_shipment_list">
                            
                            </div>

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

        RequestListFilter();

        var current_date = date('Y-m-d');
        $('#from_date').val(current_date);
    });

        $("#filter_type ,#arriving_city ,#departing_city, #from_date, #to_date").change(function() {  
            
            RequestListFilter();
            
        });


        function RequestListFilter(){
            
            var filter_type = $('#filter_type').val();
            var arriving_city = $('#arriving_city').val();
            var departing_city = $('#departing_city').val();
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $.ajax({
                url:"{{route('driverPastShipmentListFilter')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",filter_type:filter_type,arriving_city:arriving_city,departing_city:departing_city,from_date:from_date,to_date:to_date},
                success:function(response){
                    
                    res = JSON.parse(response);
                    data = res.result;
                    
                    if(res.success == '1'){
                        
                        $('.past_shipment_list').html("");

                        if(res.result.length >0){

                            $.each(res.result, function( k, v ) {

                                var profile_pic = ' src="{{asset("public/images/user.png")}}"';

                                if(v.shipper_profile_pic != null && v.shipper_profile_pic != ''){

                                    var profile_pic = ' src="'+v.shipper_profile_pic+'"';
                                }
                                

                                var status = v.status_string; 
                                var status_color = v.status_color; 
                                var details_screen = 'past'; 
                                    
                                    if(v.status == '6'){

                                      status_color = '#12D612';
                                      status = 'Delivered';
                                      details_screen = 'past'; 
                                    }else if(v.status == '3'){

                                      status_color = '#ed3709';
                                      status = 'Cancelled';
                                      details_screen = 'cancel'; 
                                    }else if(v.status == '7'){

                                      status_color = '#EF5163';
                                      status = 'Reported';
                                      details_screen = 'report'; 
                                    }

                                $('.past_shipment_list').append('<div class="col-md-4"> <div class="page-header-title"> <h5 class="m-b-10">Shipment ID : '+v.ship_id+'</h5> </div> <div class="card Recent-Users"> <div class="card-block px-0 py-3"> <div class="card-info-shipp"> <div class="user-img"> <img class="user-logo" '+profile_pic+' > <p class="user-name">'+v.shipper_first_name+' '+v.shipper_last_name+'</p> <p class="book-date">'+v.created_at+'</p>  <div class="pickup-info"> <p> '+v.amount+' DA</p> </div> <p class=" pick-p disp-cont" style="color:'+status_color+' !important;"> <br> '+status+' </p> </div> <div class="pickup-info"> <i class="fas fa-circle text-c-green f-10 m-r-15"></i>Pickup Location <p class="pick-p">'+v.pickup+'</p> </div> <div class="drop-info"> <i class="fas fa-circle text-c-red f-10 m-r-15"></i>Drop Location <p class="pick-p">'+v.drop+'</p> </div> <div class="view-more"> <a href="{{url("/driver/shipment/")}}/'+details_screen+'/'+v.shipment_id+'/details">View More</a> </div> </div> </div> </div> </div>');

                            });
                        }else{
                         
                                $('.past_shipment_list').append('<h2> Shipment List Is Empty </h2>');
                        }
                    }
                }
            });
        }

</script>
@endsection
