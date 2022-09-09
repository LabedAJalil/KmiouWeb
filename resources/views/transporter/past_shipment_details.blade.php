@section('css-section')
<style>
  .cls-1, .cls-4 {
    fill: none;
    stroke-width: 11px;
  }

  .cls-1 {
    stroke: #00874a;
  }

  .cls-2, .cls-9 {
    fill: #c6c6c6;
  }

  .cls-3 {
    fill: #00874a;
  }

  .cls-4 {
    stroke: #c6c6c6;
  }

  .cls-5 {
    fill: #b5b3b3;
  }

  .cls-5, .cls-9 {
    font-size: 20px;
    font-family: OpenSans, Open Sans;
  }

  .cls-6 {
    fill: #000;
  }

  .cls-7 {
    font-size: 8px;
  }

  .cls-8 {
    font-size: 12px;
  }
  .cls-blck{
  fill: black;
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
                                <h2>Past Shipment Details</h2>
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

                                <!-- @if($details[0]['truck'] != null && $details[0]['truck'] != '[]')
                                 <ul class="navbar-nav ml-auto col-md-3 text-center">
                                     <li>
                                        <select class="form-control" style="margin-bottom: 15px;" id="truck_filter_type">
                                                @foreach($details[0]['truck'] as $truck)

                                                    <option value="{{$truck['shipment_id']}}" <?php if($details[0]['shipment_id'] == $truck['shipment_id']){ echo("selected");} ?> >{{$truck['title']}}</option>
                                                
                                                @endforeach
                                                
                                        </select>
                                    </li>
                                </ul>
                                @endif -->

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
                                                    @endif>
                                                    <a  class="track_shipment" data-toggle="modal" data-target="#shipperUserProfile" href="#" ><p class="user-name">&nbsp; {{$details[0]['shipper_first_name']}}  {{$details[0]['shipper_last_name']}}</p></a>
                                                    <!-- namemodal -->
                                                    </div>
                                                </div>
                                                <div class="modal fade" id="shipperUserProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">User Info</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <img class="user-logo" 
                                                                @if($details[0]['shipper_profile_pic'] != null && $details[0]['shipper_profile_pic'] != '')
                                                                src="{{$details[0]['shipper_profile_pic']}}"
                                                                @else
                                                                src="{{asset('public/images/user.png')}}"
                                                                @endif>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <p style="color:black;font-weight: 600;">Name:</p>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <p>{{$details[0]['shipper_first_name']}}  {{$details[0]['shipper_last_name']}}</p>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <p style="color:black;font-weight: 600;">Email:</p>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <p>{{$details[0]['shipper_email']}}</p>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <p style="color:black;font-weight: 600;">Mobile:</p>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <p>{{$details[0]['shipper_mobile']}}</p>
                                                            </div>

                                                        </div>
                                                      </div>
                                                      <!-- <div class="modal-footer text-center">
                                                        <button type="button" class="btn btn-primary">Select</button>
                                                      </div> -->
                                                    </div>
                                                  </div>
                                                </div>

                                                <div class="pickup-info">
                                                    <label class="det-label">Driver info: </label>
                                                    <div class="user-img">
                                                    <img class="user-logo" 
                                                    @if($details[0]['driver_profile_pic'] != null && $details[0]['driver_profile_pic'] != '')
                                                    src="{{$details[0]['driver_profile_pic']}}"
                                                    @else
                                                    src="{{asset('public/images/user.png')}}"
                                                    @endif>
                                                    <a  class="track_shipment" data-toggle="modal" data-target="#driverUserProfile" href="#" >
                                                    <p class="user-name">&nbsp; {{$details[0]['driver_first_name']}}  {{$details[0]['driver_last_name']}}</p> </a>
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

                                                <div class="modal fade" id="driverUserProfile" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">User Info</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <img class="user-logo" 
                                                                @if($details[0]['shipper_profile_pic'] != null && $details[0]['shipper_profile_pic'] != '')
                                                                src="{{$details[0]['shipper_profile_pic']}}"
                                                                @else
                                                                src="{{asset('public/images/user.png')}}"
                                                                @endif>
                                                            </div>
                                                            <div class="col-md-3">
                                                                <p style="color:black;font-weight: 600;">Name:</p>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <p>{{$details[0]['driver_first_name']}}  {{$details[0]['driver_last_name']}}</p>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <p style="color:black;font-weight: 600;">Email:</p>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <p>{{$details[0]['driver_email']}}</p>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <p style="color:black;font-weight: 600;">Mobile:</p>
                                                            </div>
                                                            <div class="col-md-9">
                                                                <p>{{$details[0]['driver_mobile']}}</p>
                                                            </div>

                                                        </div>
                                                      </div>
                                                      <!-- <div class="modal-footer text-center">
                                                        <button type="button" class="btn btn-primary">Select</button>
                                                      </div> -->
                                                    </div>
                                                  </div>
                                                </div>
                                               
                                                <div class="pickup-info">
                                                    <label class="det-label">Shipper Mobile No: </label>
                                                    <p class="pick-p">{{$details[0]['shipper_mobile']}}</p>
                                                </div>

                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <div class="pickup-info">
                                                    <label class="det-label">Driver Mobile No: </label>
                                                    <p class="pick-p">{{$details[0]['driver_mobile']}}</p>
                                                </div>

                                                <div class="pickup-info">
                                                    <label class="det-label">Sender Name: </label>
                                                        <p class="pick-p">{{$details[0]['sender_first_name']}} {{$details[0]['sender_last_name']}} </p>
                                                </div>
                                                <div class="pickup-info">
                                                    <label class="det-label">Sender Mobile No: </label>
                                                        <p class="pick-p">{{$details[0]['sender_mobile']}}</p>
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
                                                    <p class="pick-p">{{$details[0]['ship_id']}}</p></p>
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
                                                    <p class="pick-p">{{$details[0]['info']}} </p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Shipping Documents: </label>
                                                    @foreach($doc as $doc)
                                                        <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$doc}}"></a>
                                                    @endforeach

                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Status: </label>
                                                    <p class="pick-p card-text" style="color:#12D612">Delivered</p>
                                                </div>

                                                <div class="drop-info">
                                                    <label class="det-label">Payment Status: </label>
                                                    @if($details[0]['payment_status'] == '0')
                                                        <p class="pick-p card-text" style="color:#ed3709">Pending</p>
                                                    @elseif($details[0]['payment_status'] == '1')
                                                        <p class="pick-p card-text" style="color:#12D612">Paid</p>
                                                    @elseif($details[0]['payment_status'] == '2')
                                                        <p class="pick-p card-text" style="color:#FFBA00">Ongoing</p>
                                                    @endif
                                                </div>

                                                <div class="pickup-info">
                                                    <label class="det-label">Receiver Name : </label>
                                                    <p class="pick-p">{{$details[0]['person_name']}} </p>
                                                </div>

                                                <div class="pickup-info">
                                                    <label class="det-label">Signature and ID Proof : </label>
                                                    
                                                    @if($details[0]['id_proof_image'] != null)
                                                    <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$details[0]['id_proof_image']}}"></a>
                                                    @endif

                                                    @if($details[0]['signature_image'] != null)
                                                    <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$details[0]['signature_image']}}"></a>
                                                    @endif
                                                    
                                                </div>

                                                <div class="pickup-info">
                                                    <label class="det-label">Track Shipment: </label>
                                                    <a  class="track_shipment" data-toggle="modal" data-target="#exampleModalTrack" href="#" >Track</a>
                                                </div>

                                                <div class="full-width text-center">
                                                
                                                @if($details[0]['payment_status'] == '0')
                                                    <a href="javascript:void(0);" class="btn btn-primary mb-4 pay_shipment_status" data-shipment_id="{{$details[0]['shipment_id']}}" >Paid </a>
                                                @endif
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
                                                    <label class="det-label">Insurance:</label>
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
        <!-- ================ contact section end ================= -->
    </main>

    <!--Document Modal -->
      <div class="modal fade" id="exampleModalDocument" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
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
  
    <!--Document Modal end -->


 <!--track Modal -->
                                                
        <div class="modal fade" id="exampleModalTrack" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Track Shipment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="track-line">
                            <svg xmlns="http://www.w3.org/2000/svg" width="319" height="878" viewBox="0 0 319 878">
                              <defs>
                              </defs>
                              <g id="Group_5363" data-name="Group 5363" transform="translate(-12741 -916)">
                                <g id="Group_5362" data-name="Group 5362">
                                  <g id="Group_5190" data-name="Group 5190" transform="translate(566 554)">
                                    <g id="Group_5187" data-name="Group 5187">
                                      <g id="Group_5183" data-name="Group 5183" transform="translate(16059 -301)">
                                        <line id="Line_2" data-name="Line 24" class="cls-4" y2="135" transform="translate(-3872 822)"/>
                                        <!-- <circle id="circle_2" data-name="Ellipse 29" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 806)"/> -->
                                        <circle id="Ellipse_571" data-name="Ellipse 571" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 942)"/>
                                      </g>
                                      <g id="Group_5185" data-name="Group 5185" transform="translate(16059 -164)">
                                        <line id="Line_3" data-name="Line 24" class="cls-4" y2="135" transform="translate(-3872 822)"/>
                                        <circle id="circle_2" data-name="Ellipse 29" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 805)"/>
                                        <circle id="Ellipse_571-2" data-name="Ellipse 571" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 942)"/>
                                      </g>
                                      <g id="Group_5186" data-name="Group 5186" transform="translate(16059 -27)">
                                        <line id="Line_4" data-name="Line 24" class="cls-4" y2="135" transform="translate(-3872 822)"/>
                                        <circle id="circle_3" data-name="Ellipse 29" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 805)"/>
                                        <circle id="circle_4" data-name="Ellipse 571" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 942)"/>
                                      </g>
                                      <g id="Group_5186" data-name="Group 5186" transform="translate(16059 -27)">
                                        <line id="Line_5" data-name="Line 24" class="cls-4" y2="135" transform="translate(-3872 965)"/>
                                        <!-- <circle id="circle_3" data-name="Ellipse 29" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 805)"/>
                                        <circle id="circle_4" data-name="Ellipse 571" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 1072)"/> -->
                                      </g>
                                      <g id="Group_5184" data-name="Group 5184" transform="translate(16002 -437)">
                                        <line id="Line_1" data-name="Line 31" class="cls-4" y2="135" transform="translate(-3815 822)"/>
                                        <circle id="circle_0" data-name="Ellipse 572" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3827 806)"/>
                                        <circle id="circle_1" data-name="Ellipse 573" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3827 942)"/>
                                      </g>
                                      <g id="Group_5184" data-name="Group 5184" transform="translate(16002 236)">
                                        <line id="Line_6" data-name="Line 31" class="cls-4" y2="135" transform="translate(-3815 822)"/>
                                        <circle id="circle_5" data-name="Ellipse 572" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3827 806)"/>
                                        <circle id="circle_6" data-name="Ellipse 573" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3827 942)"/>
                                      </g>
                                    </g>
                                  </g>
                                  <text id="Ordered_Sun_5th_June_20_" data-name="Ordered 
                            Sun, 5th June 20
                            " class="cls-5" transform="translate(12802 916)"><tspan class="cls-6"><tspan x="0" y="30">Ordered</tspan><tspan class="cls-7" y="30"> </tspan></tspan><tspan class="cls-8"><tspan x="0" y="54" id="track_date_0"></tspan></tspan></text>
                                  <text id="Driver_Accepted_Sun_5th_June_20_" data-name="Driver Accepted
                            Sun, 5th June 20
                            " class="cls-5" transform="translate(12802 1046)"><tspan class="cls-6"><tspan x="0" y="30">Accepted</tspan></tspan><tspan class="cls-8"><tspan x="0" y="54" id="track_date_1"></tspan></tspan></text>
                                  <text id="Driver_Accepted_Sun_5th_June_20_" data-name="Driver Accepted
                            Sun, 5th June 20
                            " class="cls-5" transform="translate(12802 1188)"><tspan class="cls-6"><tspan x="0" y="30">Arrived At Pickup Location</tspan></tspan><tspan class="cls-8"><tspan x="0" y="54" id="track_date_2"></tspan></tspan></text>
                                  <text id="Truck_On_The_Way_Sun_5th_June_20_" data-name="Truck On The Way
                            Sun, 5th June 20
                            " class="cls-5" transform="translate(12802 1320)"><tspan class="cls-6"><tspan x="0" y="30">Start Shipment</tspan></tspan><tspan class="cls-8"><tspan x="0" y="54" id="track_date_3"></tspan></tspan></text>
                                  <text id="Shipped_" data-name="Shipped
                            " class="cls-9" transform="translate(12802 1455)"><tspan class="cls-blck" x="0" y="30">Truck On The Way</tspan><tspan class="cls-8" y="30"></tspan><tspan class="cls-8"><tspan x="0" y="54" id="track_date_4"></tspan></tspan></text>
                                  <text id="Delivered" class="cls-9" transform="translate(12802 1721)"><tspan class="cls-blck" x="0" y="30">Delivered</tspan>
                                    <tspan class="cls-8">
                                         <tspan x="0" y="54" id="track_date_6"></tspan>
                                    </tspan>
                                     </text>
                                  <text id="Delivered" class="cls-9" transform="translate(12802 1588)"><tspan class="cls-blck" x="0" y="30">Arrived At Drop Off Location</tspan>
                                    <tspan class="cls-8">
                                        <tspan x="0" y="54" id="track_date_5"></tspan>
                                    </tspan></text>
                                </g>
                              </g>
                            </svg>

                        </div>
                    </div>
                </div>
              </div>
              <!-- <div class="modal-footer text-center">
                <button type="button" class="btn btn-primary">Select</button>
              </div> -->
            </div>
          </div>
        </div>
    <!--track Modal -->

@endsection



@section('js-section')

<script type="text/javascript">
    

        $(document).on("click",".vehicle_document",function(){
            
            var img = $(this).attr('src');
            
            $('#ModalImage').attr("src",img);

            $('#exampleModalDocument').modal('show');
        });  

        $(document).on('click', '.pay_shipment_status',function(){
         var click = $(this);
         var shipment_id = click.attr('data-shipment_id');

            $.ajax({
                url:"{{route('transporterUpdatePayShipmentStatus')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",shipment_id:shipment_id},
                success:function(data){
                        
                    res = JSON.parse(data);

                    if(res.success == '1'){
                        window.location.reload();
                    }
                }
            })
        });


        $(document).on("click",".track_shipment",function(){

        var shipment_id = "{{$details[0]['shipment_id']}}";
        
        $.ajax({
            url:"{{route('transporterTrackShipment')}}",
            type:"POST",
            data:{'_token':"{{csrf_token()}}",shipment_id:shipment_id},
            success:function(data){
                    
            res = JSON.parse(data);
            
                var track = res.result;
                if(res.success == '1'){

                    for (var i = 0; i < 7; i++) {
                        
                        if(track[i].status == '1'){

                            $('#track_date_'+i).text(track[i].date);
                            
                            $("#Line_"+i).removeClass("cls-4");
                            $("#Line_"+i).addClass("cls-1");
                            
                            $("#circle_"+i).removeClass("cls-2");
                            $("#circle_"+i).addClass("cls-3");

                        }
                    }

                }else{
                    
                    window.location.href="{{route('shipperShowActiveShipment')}}";
                }
            }
        })
       
    });  

        $(document).on("change","#truck_filter_type",function(){
            
            var ship_id = $(this).val();
            window.location = '{{url("/transporter/shipment/past")}}/'+ship_id+'/details';
        });

</script>

@endsection