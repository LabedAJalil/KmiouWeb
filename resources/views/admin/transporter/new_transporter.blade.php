@extends('admin.common.master')
@section('main-content')
<div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- Basic Elements start -->
                    <section class="basic-elements">
                        <div class="row">
                            <div class="col-12">
                                <div class="content-header"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">New Transporter</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="px-3">
                                             @if(count($errors))
                                                 <div class="form-group">
                                                     <div class="alert alert-danger">
                                                             <ul>
                                                                 @foreach($errors->all() as $error)
                                                                         <li>{{$error}}</li>
                                                                  @endforeach
                                                             </ul>
                                                     </div>
                                                 </div>
                                            @endif

                                             <!-- Alert Message -->  
                                                  <div class="flash-message">
                                                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                                      @if(Session::has('alert-' . $msg))
                                                      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                                      @endif
                                                    @endforeach
                                                  </div>
                                                <!-- Alert Message -->
           
                                            <form class="form" method="post" action="{{route('addNewUser')}}" enctype="multipart/form-data" data-parsley-validate="">
                                               {{csrf_field()}}
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">First Name</label>
                                                                <input type="text" name="first_name" class="form-control" id="first_name" required="">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Last Name</label>
                                                                <input type="text" name="last_name" class="form-control" id="last_name" required="">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Email</label>
                                                                <input type="email" name="email" class="form-control" id="basicInput" required="" >
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Phone Number</label>
                                                                <input type="number" name="mobile_no" class="form-control" id="mobile_no" required="" maxlength="11" data-parsley-error-message="Invalid Mobile Number">
                                                            </fieldset>
                                                        </div>


                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">City </label>
                                                                <input type="text" name="city" class="form-control" id="city" required="">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">State</label>
                                                                <input type="text" name="state" class="form-control" id="state" required="">
                                                            </fieldset>
                                                        </div>

                                                         <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">User Type</label>
                                                                <select name="user_type" class="form-control" id="state" required="">
                                                                    <option value="" selected disabled="">- select user type-</option>
                                                                    <option value="2">Shipper</option>
                                                                    <option value="3">Driver</option>
                                                                    <option value="4">Transporter</option>
                                                                    
                                                                </select>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Company Name</label>
                                                                <input type="text" name="company_name" class="form-control" id="company_name" >
                                                            </fieldset>
                                                        </div>


                                                       <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Carrier Number</label>
                                                                <input type="text" name="carrier_number" class="form-control" id="carrier_number" >
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Address</label>
                                                                <textarea name="address" class="form-control" id="address" required="" rows="5" cols="20"></textarea>
                                                            </fieldset>
                                                        </div>
                                        
                                        <div class="col-md-12">
                                            <img src="" id="profile-img-tag" width="200px" />
                                        <div class="col-md-12">
                                        <div class="form-actions">
                                        <button type="submit" class="btn btn-raised btn-raised btn-primary">
                                        <i class="fa fa-check-square-o"></i> Save
                                        </button>
                                        </div>
                                        </div>
                                        </div>                            
                                                </div>
                                             </div>
                                                
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- Basic Inputs end -->
                </div>
            </div>
        </div>
    </div>
            
@endsection
