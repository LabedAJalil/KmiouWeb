@section('css-section')

<style>
.user-name-info{
display: block;
}
.add-driver{
background: #3c874b;
color: white;
padding: 10px 20px;
border-radius: 5px;
margin-left: 15px;
}
.add-driver:hover{
color: white;
}
.card-info-shipp{
width: 100%;
}
.card-card{
text-align: right;
}
.send-req{
background: #3c874b;
color: white;
float: right;
margin-top: 10px;
padding: 5px 10px;
border-radius: 3px;
}
.send-req:hover{
color: white;
}
.scroll-md{
max-height: 400px;
overflow: hidden;
overflow-y: auto;
}

.upload-doc{
    border-radius: 10px;
    max-width: 100px;
    width: 60px;
    height: 60px;
    border: 2px solid #b5b5b5;
}

</style>

@endsection

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
                                <h2>Shipment Details</h2>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Shipment ID : {{$details[0]['ship_id']}}</h5>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                
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

                              @if($details[0]['truck'] != null && $details[0]['truck'] != '[]')
                                 <ul class="navbar-nav ml-auto col-md-3 text-center">
                                     <li>
                                        <select class="form-control" style="margin-bottom: 15px;" id="truck_filter_type">
                                                @foreach($details[0]['truck'] as $truck)

                                                    <option value="{{$truck['shipment_id']}}" <?php if(strcmp($id,$truck['shipment_id']) == '0'){ echo("selected");} if($truck['title'] == 'All'){ echo('id="all_truck_option"');} ?>  >{{$truck['title']}}</option>
                                                
                                                @endforeach
                                                
                                        </select>
                                    </li>
                                </ul>
                                @endif

                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="book-date-sec">
                                                    <p class="book-date">{{date('d-M-Y h:i A',strtotime($details[0]['created_at']))}}</p>
                                                </div>
                                                <div class="user-img">
                                                    <img class="user-logo" 
                                                    @if($details[0]['shipper_profile_pic'] != null && $details[0]['shipper_profile_pic'] != '')
                                                    src="{{$details[0]['shipper_profile_pic']}}"
                                                    @else
                                                    src="{{asset('public/images/user.png')}}"
                                                    @endif
                                                    >
                                                    <p class="user-name"> &nbsp; {{$details[0]['shipper_first_name']}}  {{$details[0]['shipper_last_name']}}</p>
                                                </div>
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <!-- <div class="pickup-info">
                                                    <label class="det-label">Mobile No: </label>
                                                    <p class="pick-p">+1 621-12-123</p>
                                                </div> -->
                                                
                                                <div class="pickup-info">

                                                @if(($details[0]['quotation_type'] == '1' && $details[0]['driver_id'] == '0') OR ($details[0]['is_bid_selected'] == '1' && $details[0]['bid_amount'] != '0' && $details[0]['driver_id'] == '0') OR ($details[0]['quotation_type'] == '2' && $details[0]['driver_id'] == '0') ) 

                                                    <label class="det-label">Driver info: </label>
                                                        <div class="user-img assign_driver_div">
                                                        <img class="plus-logo" src="{{asset('public/images/plus.png')}}">
                                                        <a data-toggle="modal" class="assign_driver_list" data-target="#exampleModalSelectDriver" href="javascript:void(0);" >Assign Driver</a>
                                                      
                                                        </div>


                                                    <input type="hidden" name="assign_driver_id" id="assign_driver_id" value="0">
                                                @elseif($details[0]['driver_id'] != '0')
                                                
                                                    <label class="det-label">Driver info: </label>
                                                    <div class="user-img">
                                                    <img class="user-logo"
                                                    @if($details[0]['driver_profile_pic'] != null && $details[0]['driver_profile_pic'] != '')
                                                    src="{{$details[0]['driver_profile_pic']}}"
                                                    @else
                                                    src="{{asset('public/images/user.png')}}"
                                                    @endif
                                                    >
                                                    <p class="user-name"> &nbsp; {{$details[0]['driver_first_name']}}  {{$details[0]['driver_last_name']}}</p>

                                                </div>
                                                @endif
                                                </div>
                                                
                                                <div class="pickup-info driver_info_div" style="display: none;">
                                                    <div class="user-img">
                                                    <img class="user-logo" id="driver_profile_pic" 
                                                    src="{{asset('public/images/user.png')}}"
                                                    >
                                                    <p class="user-name" id="driver_user_name"> &nbsp;</p>

                                                    <a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModalSelectDriver" class="assign_driver_list"> &nbsp;&nbsp; Change Driver </a>
                                                </div>
                                                </div>

                                                <div class="pickup-info">
                                                    <label class="det-label">Truck Info: </label>
                                                    <div class="user-img">
                                                        <img class="user-logo" 
                                                        @if($details[0]['truck_img'] != null && $details[0]['truck_img'] != '')
                                                        src="{{$details[0]['truck_img']}}"
                                                        @else
                                                        src="{{asset('public/images/user.png')}}"
                                                        @endif
                                                        >
                                                        <p class="user-name"> &nbsp; {{$details[0]['truck_name']}}</p>
                                                    </div>
                                                </div>

                                                <div class="pickup-info">
                                                    <label class="det-label">Pickup Location: </label>
                                                    <p class="pick-p">{{$details[0]['pickup']}}</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Drop Location: </label>
                                                    <p class="pick-p">{{$details[0]['drop']}}</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Shipment ID: </label>
                                                    <p class="pick-p">{{$details[0]['ship_id']}}</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Date & Time: </label>
                                                    <p class="pick-p">{{$details[0]['pickup_date']}}</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Service Type: </label>
                                                    <p class="pick-p">Semi Trailer 300</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Number of Vehicle : </label>
                                                    <p class="pick-p">{{$details[0]['no_of_vehicle']}}</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Goods Type: </label>
                                                    <p class="pick-p">
                                                        {{$details[0]['goods_type_name']}}
                                                    </p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Weight: </label>
                                                    <p class="pick-p">{{$details[0]['weight']}} 
                                                    @if($details[0]['weight_type'] == '0')
                                                    Kg
                                                    @elseif($details[0]['weight_type'] == '1')
                                                    Ton
                                                    @endif</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Instruction: </label>
                                                    <p class="pick-p"> {{$details[0]['info']}} </p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Shipping Documents: </label>
                                                     @foreach($doc as $doc)
                                                        <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$doc}}"></a>
                                                    @endforeach
                                                </div>
                                            
                                            @if($details[0]['status'] == '0')

                                                @if($details[0]['quotation_type'] == '0' && $details[0]['is_bid_selected'] == '0')

                                                    @if($details[0]['bid_amount'] == '0')
                                                        <div class="pickup-info edit-info">
                                                            <label class="det-label">Bid Amount: </label>
                                                            <input type="number" class="form-control edit-pro bid_amount" placeholder="Enter bid amount">
                                                        </div>
                                                        <div class="full-width text-center">
                                                            <a href="javascript:void(0)" class="btn btn-primary mb-4 place_new_bid" data-shipment_id="{{$details[0]['shipment_id']}}">Place Bid</a>
                                                        </div>
                                                    @else
                                                        <div class="pickup-info edit-info">
                                                            <label class="det-label">Bid Amount: </label>
                                                            <input type="number" class="form-control edit-pro bid_amount" id="bid_amount" value="{{$details[0]['bid_amount']}}" placeholder="Enter bid amount">
                                                        </div>

                                                        <div class="full-width text-center">
                                                            <a href="javascript:void(0)" class="btn btn-primary mb-2 update_bid" data-shipment_id="{{$details[0]['shipment_id']}}" data-type="0" >Update Bid</a>
                                                            <a href="javascript:void(0)" class="btn btn-danger mb-2 update_bid" data-shipment_id="{{$details[0]['shipment_id']}}" data-type="1">Cancel Bid</a>
                                                        </div>
                                                    @endif
                                            

                                                @elseif($details[0]['quotation_type'] == '1' OR ($details[0]['is_bid_selected'] == '1' && $details[0]['bid_amount'] != '0') OR ($details[0]['is_bid_selected'] == '1' && $details[0]['quotation_type'] == '2') )

                                                @if($details[0]['quotation_type'] == '0')
                                                <div class="drop-info">
                                                    <label class="det-label">Bid Amount: </label>
                                                    <p class="pick-p">{{$details[0]['bid_amount']}} 
                                                    </p>
                                                </div>
                                                @endif
                                                <div class="full-width text-center">
                                                    @if($details[0]['quotation_type'] != '0')
                                                    <!-- remove as per application -->
                                                       <!--  <a href="javascript:void(0);" class="btn btn-danger mb-4 accept_reject" data-shipment_id="{{$details[0]['shipment_id']}}" data-is_accept="0"  >Reject</a> -->
                                                    @endif
                                                    <a href="javascript:void(0);" class="btn btn-primary mb-4 accept_reject" data-shipment_id="{{$details[0]['shipment_id']}}" data-is_accept="1" >Accept</a>
                                                </div>
                                                @endif
                                                
                                            @else($details[0]['driver_id'] == '0')
                                                     <a href="javascript:void(0);" class="btn btn-primary mb-4 assign_driver_btn" data-shipment_id="{{$details[0]['shipment_id']}}" data-is_accept="1" >Assign Driver</a>
                                            @endif

                                            @if($details[0]['status'] == '1' || $details[0]['status'] == '2' || $details[0]['status'] == '4' || $details[0]['status'] == '5' )
                                                 <a data-toggle="modal" data-target="#exampleModalCentercan" href="javascript:void(0);" class="btn btn-danger mb-4">Cancel</a>
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ================ contact section end ================= -->
    </main>


     <!-- Modal -->
    <div class="modal fade" id="exampleModalSelectDriver" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Select Driver</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="search_driver" onkeypress="join_driver_list()" placeholder="Search Driver" aria-label="search_driver" aria-describedby="basic-addon1">
                <div class="input-group-prepend">
                    <span class="input-group-text remove_search_string" id="basic-addon1"> 
                        <span aria-hidden="true">&times;</span>
                    </span>
                </div>
            </div>
            <div class="row scroll-mb driver_body">
                
            </div>
          </div>
          <!-- <div class="modal-footer text-center">
            <button type="button" class="btn btn-primary">Select</button>
          </div> -->
        </div>
      </div>
    </div>
    <!-- Modal -->


    <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
          <div class="modal-dialog modal-dialog-centered doc-modal" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 driv-block">
                        <img class="upload-doc-modl" src="{{asset('public/img/doc.jpg')}}" id="ModalImage">
                        <!-- <p class="user-mob">+1 273 122 12</p> -->
                    </div>
                </div>
              </div>
              <!-- <div class="modal-footer text-center">
                <button type="button" class="btn btn-primary">Select</button>
              </div> -->
            </div>
          </div>
        </div>
        

    <!-- modal end -->

    <!--Cancel modal -->
    <div class="modal fade" id="exampleModalCentercan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Select Cancel Reason</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <!-- <div class="input-group mb-3">
        <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1"><i class="feather icon-search"></i></span>
        </div>
        <input type="text" class="form-control" placeholder="Search Driver" aria-label="search_driver" aria-describedby="basic-addon1">
        </div> -->
        <form method="POST" id="cancel_form" action="{{ route('transporterCancelShipment') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
        <input type="hidden" name="shipment_id" value="{{$details[0]['shipment_id']}}" >
        <div class="col-md-12 driv-block">
        <label>Accident </label>
        <input type="checkbox" class="canc-check" name="cancel_reason" value="0">
        </div>
        <div class="col-md-12 driv-block">
        <label>Engine Problem </label>
        <input type="checkbox" class="canc-check" name="cancel_reason" value="1">
        </div>
        <div class="col-md-12 driv-block">
        <label>Fuel Over </label>
        <input type="checkbox" class="canc-check" name="cancel_reason" value="2">
        </div>
        <div class="col-md-12 driv-block">
        <label>Medical Emergency </label>
        <input type="checkbox" class="canc-check" name="cancel_reason" value="3">
        </div>
        <div class="col-md-12 driv-block">
        <label>Other </label>
        <input type="checkbox" class="canc-check" name="cancel_reason" value="4">
        </div>
        <div style="margin-top: 15px" class="col-md-12">
        <textarea class="form-control" placeholder="Leave Comment...." name="comment" required=""></textarea>
        </div>
        <div style="margin-top: 15px" class="col-md-12 text-center">
        <button class="btn btn-primary mb-4">Submit</button>
        </div>
        </div>
        </form>
        </div>
        <!-- <div class="modal-footer text-center">
        <button type="button" class="btn btn-primary">Select</button>
        </div> -->
        </div>
        </div>
    </div>
    <!-- Cancel Model -->

    <!--Truck modal -->
    <div class="modal fade" id="exampleModalCenterTruck" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Select Truck</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <!-- <div class="input-group mb-3">
        <div class="input-group-prepend">
        <span class="input-group-text" id="basic-addon1"><i class="feather icon-search"></i></span>
        </div>
        <input type="text" class="form-control" placeholder="Search Driver" aria-label="search_driver" aria-describedby="basic-addon1">
        </div> -->
        <div class="row">
        <input type="hidden" name="shipment_id" value="{{$details[0]['shipment_id']}}" >
         @if($details[0]['truck'] != null && $details[0]['truck'] != '[]')
            @foreach($details[0]['truck'] as $truck)
            <div class="col-md-12 driv-block">
            @if($truck['title'] != "All")
                <label>{{$truck['title']}} </label>
                <input type="checkbox" class="canc-check truck_checkbox" name="truck_{{$truck['shipment_id']}}" checked="" value="{{$truck['shipment_id']}}" id="truck_{{$truck['shipment_id']}}">
            </div>
            @endif
             @endforeach
        @endif
        <div style="margin-top: 15px" class="col-md-12 text-center">
        <button class="btn btn-primary mb-4 btn_select_truck">Submit</button>
        </div>
        </div>
        </div>
        <!-- <div class="modal-footer text-center">
        <button type="button" class="btn btn-primary">Select</button>
        </div> -->
        </div>
        </div>
    </div>
    <!-- Truck Model -->

@endsection


@section('js-section')

<script type="text/javascript">
    

        $(document).on("click",".vehicle_document",function(){
            
            var img = $(this).attr('src');
            
            $('#ModalImage').attr("src",img);

            $('#exampleModalCenter').modal('show');
        });

        $(document).on("click",".accept_reject",function(){

        var click = $(this);
        var shipment_id =  $("#truck_filter_type").val();
        var is_accept = click.data('is_accept');
        var assign_driver_id = $('#assign_driver_id').val();
            
            $.ajax({
                   type:"POST",
                   url:'{{route("transporterAccpetRejectShipment")}}',
                   data : {
                              "_token": "{{ csrf_token() }}",
                              "shipment_id": shipment_id,
                              "is_accept": is_accept,
                              "assign_driver_id": assign_driver_id
                          },
                   success:function(data){
                     res =  $.parseJSON(data);
                      if(res.success == '1'){


                       if(assign_driver_id != '0'){

                            window.location.href = "{{route('transporterShowActiveShipment',['filter_type','1'])}}";

                       }else{
                            
                            window.location.href = "{{route('transporterShowRequestList')}}";
                       }
                      }else{
                        window.location.reload();
                      }
                   }

              }); 
            
        });

        $(document).on("click",".place_new_bid",function(){

        var click = $(this);
        var shipment_id = $("#truck_filter_type").val();
        var bid_amount = $('.bid_amount').val();

            if(bid_amount > 0){
                $.ajax({
                       type:"POST",
                       url:'{{route("transporterPlaceNewBid")}}',
                       data : {
                                  "_token": "{{ csrf_token() }}",
                                  "shipment_id": shipment_id,
                                  "bid_amount": bid_amount
                              },
                       success:function(data){
                         res =  $.parseJSON(data);
                          if(res.success == '1')

                            window.location.href = "{{route('transporterShowRequestList')}}";
                       }

                  }); 
            }else{
                alert("Please Enter Valid Bid Amount");
            }
        });

        
            /* ------------------ driver list for request ajax ----------------*/

        $(document).on('click', '.assign_driver_list',function(){
         
            join_driver_list('');
        //end function
        });
        
        $(document).on('click', '.remove_search_string',function(){
                
            $('#search_driver').val('');
            join_driver_list('');
        //end function
        });
        
        function join_driver_list(search_string){

            var search_string = $('#search_driver').val();

            $.ajax({
                url:"{{route('transporterShowDriverListForAssign')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",search_string:search_string},
                success:function(data){
                        
                    res = JSON.parse(data);
                    
                    $('.driver_body').html("");

                    if(res.result.length > 0){

                        $.each(res.result, function( k, v ) { 
                            
                            var profile_pic = "{{asset('public/images/user.png')}}";
                            var truck_img = "{{asset('public/images/user.png')}}";
                            
                            if(v.profile_pic != null && v.profile_pic != '' ){
                                profile_pic = v.profile_pic;
                            }

                            if(v.truck_img != null && v.truck_img != '' ){
                                truck_img = v.truck_img;
                            }

                            $('.driver_body').append('<div class="col-md-12 driv-block"><img class="user-logo driv-name" src="'+profile_pic+'"><a href="javascript:void(0);" class="user-name">'+v.user_name+'</a><a href="javascript:void(0);" data-driver_id='+v.user_id+' data-user_name="'+v.user_name+'" data-profile_pic="'+profile_pic+'" class="send-req select_driver">Select</a><img class="user-logo driv-name float-right" src="'+truck_img+'"></div>'); 
                        });
                    
                    }else{
                        
                        $('.driver_body').append('<div class="text-center mb-3 ml-15"> No Data Found </div>');
                    }
                    
                }
            });

        //end function
        }

        
        $(document).on('click', '.select_driver',function(){
            
            var driver_id = $(this).data('driver_id');
            var user_name = $(this).data('user_name');
            var profile_pic = $(this).data('profile_pic');
            
            $('#assign_driver_id').val(driver_id);
            $('#driver_user_name').html(user_name);
            $('#driver_profile_pic').html(profile_pic);

            $('.assign_driver_div').css('display','none');
            $('.driver_info_div').css('display','inline-flex');

            $('#exampleModalSelectDriver').modal('hide');            
        });


        // assign shipment to driver

        $(document).on('click', '.assign_driver_btn',function(){

            var click = $(this);
            var driver_id = $('#assign_driver_id').val();
            var shipment_id = "{{$details[0]['shipment_id']}}";

            $.ajax({
                url:"{{route('transporterAssignDriver')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",driver_id:driver_id,shipment_id:shipment_id},
                success:function(data){
                        
                    res = JSON.parse(data);

                    if(res.success == '1'){
                        
                       window.location.href = "{{route('transporterShowActiveShipment')}}";
                    }else{
                        window.location.reload();
                    }
                    
                }
            });
         //end function
        });


    /* ------------------ driver list for request ajax ----------------*/


     // update bid

        $(document).on('click', '.update_bid',function(){

            var click = $(this);
            // var shipment_id = "{{$details[0]['shipment_id']}}";
            var shipment_id = $("#truck_filter_type").val();

            var bid_amount = $('#bid_amount').val();
            var type = $(this).data('type');

            $.ajax({
                url:"{{route('transporterEditDeleteBid')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",shipment_id:shipment_id,bid_amount:bid_amount,type:type},
                success:function(data){
                        
                    res = JSON.parse(data);

                    if(res.success == '1'){
                        
                        window.location.reload();
                    }
                    
                }
            });
         //end function
        });

        
        $(document).on("change","#truck_filter_type",function(){
            var check_all = $('#truck_filter_type option:selected').text();
            if(check_all == 'All'){
                $('#exampleModalCenterTruck').modal('show'); return;
            }
              var ship_id = $(this).val();
              window.location = '{{url("/transporter/shipment/request")}}/'+ship_id+'/details';
          });

        $(document).on("click",".btn_select_truck",function(){
            var shipment_ids = '0';
            var flag = '0';
            $('input:checkbox.truck_checkbox').each(function () {
                   var ThisVal = (this.checked ? $(this).val() : "");
                    if(flag == '0'){
                        shipment_ids = ThisVal;
                    }else if(ThisVal != ""){
                        shipment_ids += ","+ThisVal;
                    }             
                    flag = '1';
              });
            if(shipment_ids != ""){

                    $('#all_truck_option').val(shipment_ids);
                    $('#exampleModalCenterTruck').modal('hide'); return;
            }else{
                alert('Please Select Truck');
            }
        });

</script>

@endsection