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
                                        <h5 class="m-b-10">Support Numbers</h5>
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
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <form action="{{route('updatesupportnumber')}}" method="post">
                                                {{csrf_field()}}

                                                <input type="hidden" name="id" value="{{$data[0]->id}}" >
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Shippers & Transporter : </label>
                                                    <input type="text" value="{{$data[0]->shipper_transporter}}" name="shipper_transporter" class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Driver : </label>
                                                    <input type="text" value="{{$data[0]->driver}}" name="driver" class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <!-- <div class="pickup-info edit-info">
                                                    <label class="det-label">Subject: </label>
                                                    <input type="text" value=""  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Your Message: </label>
                                                    <textarea class="form-control edit-pro" id="exampleFormControlTextarea1" placeholder="Enter Here" rows="3"></textarea>
                                                </div> -->
                                                <!-- <div class="view-more">
                                                    <a href="view">View More</a>
                                                </div> -->
                                                <div class="full-width text-center">
                                                    <button  type="submit" class="btn btn-primary mb-4">Update</button>
                                                </div>
                                                </form>
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
