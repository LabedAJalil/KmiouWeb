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
                                        <h4 class="card-title mb-0">Update Shipper Info</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="px-3">
                                            <form class="form" method="post" action="{{route('userUpdate')}}" enctype="multipart/form-data">
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
                                                        
                                                        <div class="col-xl-6 col-lg-6 col-md-12 mb-1">

                                                                <label style="display:block;" for="cash_card">Payment Type </label>
                                                            <fieldset class="form-group">
                                                                <label for="via_transfer">Cash / Card <input type="radio" name="payment_type" value="0" class="" id="cash_card" 
                                                                <?php if($data['payment_type'] == '0'){ echo 'checked';}    ?>> </label>
                                                                <label style="display:block;" for="via_transfer">Via Transfer  <input type="radio" name="payment_type" value="1" 
                                                                <?php if($data['payment_type'] == '1'){ echo 'checked';}    ?>
                                                                 class="" id="via_transfer"> </label>
                                                            </fieldset>
                                                        </div>
                                                        
                                                      <div class="col-md-3">
                                                          <fieldset class="form-group">
                                                              <label for="basicInput">Bear Commission</label>
                                                              <select class="form-control commission_type" name="is_commission" required="">
                                                                <option value="0" @if($data->is_commission == '0') selected @endif >No</option>
                                                                <option value="1" @if($data->is_commission == '1') selected @endif > Yes </option>
                                                            </select>
                                                          </fieldset>
                                                      </div>
                                                    
                                                        <div class="col-md-3 commission_percent">
                                                          <fieldset class="form-group">
                                                              <label for="commission_percent">Commission (%)</label>
                                                                <input type="number" name="commission_percent" value="{{$data->commission_percent}}" class="form-control" id="commission_percent" >
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
            
@endsection


 @section('js-section')  
<script type="text/javascript">

    if("{{$data->is_commission}}" == '0'){

        $('.commission_percent').css('display','none');
        $('#commission_percent').attr('required', false);
        
    }

    $('.commission_type').change(function(){
              
          var commission_type = $(this).val();
          
          if(commission_type == '0'){

            $('.commission_percent').css('display','none');
            $('#commission_percent').attr('required', false);
          
          }else{

            $('.commission_percent').css('display','inline-block');
            $('#commission_percent').attr('required', true);
            
          }
    });

</script>
@endsection