@section('css-section')
<style type="text/css" media="screen">
    .upload-doc{
        border-radius: 10px;
        max-width: 100px;
        width: 60px;
        height: 60px;
        border: 2px solid #b5b5b5;
    }
</style>

@endsection

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
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <!-- <div class="pickup-info">
                                                    <label class="det-label">Mobile No: </label>
                                                    <p class="pick-p">+1 621-12-123</p>
                                                </div> -->
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
                                                
                                                @if($details[0]['quotation_type'] == '0' && $details[0]['is_bid_selected'] == '0')

                                                    @if($details[0]['bid_amount'] == '0')
                                                        <div class="pickup-info edit-info">
                                                            <label class="det-label">Bid Amount: </label>
                                                            <input type="text" class="form-control edit-pro bid_amount" placeholder="Enter bid amount">
                                                        </div>
                                                        <div class="full-width text-center">
                                                            <a href="javascript:void(0);" class="btn btn-primary mb-4 place_new_bid" data-shipment_id="{{$details[0]['shipment_id']}}">Place Bid</a>
                                                        </div>
                                                    @else
                                                        <div class="pickup-info edit-info">
                                                            <label class="det-label">Bid Amount: </label>
                                                            <input type="number" class="form-control edit-pro bid_amount" id="bid_amount" value="{{$details[0]['bid_amount']}}" placeholder="Enter bid amount">
                                                        </div>


                                                        <div class="full-width text-center">
                                                            <a href="#" class="btn btn-primary mb-2 update_bid" data-shipment_id="{{$details[0]['shipment_id']}}" data-type="0" >Update Bid</a>
                                                            <a href="#" class="btn btn-danger mb-2 update_bid" data-shipment_id="{{$details[0]['shipment_id']}}" data-type="1">Cancel Bid</a>
                                                        </div>
                                                    @endif

                                                @elseif($details[0]['quotation_type'] == '1' OR ($details[0]['is_bid_selected'] == '1' && $details[0]['bid_amount'] != '0') )

                                                    @if($details[0]['quotation_type'] == '0')
                                                    <div class="drop-info">
                                                        <label class="det-label">Bid Amount: </label>
                                                        <p class="pick-p">{{$details[0]['bid_amount']}} 
                                                        </p>
                                                    </div>
                                                    @endif

                                                <div class="full-width text-center">
                                                    <!-- <a href="javascript:void(0);" class="btn btn-danger mb-4 accept_reject" data-shipment_id="{{$details[0]['shipment_id']}}" data-is_accept="0"  >Reject</a> -->
                                                    <a href="javascript:void(0);" class="btn btn-primary mb-4 accept_reject" data-shipment_id="{{$details[0]['shipment_id']}}" data-is_accept="1" >Accept</a>
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
        <!-- ================ contact section end ================= -->
    </main>

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
        var shipment_id = click.data('shipment_id');
        var is_accept = click.data('is_accept');
        
            $.ajax({
                   type:"POST",
                   url:'{{route("driverAccpetRejectShipment")}}',
                   data : {
                              "_token": "{{ csrf_token() }}",
                              "shipment_id": shipment_id,
                              "is_accept": is_accept
                          },
                   success:function(data){
                     res =  $.parseJSON(data);
                      if(res.success == '1')
                       if(is_accept == '1'){

                            window.location.href = "{{route('driverShowActiveShipment')}}";
                          
                       }else{
                            window.location.href = "{{route('driverShowRequestList')}}";
                       }
                   }

              }); 
        });

        $(document).on("click",".place_new_bid",function(){

        var click = $(this);
        var shipment_id = click.data('shipment_id');
        var bid_amount = $('.bid_amount').val();
        
            $.ajax({
                   type:"POST",
                   url:'{{route("driverPlaceNewBid")}}',
                   data : {
                              "_token": "{{ csrf_token() }}",
                              "shipment_id": shipment_id,
                              "bid_amount": bid_amount
                          },
                   success:function(data){
                     res =  $.parseJSON(data);
                      
                      if(res.success == '1'){

                        window.location.href = "{{route('driverShowRequestList')}}";

                      }
                   
                   }

              }); 
        });

        
          // update bid

        $(document).on('click', '.update_bid',function(){

            var click = $(this);
            var shipment_id = "{{$details[0]['shipment_id']}}";
            var bid_amount = $('#bid_amount').val();
            var type = $(this).data('type');
            
            $.ajax({
                url:"{{route('driverEditDeleteBid')}}",
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



</script>

@endsection