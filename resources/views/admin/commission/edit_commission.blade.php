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
                                        <h4 class="card-title mb-0">Edit Commission</h4>
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
           
                                            <form class="form" method="post" action="{{route('updateCommission')}}" enctype="multipart/form-data" data-parsley-validate="">
                                               {{csrf_field()}}

                                               <input type="hidden" name="commission_id" value="{{$data->id}}" >
                                               
                                                <div class="form-body">
                                                    <div class="row" style="max-width: 450px;">
                                                        
                                                        <div class="col-md-12">
                                                            <fieldset class="form-group transporter_percent">
                                                                <label for="basicInput">User Name</label>
                                                                <input type="text" class="form-control" id="user_name" value="{{$user->first_name}} {{$user->last_name}}" disabled="">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group transporter_percent">
                                                                <label for="basicInput">User Type</label>
                                                                <input type="text" class="form-control" id="user_type" 
                                                                <?php 
                                                                  if($user->user_type == '2'){
                                                                    echo('value="Shipper"');
                                                                  }else if($user->user_type == '3'){
                                                                    echo('value="Transporter"');
                                                                  }else{
                                                                    echo('value="Driver"');
                                                                 }
                                                                 ?> 
                                                                disabled="">
                                                            </fieldset>
                                                        </div>


                                                        <div class="col-md-12">
                                                            <fieldset class="form-group transporter_percent">
                                                                <label for="basicInput">Admin Percentage</label>
                                                                <input type="number" min="1" max="100" name="admin_percent" class="form-control" id="percent" value="{{$data->admin_percent}}" required="">
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

    /*$('.commission_type').change(function(){
              
          var commission_type = $(this).val();
          
          if(commission_type == '1'){

            $('.transporter_percent').css('display','none');
            $('#transporter_percent').attr('required', false);
            $('#transporter_percent').val('0');
          
          }else{
            
            $('#transporter_percent').val('');
            $('.transporter_percent').css('display','inline-block');
            $('#transporter_percent').attr('required', true);
            
          }
    });*/

</script>
@endsection
