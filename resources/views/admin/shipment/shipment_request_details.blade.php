@section('css-section')

<style type="text/css" media="screen">

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

@extends('admin.common.master')
@section('main-content')

 <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->
                    <div class="page-header">
                        <div class="page-block">
                            <div class="row align-items-center">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h3 class="m-b-10">Shipment Request Details</h3>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
        <section class="contact-section">
            <div class="container">
                <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Shipment ID : {{$details[0]['shipment_id']}}</h5>
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

                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="book-date-sec">
                                                    <p class="book-date">{{date('d-M-Y h:i A',strtotime($details[0]['created_at']))}}</p>
                                                </div>
                                                <div class="pickup-info">
                                                <label class="det-label">Shipper info: </label>
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

                                                @if($details[0]['transporter_id'] != '0')
                                                 <div class="pickup-info">
                                                    <label class="det-label">Transporter info: </label>
                                                    <div class="user-img">
                                                    <img class="user-logo" 
                                                    @if($details[0]['transporter_profile_pic'] != null && $details[0]['transporter_profile_pic'] != '')
                                                    src="{{$details[0]['transporter_profile_pic']}}"
                                                    @else
                                                    src="{{asset('public/images/user.png')}}"
                                                    @endif
                                                    >
                                                    <p class="user-name"> &nbsp; {{$details[0]['transporter_first_name']}}  {{$details[0]['transporter_last_name']}}</p>
                                                    </div>
                                                </div>
                                            
                                                @elseif($details[0]['quotation_type'] == '2')

                                                 <div class="pickup-info">
                                                    <img class="plus-logo" src="{{asset('public/images/plus.png')}}">
                                                        <a data-toggle="modal" id="assign_transporter" data-target="#exampleModalSelectTransporter" href="javascript:void(0);" >Assign Transporter</a>

                                                </div>
                                                    
                                                @endif

                                                @if($details[0]['driver_id'] != '0')
                                                 <div class="pickup-info">
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
                                                </div>
                                            
                                                @endif
                                                <!-- elseif($details[0]['transporter_id'] != '0' && $details[0]['quotation_type'] == '2')

                                                 <div class="pickup-info">
                                                    <img class="plus-logo" src="{{asset('public/images/plus.png')}}">
                                                        <a data-toggle="modal" id="assign_driver" data-target="#exampleModalSelectDriver" href="javascript:void(0);" >Assign Driver</a>
                                                </div> -->
                                                    
                                                 
                                                <div class="pickup-info">
                                                    <label class="det-label">Shipper Mobile: </label>
                                                    <p class="pick-p">{{$details[0]['shipper_mobile']}}</p>
                                                </div>

                                                @if($details[0]['transporter_id'] != '0')
                                                <div class="pickup-info">
                                                    <label class="det-label">Transporter Mobile: </label>
                                                    <p class="pick-p">{{$details[0]['transporter_mobile']}}</p>
                                                </div>
                                                @endif
                                                
                                                @if($details[0]['driver_id'] != '0')
                                                <div class="pickup-info">
                                                    <label class="det-label">Driver Mobile: </label>
                                                    <p class="pick-p">{{$details[0]['driver_mobile']}}</p>
                                                </div>
                                                @endif

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
                                                    <p class="pick-p">{{$details[0]['shipment_id']}}</p>
                                                </div>

                                                <div class="drop-info">
                                                    <label class="det-label">Status: </label>
                                                    @if($details[0]['status'] == '0')

                                                        @if($details[0]['quotation_type'] == '0')

                                                          @if($details[0]['bidder_count'] == '0')

                                                                <p class="pending-lab pick-p" style="color:#FFC70D">
                                                                Awaiting Bid </p>

                                                            @else
                                                                
                                                                @if($details[0]['bid_status'] == '1')
                                                                    <p class="pending-lab pick-p" style="color:#FFC70D">
                                                                        Bidder Awarded
                                                                    </p>
                                                                @elseif($details[0]['bid_status'] == '0')
                                                                    <p class="pending-lab pick-p" style="color:#FFC70D">
                                                                        Bid Received
                                                                    </p>
                                                                @endif

                                                            @endif
                                                      @else
                                                      <p class="pending-lab pick-p" style="color:#FFC70D">
                                                        Schedule for Delivery
                                                      </p>
                                                      @endif
                                                    @elseif($details[0]['status'] == '1')
                                                    <p class="pending-lab pick-p" style="color:#00874A">
                                                    Accepted By Transporter
                                                    </p>
                                                    @elseif($details[0]['status'] == '2')
                                                    <p class="pending-lab pick-p"  style="color:#0063C6">
                                                    On The Way
                                                    </p>
                                                    @elseif($details[0]['status'] == '4')
                                                    <p class="pending-lab pick-p"  style="color:#00874A">
                                                    Arrived
                                                    </p>
                                                    @elseif($details[0]['status'] == '5')
                                                    <p class="pending-lab pick-p"  style="color:#FFC70D">
                                                    Shipment Started 
                                                    </p>
                                                    @elseif($details[0]['status'] == '6')
                                                    <p class="pending-lab pick-p"  style="color:#12D612">
                                                    Delivered
                                                    </p>
                                                    @endif
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
                                                    <p class="pick-p">{{$details[0]['goods_type_name']}}
                                                    </p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Weight: </label>
                                                    <p class="pick-p">
                                                        {{$details[0]['weight']}} 
                                                    @if($details[0]['weight_type'] == '0')
                                                    Kg
                                                    @elseif($details[0]['weight_type'] == '1')
                                                    Ton
                                                    @endif
                                                    </p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Instruction: </label>
                                                    <p class="pick-p">{{$details[0]['info']}} </p>
                                                </div>
                                                <div class="pickup-info">
                                                    <label class="det-label">Loading Documents: </label>
                                                    @foreach($doc as $doc)
                                                        <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$doc}}"></a>
                                                    @endforeach
                                                </div>

                                                @if($details[0]['quotation_type'] == '0')

                                                    <div class="pickup-info">
                                                        <label class="det-label">Bid Details: </label>
                                                        <a  class="ship-doc" data-toggle="modal" data-target="#exampleModalSelectBidder" href="#" >View</a>
                                                    </div>

                                                @endif

                                                <!-- <div class="full-width text-center">
                                                    <a href="javascript:void(0);" class="btn btn-danger mb-4">Reject</a>
                                                    <a href="javascript:void(0);" class="btn btn-primary mb-4">Accept</a>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Invoice</h5>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">


                                                <div class="invoice-info">
                                                    <label class="det-label">Payment Method: </label>
                                                    <p class="pick-p card-text">
                                                        @if($details[0]['payment_type'] == '0')
                                                        Cash
                                                        @elseif($details[0]['payment_type'] == '1')
                                                        Card
                                                        @else
                                                        ACH
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="invoice-info">
                                                    <label class="det-label">BaseFare:</label>
                                                    <p class="pick-p">{{$details[0]['base_fare']}} DA</p>
                                                </div>
                                                <div class="invoice-info">
                                                    <label class="det-label">Insurance: </label>
                                                    <p class="pick-p">0 DA</p>
                                                </div>
                                                <div class="invoice-info">
                                                    <label class="det-label">KMIOU Charges {{$details[0]['kmiou_charges_per']}} : </label>
                                                    <p class="pick-p">{{$details[0]['kmiou_charges_amount']}}</p>
                                                </div>
                                                <div class="invoice-info">
                                                    <label class="det-label">Tax {{$details[0]['tax_per']}}(%) : </label>
                                                    <p class="pick-p">{{$details[0]['tax_amount']}} DA</p>
                                                </div>
                                                <div class="invoice-info">
                                                    <label class="det-label">Discount {{$details[0]['discount_per']}}(%) : </label>
                                                    <p class="pick-p">{{$details[0]['discount_amount']}} DA</p>
                                                </div>
                                                <div class="invoice-info">
                                                    <label class="det-label total-lab">Total Fare: </label>
                                                    <p class="pick-p total-lab">{{$details[0]['amount']}} DA</p>
                                                </div>
                                            
                                            @if($details[0]['quotation_type'] == '2' && $details[0]['amount'] == '0')
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Enter Amount: </label>
                                                    <input type="number" class="form-control edit-pro shipment_amount" placeholder="Enter Shipment amount">
                                                </div>
                                                <div class="full-width text-center">
                                                    <a href="#" class="btn btn-primary mb-4 enter_shipment_amount" data-shipment_id="{{$details[0]['shipment_id']}}">Submit</a>
                                                </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->



    <!--Select Transporter Modal -->
    <div class="modal fade" id="exampleModalSelectTransporter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Select Transporter</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="search_transporter" onkeypress="join_transporter_list()" placeholder="Search Transporter" aria-label="search_driver" aria-describedby="basic-addon1">
                <div class="input-group-prepend">
                    <span class="input-group-text remove_search_string_transporter" id="basic-addon1"> 
                        <span aria-hidden="true">&times;</span>
                    </span>
                </div>
            </div>
            <div class="row scroll-mb transporter_body">
                
            </div>
          </div>
          <!-- <div class="modal-footer text-center">
            <button type="button" class="btn btn-primary">Select</button>
          </div> -->
        </div>
      </div>
    </div>
    <!--Select Transporter Modal -->


    <!--Select Driver Modal -->
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
    <!--Select Driver Modal -->


     <!--Document Modal -->
    <div class="modal fade" id="exampleModalDocument" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
     <!--Document Modal -->

        <!--Bidder Modal -->
    <div class="modal fade" id="exampleModalSelectBidder" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Select Bidder</h5>
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
            @if($bid != [])
               @foreach($bid as $bid)
                <div class="col-md-12 driv-block">
                    <img class="user-logo driv-name" 
                    @if($bid->user_profile_pic != null && $bid->user_profile_pic != '')
                    src="{{$bid->user_profile_pic}}"
                    @else
                    src="{{asset('public/images/user.png')}}"
                    @endif>
                    @if($details[0]['bid_status'] == '0')
                        <a href="javascript:void(0);" class="user-name get_bid_id" data-bid_id="{{$bid->id}}">{{$bid->user_first_name}} {{$bid->user_last_name}} </a>
                        <p class="pick-p total-lab marg-bid-rate">{{$bid->bid_amount}} DA</p>
                    
                    @elseif($details[0]['bid_status'] == '1')
                        
                        {{$bid->user_first_name}} {{$bid->user_last_name}}
                        <p class="pick-p total-lab marg-bid-rate">
                        @if($bid->status == '1') 
                        Selected &nbsp; &nbsp;
                        @endif
                         {{$bid->bid_amount}} DA</p>

                    @endif
                </div>
               @endforeach
            @else
            <h5 text-center> No One Bidded Yet </h5>
            @endif
                
            </div>
          </div>
          <!-- <div class="modal-footer text-center">
            <button type="button" class="btn btn-primary">Select</button>
          </div> -->
        </div>
      </div>
    </div>
    <!-- <div class="view-more">
        <a href="view">View More</a>
    </div> -->

    <!--Bidder modal -->

@endsection

@section('js-section')

<script type="text/javascript">

        $(document).on('click', '#assign_transporter',function(){
         
            join_transporter_list('');
        //end function
        });
        
        $(document).on('click', '.remove_search_string_transporter',function(){
                
            $('#search_transporter').val('');
            join_transporter_list('');
        //end function
        });
        
        function join_transporter_list(search_string){

            var search_string = $('#search_transporter').val();

            $.ajax({
                url:"{{route('transporterList')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",search_string:search_string},
                success:function(data){
                        
                    res = JSON.parse(data);
                    
                    $('.transporter_body').html("");

                    if(res.result.length > 0){

                        $.each(res.result, function( k, v ) { 
                            
                            var profile_pic = "{{asset('public/images/user.png')}}";
                            
                            if(v.profile_pic != null && v.profile_pic != '' ){
                                profile_pic = v.profile_pic;
                            }

                            $('.transporter_body').append('<div class="col-md-12 driv-block"><img class="user-logo driv-name" src="'+profile_pic+'"><a href="javascript:void(0);" class="user-name">'+v.user_name+'</a><a href="javascript:void(0);" data-transporter_id='+v.user_id+' class="send-req assign_transporter">Assign</a></div>'); 
                        });
                    
                    }else{
                        
                        $('.transporter_body').append('<div class="text-center mb-3 ml-15"> No Data Found </div>');
                    }
                    
                }
            });

        //end function
        }

        // assign shipment to transporter

        $(document).on('click', '.assign_transporter',function(){

            var click = $(this);
            var transporter_id = click.data('transporter_id');
            var shipment_id = "{{$details[0]['shipment_id']}}";

            $.ajax({
                url:"{{route('assignTransporter')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",transporter_id:transporter_id,shipment_id:shipment_id},
                success:function(data){
                        
                    res = JSON.parse(data);

                    if(res.success == '1'){
                        window.location.reload();
                    }else{
                        window.location.reload();
                    }
                    
                }
            });
         //end function
        });


        $(document).on('click', '#assign_driver',function(){
                 
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
            var transporter_id = "{{$details[0]['transporter_id']}}";

            $.ajax({
                url:"{{route('driverList')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",search_string:search_string,transporter_id:transporter_id},
                success:function(data){
                        
                    res = JSON.parse(data);
                    
                    $('.driver_body').html("");

                    if(res.result.length > 0){

                        $.each(res.result, function( k, v ) { 
                            
                            var profile_pic = "{{asset('public/images/user.png')}}";
                            
                            if(v.profile_pic != null && v.profile_pic != '' ){
                                profile_pic = v.profile_pic;
                            }

                            $('.driver_body').append('<div class="col-md-12 driv-block"><img class="user-logo driv-name" src="'+profile_pic+'"><a href="javascript:void(0);" class="user-name">'+v.user_name+'</a><a href="javascript:void(0);" data-driver_id='+v.user_id+' class="send-req assign_driver">Assign</a></div>'); 
                        });
                    
                    }else{
                        
                        $('.driver_body').append('<div class="text-center mb-3 ml-15"> No Data Found </div>');
                    }
                    
                }
            });

        //end function
        }

        // assign shipment to driver

        $(document).on('click', '.assign_driver',function(){

            var click = $(this);
            var transporter_id = "{{$details[0]['transporter_id']}}";
            var driver_id = click.data('driver_id');
            var shipment_id = "{{$details[0]['shipment_id']}}";

            $.ajax({
                url:"{{route('assignDriver')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",transporter_id:transporter_id,driver_id:driver_id,shipment_id:shipment_id},
                success:function(data){
                        
                    res = JSON.parse(data);

                    if(res.success == '1'){
                        window.location.reload();
                    }else{
                        window.location.reload();
                    }
                    
                }
            });
         //end function
        });

        // enter amount of shipment

        $(document).on('click', '.enter_shipment_amount',function(){

            var shipment_amount = $('.shipment_amount').val();
            var shipment_id = "{{$details[0]['shipment_id']}}";

            $.ajax({
                url:"{{route('setShipmentAmount')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",shipment_amount:shipment_amount,shipment_id:shipment_id},
                success:function(data){
                        
                    res = JSON.parse(data);

                    if(res.success == '1'){
                        window.location.reload();
                    }else{
                        window.location.reload();
                    }
                    
                }
            });
         //end function
        });


        $(document).on("click",".vehicle_document",function(){
            
            var img = $(this).attr('src');
            
            $('#ModalImage').attr("src",img);

            $('#exampleModalDocument').modal('show');
        });

</script>

@endsection