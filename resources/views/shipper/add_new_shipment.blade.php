@extends('shipper.common.master')
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
                                        <h5 class="m-b-10">Enter Booking Details</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Select Quotation Type: </label>
                                                    <!-- <input type="checkbox" class="custom-checkbox" id="customCheck1"> -->
                                                        <select class="form-control noti-sel" id="exampleFormControlSelect1">
                                                    <option>Auction</option>
                                                    <option>Fixed</option>
                                                    <option>Instant Quote</option>
                                                </select>
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Select Transport Type: </label>
                                                    <!-- <input type="checkbox" class="custom-checkbox" id="customCheck1"> -->
                                                        <select class="form-control noti-sel" id="exampleFormControlSelect1">
                                                    <option>Local</option>
                                                    <option>Import</option>
                                                    <option>Export</option>
                                                </select>
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Select Pick-up Date: </label>
                                                    <input type="date" value="Alex"  class="form-control edit-pro" placeholder="Select">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Select Goods Type: </label>
                                                    <input type="text" value=""  class="form-control edit-pro" placeholder="Search...">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Estimated Weight: </label>
                                                    <input type="text" value=""  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <!-- <div class="pickup-info edit-info">
                                                    <label class="det-label">Sender & Receiver info: </label>
                                                    <input type="email" value=""  class="form-control edit-pro" placeholder="Enter here">
                                                </div> -->
                                                <div class="pickup-info">
                                                    <label class="det-label">Sender & Receiver info: </label>
                                                    <a class="det-label" data-toggle="modal" data-target="#exampleModalCenter" href="#" >Enter Here</a>
                                                </div>
                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Enter info</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Sender Details</h5>
                                                        <div class="row">
                                                            <div class="col-md-12 driv-block">
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">Name: </label>
                                                                    <input type="text" value="Alex"  class="form-control edit-pro" placeholder="Enter here">
                                                                </div>
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">Mobile: </label>
                                                                    <input type="text" value="Alex"  class="form-control edit-pro" placeholder="Enter here">
                                                                </div>
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">Email: </label>
                                                                    <input type="text" value="Alex"  class="form-control edit-pro" placeholder="Enter here">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <h5 style="margin-top: 5px;" class="modal-title" id="exampleModalLongTitle">Receiver Details</h5>
                                                        <div class="row">
                                                            <div class="col-md-12 driv-block">
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">Name: </label>
                                                                    <input type="text" value="Alex"  class="form-control edit-pro" placeholder="Enter here">
                                                                </div>
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">Mobile: </label>
                                                                    <input type="text" value="Alex"  class="form-control edit-pro" placeholder="Enter here">
                                                                </div>
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">Email: </label>
                                                                    <input type="email" value="Alex"  class="form-control edit-pro" placeholder="Enter here">
                                                                </div>
                                                            </div>
                                                            <div class="full-width text-center">
                                                <button class="btn btn-primary mb-4">Add</button>
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
                                                    <label class="det-label">Upload Document: </label>
                                                    <input type="file" value=""  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Add Instructions: </label>
                                                    <textarea class="form-control edit-pro" id="exampleFormControlTextarea1" placeholder="Enter Here" rows="3"></textarea>
                                                </div>
                                                <!-- <div class="view-more">
                                                    <a href="view">View More</a>
                                                </div> -->
                                                <div class="full-width text-center">
                                                    <button class="btn btn-primary mb-4">Send Request</button>
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
        </div>
    </div>
    <!-- [ Main Content ] end -->

@endsection