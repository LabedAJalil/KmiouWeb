@section('css-section')


<style>

.upload-doc
{
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
                                 <!-- Alert Message -->  
                                  <div class="flash-message">
                                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                      @if(Session::has('alert-' . $msg))
                                      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                      @endif
                                    @endforeach
                                  </div>
                                <!-- Alert Message -->

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Update Transporter Info</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="px-3">
                                            <form class="form" method="post" action="{{route('transporterUpdate')}}" enctype="multipart/form-data">
                                                {{csrf_field()}}

                                                 <input type="hidden" name="id" value="{{($data) ? $data['id']: ''}}">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">First Name</label>
                                                               <input type="text" name="first_name"  value=" {{($data) ? $data['first_name']: ''}}" class="form-control" id="basicInput">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-6">
                                                            
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Last Name</label>
                                                               <input type="text" name="last_name"  value=" {{($data) ? $data['last_name']: ''}}" class="form-control" id="basicInput">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-6">
                                                            
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Phone Number</label>
                                                               <input type="text" name="mobile_no"  value=" {{($data) ? $data['mobile_no']: ''}}" class="form-control" id="basicInput" data-parsley-error-message="Invalid Mobile Number" maxlength="11">
                                                            </fieldset>
                                                        </div>
                                                         
                                                        <div class="col-md-6">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Address</label>
                                                             
                                                            <select class="form-control" name="shipping_city"  id="shipping_city" required>
                                                             @foreach($city as $key => $value)
                                                            <option value="{{$value->id}}">{{$value->city_name}}</option>
                                                            @endforeach
                                                            </select>
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-6">
                                                            
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Company Name</label>
                                                               <input type="text" name="company_name"  value=" {{($data) ? $data['company_name']: ''}}" class="form-control" id="basicInput">
                                                            </fieldset>
                                                        </div>
                                                        

                                                        <div class="col-xl-6 col-lg-6 col-md-12 mb-1">
                                                           <div class="pickup-info edit-info">
                                                            <label class="det-label">Company Document: </label>
                                                            <?php
                                                                $doc = array();

                                                               if($data && $data->doc != '' && $data->doc != null){

                                                                  $str = $data->doc;

                                                                  $doc = explode ("#####", $str);
                                                               }
                                                            ?>
                                                            @foreach($doc as $doc)
                                                                <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$doc}}"></a>
                                                            @endforeach
                                                        </div>
                                                        <div class="pickup-info edit-info">
                                                            <label class="det-label">Upload Document: </label>
                                                            <input type="file" name="doc[]" class="form-control edit-pro" value="{{$data->doc}}" multiple="" accept=".jpg,.jpeg,.png">
                                                        </div>
                                                        </div>

                                                        <div class="col-xl-6 col-lg-6 col-md-12 mb-1">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Carrier Number</label>
                                                                <input type="text" name="carrier_number" value=" {{($data) ? $data['carrier_number']: ''}}" class="form-control" id="basicInput">
                                                            </fieldset>
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            <div class="form-actions text-center">
                                                                <button type="submit" class="btn btn-raised btn-raised btn-primary text-align-center">update
                                                                </button>
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
    

        $(document).on("click",".vehicle_document",function(){
            
            var img = $(this).attr('src');
            
            $('#ModalImage').attr("src",img);

            $('#exampleModalCenter').modal('show');
        });

        function readURL(input) {

            if (input.files && input.files[0]) {
            var reader = new FileReader();

                reader.onload = function(e) {
                $('.profile_pic').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }


</script>

@endsection