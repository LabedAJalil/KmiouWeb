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
                                        <h3 class="m-b-10">Reported Shipment Details</h3>
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
                                                    <p class="pending-lab pick-p" style="color:#FFC70D">
                                                    Schedule for Delivery
                                                    </p>
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
                                                    <label class="det-label">Report Reason: </label>
                                                    <p class="pending-lab pick-p disp-cont" style="color:red">
                                                        @if($details[0]['report_emergency'] == '0')
                                                        Security Emergency
                                                        @elseif($details[0]['report_emergency'] == '1')
                                                        Engine Problem
                                                        @elseif($details[0]['report_emergency'] == '2')
                                                        Fuel Over
                                                        @elseif($details[0]['report_emergency'] == '3')
                                                        Truck Tire Flat
                                                        @else
                                                        Other
                                                        @endif
                                                    </p>
                                                </div>

                                                <div class="drop-info">
                                                    <label class="det-label">Report Comment: </label>
                                                    <p class="pending-lab pick-p disp-cont">
                                                    
                                                        @foreach($comment as $comment)
                                                            <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$comment}}"></a>
                                                        @endforeach
                                                    </p>
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
                                                    <p class="pick-p total-lab">{{$details[0]['total_amount']}} DA</p>
                                                </div>
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
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->


     <!-- Modal -->
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
                   <img class="upload-doc-modl" src="{{asset('public/img/doc.jpg')}}" id="ModalImage" style="width:100% !important;">
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
    <!-- <div class="drop-info">
        <label class="det-label">Shipping Documents: </label>
        <a class="ship-doc" href="#">Download</a>
    </div> -->

@endsection

@section('js-section')

<script type="text/javascript">
     

        $(document).on("click",".vehicle_document",function(){
            
            var img = $(this).attr('src');
            
            $('#ModalImage').attr("src",img);

            $('#exampleModalDocument').modal('show');
        });

</script>

@endsection