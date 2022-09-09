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
                                        <h5 class="m-b-10">Rate Shipment</h5>
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
                                <div class="col-md-6">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp rate-sec">
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <div class="pickup-info edit-info">
                                                    <i class="feather icon-star-on rated"></i>
                                                    <i class="feather icon-star-on rated"></i>
                                                    <i class="feather icon-star-on rated"></i>
                                                    <i class="feather icon-star-on rated"></i>
                                                    <i class="feather icon-star-on"></i>
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <!-- <label class="det-label">Your Message: </label> -->
                                                    <textarea class="form-control edit-pro rate-text" id="exampleFormControlTextarea1" placeholder="How was your Shipment? Please explain us in details…" rows="3"></textarea>
                                                </div>
                                                </div>
                                                <!-- <div class="view-more">
                                                    <a href="view">View More</a>
                                                </div> -->
                                                <div class="full-width text-center">
                                                    <button class="btn btn-primary mb-4">Save</button>
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
