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
                                        <h4 class="card-title mb-0">New Coupon</h4>
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
           
                                            <form class="form" method="post" action="{{route('addNewCoupon')}}" enctype="multipart/form-data" data-parsley-validate="">
                                               {{csrf_field()}}
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Title</label>
                                                                <input type="text" name="title" class="form-control" id="title" required="">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Coupon Code</label>
                                                                <input type="text" name="coupon_code" class="form-control" minlength="6" maxlength="6" id="coupon_code" required="">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Start Date</label>
                                                                <input type="date" name="start_date" class="form-control start_date" id="start_date" required="" >
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-xl-4 col-lg-6 col-md-12 mb-1">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">End Date</label>
                                                                <input type="date" name="end_date" class="form-control end_date" id="end_date" required="" >
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Discount</label>
                                                                <input type="number" name="discount" class="form-control" id="discount" required="" maxlength="3" min="1" max="100">
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

 @section('js-section')  
<script type="text/javascript">

      //date operations
    $(function(){
            var dtToday = new Date();
            
            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();
            
            var maxDate = year + '-' + month + '-' + day;
            $('.start_date').attr('min', maxDate);
            $('.end_date').attr('min', maxDate);

        });


    $('.start_date').change(function(){

         var dtToday = new Date($('.start_date').val());
            
            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();
            
            var maxDate = year + '-' + month + '-' + day;
        $('.end_date').attr('min', maxDate);
         
    });

     $('.end_date').change(function(){

         var dtToday = new Date($('.end_date').val());
            
            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();
            
            var maxDate = year + '-' + month + '-' + day;
        $('.start_date').attr('max', maxDate);
         
    });

</script>
@endsection
