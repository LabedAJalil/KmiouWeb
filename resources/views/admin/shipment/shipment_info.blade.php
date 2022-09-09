<style type="text/css" media="screen">
    .upload-doc{
        border-radius: 10px;
        max-width: 100px;
        width: 60px;
        height: 60px;
        border: 2px solid #b5b5b5;
    }
</style>

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
                                                <!-- <div class="pickup-info">
                                                    <label class="det-label">Mobile No: </label>
                                                    <p class="pick-p">+1 621-12-123</p>
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
                                                <!-- <div class="drop-info">
                                                    <label class="det-label">Shipping Documents: </label>
                                                    <a class="ship-doc" href="#">Download</a>
                                                </div> -->
                                                <div class="pickup-info">
                                                    <label class="det-label">Loading Documents: </label>
                                                    <a data-toggle="modal" data-target="#exampleModalCenter" href="javascript:void(0);" ><img class="upload-doc" src="{{asset('public/img/doc.jpg')}}"></a>
                                                    <a data-toggle="modal" data-target="#exampleModalCenter" href="javascript:void(0);" ><img class="upload-doc" src="{{asset('public/img/doc.jpg')}}"></a>
                                                </div>
                                                <div class="pickup-info unload-doc">
                                                    <label class="det-label">Unloading Documents: </label>
                                                    <a data-toggle="modal" data-target="#exampleModalCenter" href="javascript:void(0);" ><img class="upload-doc" src="{{asset('public/img/doc.jpg')}}"></a>
                                                    <a data-toggle="modal" data-target="#exampleModalCenter" href="javascript:void(0);" ><img class="upload-doc" src="{{asset('public/img/doc.jpg')}}"></a>
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
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Bid Amount: </label>
                                                    <input type="number" value="" class="form-control edit-pro" placeholder="Enter amount">
                                                </div>
                                                <!-- <div class="view-more">
                                                    <a href="view">View More</a>
                                                </div> -->
                                                <div class="full-width text-center">
                                                    <a href="javascript:void(0);" class="btn btn-primary mb-4">Place Bid</a>
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
        </section>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->

@endsection
