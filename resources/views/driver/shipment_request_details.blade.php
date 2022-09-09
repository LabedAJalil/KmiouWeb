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
                                <h2>Shipment Request Details</h2>
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
                                        <h5 class="m-b-10">Shipment ID:102231231</h5>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="book-date-sec">
                                                    <p class="book-date">23 May, 2020 15:20</p>
                                                </div>
                                                <div class="user-img">
                                                    <img class="user-logo" src="{{asset('public/images/user1.png')}}">
                                                    <p class="user-name">Alex Christano</p>
                                                </div>
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <div class="pickup-info">
                                                    <label class="det-label">Pickup Location: </label>
                                                    <p class="pick-p">32 St Marks Pl, New York, NY 10003,</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Drop Location: </label>
                                                    <p class="pick-p">32 St Marks Pl, New York, NY 10003,</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Shipment ID: </label>
                                                    <p class="pick-p">102231231</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Date & Time: </label>
                                                    <p class="pick-p">19, Aug 2020 - 02:00 AM</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Service Type: </label>
                                                    <p class="pick-p">Semi Trailer 300</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Number of Vehicle : </label>
                                                    <p class="pick-p">2</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Goods Type: </label>
                                                    <p class="pick-p">Food</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Weight: </label>
                                                    <p class="pick-p">20 Tons</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Instruction: </label>
                                                    <p class="pick-p">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry’s standard. </p>
                                                </div>
                                                <div class="pickup-info">
                                                    <label class="det-label">Loading Documents: </label>
                                                    <a data-toggle="modal" data-target="#exampleModalCenter" href="#" ><img class="upload-doc" src="{{asset('public/img/doc.jpg')}}"></a>
                                                    <a data-toggle="modal" data-target="#exampleModalCenter" href="#" ><img class="upload-doc" src="{{asset('public/img/doc.jpg')}}"></a>
                                                </div>
                                                <div class="pickup-info unload-doc">
                                                    <label class="det-label">Unloading Documents: </label>
                                                    <a data-toggle="modal" data-target="#exampleModalCenter" href="#" ><img class="upload-doc" src="{{asset('public/img/doc.jpg')}}"></a>
                                                    <a data-toggle="modal" data-target="#exampleModalCenter" href="#" ><img class="upload-doc" src="{{asset('public/img/doc.jpg')}}"></a>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                                                <img class="upload-doc-modl" src="{{asset('public/img/doc.jpg')}}">
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
                                                <div class="full-width text-center">
                                                    <a href="javascript:void(0);" class="btn btn-danger mb-4">Reject</a>
                                                    <a href="javascript:void(0);" class="btn btn-primary mb-4">Accept</a>
                                                </div>
                                                <!-- modal -->
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
                                                        <div class="row">
                                                            <div class="col-md-12 driv-block">
                                                                <label>Accident </label>
                                                                <input type="checkbox"class="canc-check">
                                                            </div>
                                                            <div class="col-md-12 driv-block">
                                                                <label>Engine Problem </label>
                                                                <input type="checkbox"class="canc-check">
                                                            </div>
                                                            <div class="col-md-12 driv-block">
                                                                <label>Fuel Over </label>
                                                                <input type="checkbox"class="canc-check">
                                                            </div>
                                                            <div class="col-md-12 driv-block">
                                                                <label>Medical Emergency </label>
                                                                <input type="checkbox"class="canc-check">
                                                            </div>
                                                            <div style="margin-top: 15px" class="col-md-12">
                                                                <textarea class="form-control" placeholder="Leave Comment...." name=""></textarea>
                                                            </div>
                                                            <div style="margin-top: 15px" class="col-md-12 text-center">
                                                                <a href="#" class="btn btn-primary mb-4">Submit</a>
                                                            </div>
                                                        </div>
                                                      </div>
                                                      <!-- <div class="modal-footer text-center">
                                                        <button type="button" class="btn btn-primary">Select</button>
                                                      </div> -->
                                                    </div>
                                                  </div>
                                                </div>
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
                            <div class="col-md-8">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="invoice-info">
                                                    <label class="det-label">Payment Method: </label>
                                                    <p class="pick-p card-text">Card</p>
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
                                                    <p class="pick-p total-lab">$154.75</p>
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

@endsection