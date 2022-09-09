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
                                        <h4 class="card-title mb-0">Edit Surge Price</h4>
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
           
                                            <form class="form" method="post" action="{{route('updateSurgePrice')}}" enctype="multipart/form-data" data-parsley-validate="">
                                               {{csrf_field()}}

                                               <input type="hidden" name="price_id" value="{{$data->id}}" >
                                               
                                                <div class="form-body">
                                                    <div class="row" style="max-width: 450px;">
                                                        
                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Total Difference Hours</label>
                                                                <input type="text" name="total_diff_hours" class="form-control" id="total_diff_hours" required="" value="{{$data->total_diff_hours}}">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Price Per Hour</label>
                                                                <input type="text" name="price_per_hour" class="form-control" id="price_per_hour"  value="{{$data->price_per_hour}}">
                                                            </fieldset>
                                                        </div>

                                                      <div class="col-md-12">
                                                          <fieldset class="form-group">
                                                              <label for="basicInput">Type</label>
                                                              <select class="form-control type" name="type" required="">
                                                                <option value="0" @if($data->type == '0') selected @endif >Pickup</option>
                                                                <option value="1" @if($data->type == '1') selected @endif > Drop </option>
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

    $(document).on("click",".truck_images",function(){
            
        var img = $(this).attr('src');
        
        $('#ModalImage').attr("src",img);

        $('#exampleModalCenter').modal('show');
    });


</script>
@endsection
