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
                                                    <label class="det-label">Driver info: </label>
                                                    <div class="user-img">
                                                    <img class="user-logo" src="{{asset('public/images/dri.png')}}">
                                                    <p class="user-name">Peter Mcknion</p>
                                                    </div>
                                                </div>
                                                <div class="pickup-info">
                                                    <label class="det-label">Driver Mobile No: </label>
                                                    <p class="pick-p">+1 621-12-123</p>
                                                </div>
                                                <div class="pickup-info">
                                                    <label class="det-label">Shipper Mobile No: </label>
                                                    <p class="pick-p">+1 621-12-123</p>
                                                </div>
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
                                                    <label class="det-label">Weight: </label>
                                                    <p class="pick-p">20 Tons</p>
                                                </div>
                                                <div class="drop-info">
                                                    <label class="det-label">Goods Type: </label>
                                                    <p class="pick-p">Food</p>
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

                                                <div class="pickup-info mt-15">
                                                    <label class="det-label">Track Shipment: </label>
                                                    <a  class="ship-doc" data-toggle="modal" data-target="#exampleModalCenter2" href="#" >Track</a>
                                                </div>

                                                 <div class="pickup-info mt-15">
                                                    <label class="det-label">Report Emergency: </label>
                                                    <a  class="ship-doc" data-toggle="modal" data-target="#exampleModalEmergency" href="#" >Help</a>
                                                </div>

                                                <!--track Modal -->
                                                
                                                <div class="modal fade" id="exampleModalCenter2" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="319" height="608" viewBox="0 0 319 608">
                                                                      <defs>
                                                                      </defs>
                                                                      <g id="Group_5363" data-name="Group 5363" transform="translate(-12741 -916)">
                                                                        <g id="Group_5362" data-name="Group 5362">
                                                                          <g id="Group_5190" data-name="Group 5190" transform="translate(566 554)">
                                                                            <g id="Group_5187" data-name="Group 5187">
                                                                              <g id="Group_5183" data-name="Group 5183" transform="translate(16059 -301)">
                                                                                <line id="Line_24" data-name="Line 24" class="cls-1" y2="135" transform="translate(-3872 822)"/>
                                                                                <circle id="Ellipse_29" data-name="Ellipse 29" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 806)"/>
                                                                                <circle id="Ellipse_571" data-name="Ellipse 571" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 942)"/>
                                                                              </g>
                                                                              <g id="Group_5185" data-name="Group 5185" transform="translate(16059 -164)">
                                                                                <line id="Line_24-2" data-name="Line 24" class="cls-1" y2="135" transform="translate(-3872 822)"/>
                                                                                <circle id="Ellipse_29-2" data-name="Ellipse 29" class="cls-3" cx="12" cy="12" r="12" transform="translate(-3884 805)"/>
                                                                                <circle id="Ellipse_571-2" data-name="Ellipse 571" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 942)"/>
                                                                              </g>
                                                                              <g id="Group_5186" data-name="Group 5186" transform="translate(16059 -27)">
                                                                                <line id="Line_24-3" data-name="Line 24" class="cls-4" y2="135" transform="translate(-3872 822)"/>
                                                                                <circle id="Ellipse_29-3" data-name="Ellipse 29" class="cls-3" cx="12" cy="12" r="12" transform="translate(-3884 805)"/>
                                                                                <circle id="Ellipse_571-3" data-name="Ellipse 571" class="cls-2" cx="12" cy="12" r="12" transform="translate(-3884 942)"/>
                                                                              </g>
                                                                              <g id="Group_5184" data-name="Group 5184" transform="translate(16002 -437)">
                                                                                <line id="Line_31" data-name="Line 31" class="cls-1" y2="135" transform="translate(-3815 822)"/>
                                                                                <circle id="Ellipse_572" data-name="Ellipse 572" class="cls-3" cx="12" cy="12" r="12" transform="translate(-3827 806)"/>
                                                                                <circle id="Ellipse_573" data-name="Ellipse 573" class="cls-3" cx="12" cy="12" r="12" transform="translate(-3827 942)"/>
                                                                              </g>
                                                                            </g>
                                                                          </g>
                                                                          <text id="Ordered_Sun_5th_June_20_" data-name="Ordered 
                                                                    Sun, 5th June 20
                                                                    " class="cls-5" transform="translate(12802 916)"><tspan class="cls-6"><tspan x="0" y="30">Ordered</tspan><tspan class="cls-7" y="30"> </tspan></tspan><tspan class="cls-8"><tspan x="0" y="54">Sun, 5th June 20</tspan></tspan></text>
                                                                          <text id="Driver_Accepted_Sun_5th_June_20_" data-name="Driver Accepted
                                                                    Sun, 5th June 20
                                                                    " class="cls-5" transform="translate(12802 1046)"><tspan class="cls-6"><tspan x="0" y="30">Driver Accepted</tspan></tspan><tspan class="cls-8"><tspan x="0" y="54">Sun, 5th June 20</tspan></tspan></text>
                                                                          <text id="Truck_On_The_Way_Sun_5th_June_20_" data-name="Truck On The Way
                                                                    Sun, 5th June 20
                                                                    " class="cls-5" transform="translate(12802 1187)"><tspan class="cls-6"><tspan x="0" y="30">Truck On The Way</tspan></tspan><tspan class="cls-8"><tspan x="0" y="54">Sun, 5th June 20</tspan></tspan></text>
                                                                          <text id="Shipped_" data-name="Shipped
                                                                    " class="cls-9" transform="translate(12802 1328)"><tspan x="0" y="30">Shipped</tspan><tspan class="cls-8" y="30"></tspan><tspan class="cls-8"><tspan x="0" y="54"></tspan></tspan></text>
                                                                          <text id="Delivered" class="cls-9" transform="translate(12802 1458)"><tspan x="0" y="30">Delivered</tspan></text>
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

                                                <!--Emergency Modal -->                                                
                                                <div class="modal fade" id="exampleModalEmergency" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Report Emergency</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 driv-block">
                                                                <label>Security Emergency </label>
                                                                <input type="checkbox"class="canc-check float-right">
                                                            </div>
                                                            <div class="col-md-12 driv-block">
                                                                <label>Engine Problem </label>
                                                                <input type="checkbox"class="canc-check float-right">
                                                            </div>
                                                            <div class="col-md-12 driv-block">
                                                                <label>Fuel Over </label>
                                                                <input type="checkbox"class="canc-check float-right ">
                                                            </div>
                                                            <div class="col-md-12 driv-block">
                                                                <label>Truck Tire Flat </label>
                                                                <input type="checkbox"class="canc-check float-right">
                                                            </div>
                                                            <div class="col-md-12 driv-block">
                                                                <label>Other </label>
                                                                <input type="checkbox"class="canc-check float-right">
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
                                                <!--Emergency Modal -->


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
                                                <!-- <div class="drop-info">
                                                    <label class="det-label">Shipping Documents: </label>
                                                    <a class="ship-doc" href="#">Download</a>
                                                </div> -->
                                                <!-- <div class="view-more">
                                                    <a href="view">View More</a>
                                                </div> -->
                                                <div class="full-width text-center">
                                                    <a data-toggle="modal" data-target="#exampleModalCentercan" href="#" class="btn btn-danger mb-4">Cancel</a>
                                                    <a href="#" class="btn btn-primary mb-4">On My Way</a>
                                                </div>
                                                <!-- modal -->
                                                <div class="modal fade" id="exampleModalCentercan" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Cancel Shipment</h5>
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
                                                            <div class="col-md-12 driv-block">
                                                                <label>Other </label>
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
                                                    <p class="pick-p">79ع.د </p>
                                                </div>
                                                <div class="invoice-info">
                                                    <label class="det-label total-lab">Total Fare: </label>
                                                    <p class="pick-p total-lab">154.75ع.د </p>
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
@endsection
