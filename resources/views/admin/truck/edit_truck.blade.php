@section('css-section')
<style type="text/css" media="screen">
    .upload-doc{
        border-radius: 10px;
        max-width: 100px;
        width: 60px;
        height: 60px;
        border: 2px solid #b5b5b5;
    }
</style>

@endsection

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
                                        <h4 class="card-title mb-0">Edit Truck Details</h4>
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

                                            <form class="form" method="post" action="{{route('updateTruck')}}" enctype="multipart/form-data" data-parsley-validate="">
                                               {{csrf_field()}}

                                               <input type="hidden" name="truck_id" value="{{$data->id}}" >

                                                <div class="form-body">
                                                    <div class="row" style="max-width: 450px;">

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Truck Name(English)</label>
                                                                <input type="text" name="truck_name" class="form-control" id="truck_name" required="" value="{{$data->truck_name}}">
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Truck Name(French)</label>
                                                                <input type="text" name="truck_name_fr" class="form-control" id="truck_name_fr" required="" value="{{$data->truck_name_fr}}">
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Truck Name(Arabic)</label>
                                                                <input type="text" name="truck_name_ar" class="form-control" id="truck_name_ar" required="" value="{{$data->truck_name_ar}}">
                                                            </fieldset>
                                                        </div>
                                                        @if($truck_capacity !=[])

                                                     @foreach($truck_capacity as $key=>$value)

                                                        <div class="col-md-6 add_to_do_div">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Truck Capacity (Kg/Ton)</label>
                                                                <input type="number"  min="1" step="0.5" name="truck_capacity[]" class="form-control" id="capacity"  value="{{$value->truck_capacity}}">
                                                            </fieldset>
                                                            <!-- <a href="javascript:void();" class="float-center" id="btn_add_to_do">+ Add</a> -->
                                                        </div>

                                                        <div class="col-md-6 add_to_do_div_ca">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Weight Type</label>
                                                                <select class="form-control weight_type" name="weight_type[]" required="">
                                                                <option value="0" @if($value->weight_type == '0') selected @endif >Kg</option>
                                                                <option value="1" @if($value->weight_type == '1') selected @endif > Ton </option>
                                                            </select>
                                                            </fieldset>
                                                        </div>
                                                        <!-- <div class="col-md-6 add_to_do_div">
                                                            </div> -->
                                                        @endforeach
                                                        @else

                                                        <div class="col-md-6 add_to_do_div">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Truck Capacity (Kg/Ton)</label>
                                                                <input type="number"  min="1" step="0.5" name="capacity[]" class="form-control" id="capacity"  value="{{$data->capacity}}" required="">
                                                            </fieldset>
                                                             <!-- <a href="javascript:void();" class="float-center" id="btn_add_to_do">+ Add</a> -->
                                                        </div>

                                                        <div class="col-md-6 add_to_do_div_ca">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Weight Type</label>
                                                                <select class="form-control weight_type" name="weight_type[]" required="">
                                                                <option value="0" @if($data->weight_type == '0') selected @endif >Kg</option>
                                                                <option value="1" @if($data->weight_type == '1') selected @endif >Ton</option>
                                                            </select>
                                                            </fieldset>
                                                        </div>
                                                        @endif
                                                        <a href="javascript:void();" class="float-center" id="btn_add_to_do">+ Add</a>

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                            @foreach($doc as $doc)
                                                                <a href="javascript:void(0);"><img class="upload-doc truck_images" src="{{$doc}}"></a>
                                                            @endforeach
                                                                <label style="display: block;" for="basicInput">Truck Image: </label>
                                                                <input type="file" id="truck_img" name="truck_img" placeholder="choose image" accept=".jpg,.jpeg,.png" value="{{$data->truck_img}}">

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

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
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
                    <img class="upload-doc-modl" src="{{asset('public/img/doc.jpg')}}" id="ModalImage">
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

<!-- modal end -->

@endsection

 @section('js-section')
<script type="text/javascript">
    var to_do_count = 0;

$(document).on("click","#btn_add_to_do",function(){

        to_do_count = (to_do_count+1);
       //  alert(to_do_count);
           $('.add_to_do_div').append('<div class="add_to_do_div'+to_do_count+'"><input type="number" placeholder="Enter Truck Capacity" class="form-control input-md" name="truck_capacity[]" required id="to_do_'+to_do_count+'"></div>');
           $('.add_to_do_div_ca').append('<div class="add_to_do_div_ca'+to_do_count+'"><a href="javascript:;" class="remove_to_do float-right" data-count="'+to_do_count+'" id="remove_to_do_'+to_do_count+'"><i class="fa fa-trash"></i></a><select class="form-control input-md" name="weight_type[]" required id="to_do_'+to_do_count+'"><option value="0">Kg</option><option value="1"> Ton </option></select><br></div>');



     });

     $(document).on("click",".remove_to_do",function(){
         to_do_count = (to_do_count-1);
           var count = $(this).data('count');
           $('.add_to_do_div'+count).remove();
           $('.add_to_do_div_ca'+count).remove();
     });

    $(document).on("click",".truck_images",function(){

        var img = $(this).attr('src');

        $('#ModalImage').attr("src",img);

        $('#exampleModalCenter').modal('show');
    });


</script>
@endsection
