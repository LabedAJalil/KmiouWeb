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
                                        <h4 class="card-title mb-0">New Surge Price</h4>
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
           
                                            <form class="form" method="post" action="{{route('addNewSurgePrice')}}" enctype="multipart/form-data" data-parsley-validate="">
                                               {{csrf_field()}}
                                                <div class="form-body">
                                                    <div class="row" style="max-width: 450px;">

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Total Difference Hours</label>
                                                                <input type="text" name="total_diff_hours" class="form-control" id="total_diff_hours" required="">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Price Per Hour</label>
                                                                <input type="text" name="price_per_hour" class="form-control" id="price_per_hour" >
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Type</label>
                                                                <select class="form-control type" name="type" required="">
                                                                  <option value="0">Pickup</option>
                                                                  <option value="1">Drop</option>
                                                              </select>
                                                            </fieldset>
                                                        </div>

                                                        
                                                    <div class="col-md-12 text-left">
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

 @section('js-section')  
<script type="text/javascript">


</script>
@endsection
