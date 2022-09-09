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
                                        <h5 class="m-b-10">Edit Profile</h5>
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
                                                <div class="user-img">
                                                    <label class="det-label">Profile Pic: </label>
                                                    <img class="profile-logo" src="{{asset('public/images/user1.png')}}">
                                                    <a href="#">Change</a>
                                                </div>
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">First Name: </label>
                                                    <input type="text" value="Alex"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Last Name: </label>
                                                    <input type="text" value="Goex"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Mobile No: </label>
                                                    <input type="text" value="+1 312 12 121"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Email: </label>
                                                    <input type="email" value="alex28382@gmail.com"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">City: </label>
                                                    <input type="email" value="Dallas"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">State: </label>
                                                    <input type="email" value="Texas"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Carrier number: </label>
                                                    <input type="email" value="ASH212AW"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Push Notification: </label>
                                                    <!-- <input type="checkbox" class="custom-checkbox" id="customCheck1"> -->
                                                        <select class="form-control noti-sel" id="exampleFormControlSelect1">
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
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
