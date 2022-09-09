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
                                        <h4 class="card-title mb-0">New Truck</h4>
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

                                            <form id="addNewTruck" class="form" method="post" action="{{route('addNewTruck')}}" enctype="multipart/form-data" data-parsley-validate="">
                                               {{csrf_field()}}
                                                <div class="form-body">
                                                    <div class="row" style="max-width: 450px;">

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Truck Name(English)</label>
                                                                <input type="text" name="truck_name" class="form-control" id="truck_name" required="">
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Truck Name(French)</label>
                                                                <input type="text" name="truck_name_fr" class="form-control" id="truck_name_fr" required="">
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Truck Name(Arabic)</label>
                                                                <input type="text" name="truck_name_ar" class="form-control" id="truck_name_ar" required="">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-6 add_to_do_div">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Truck Capacity (Kg/Ton)</label>
                                                                <input type="number" min="1" step="0.5" name="capacity[]" class="form-control" id="capacity" required="">
                                                            </fieldset>
                                                        </div>

                                                         <div class="col-md-6 add_to_do_div1">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Weight Type</label>
                                                                <select class="form-control weight_type"  name="weight_type[]" id="weight_type"required="">
                                                                    <option value="0">Kg</option>
                                                                    <option value="1"> Ton </option>
                                                                </select>
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <a href="javascript:void();" class="float-center" id="btn_add_to_do">+ Add</a>
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label style="display: block;" for="basicInput">Truck Image: </label>
                                                                <input type="file" id="truck_img" name="truck_img" required="" placeholder="choose image" accept=".jpg,.jpeg,.png">
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
 <!-- <script src="{{asset('public/js/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('public/js/parsley.min.js')}}"></script>    -->
 <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
<script type="text/javascript">

var to_do_count = 0;

 $(document).on("click","#btn_add_to_do",function(){

         to_do_count = (to_do_count+1);
        //  alert(to_do_count);
            $('.add_to_do_div').append('<div class="div_to_do_'+to_do_count+'" style="margin-top: 40px;"><a href="javascript:;" class="remove_to_do float-right" data-count="'+to_do_count+'" id="remove_to_do_'+to_do_count+'"></a><input type="number" placeholder="Enter Truck Capacity" class="form-control input-md" name="capacity[]" required id="to_do_'+to_do_count+'"></div>');
            $('.add_to_do_div1').append('<div class="div_to_do_'+to_do_count+'"><a href="javascript:;" class="remove_to_do float-right" data-count="'+to_do_count+'" id="remove_to_do_'+to_do_count+'"><i class="fa fa-trash"></i></a><select class="form-control input-md" name="weight_type[]" required id="to_do_'+to_do_count+'"><option value="0">Kg</option><option value="1"> Ton </option></select><br></div>');



      });

      $(document).on("click",".remove_to_do",function(){
          to_do_count = (to_do_count-1);
            var count = $(this).data('count');
            $('.div_to_do_'+count).remove();
            // $('.div1_to_do_'+count).remove();
      });
    //   $('#addNewTruck').parsley();

</script>
@endsection
