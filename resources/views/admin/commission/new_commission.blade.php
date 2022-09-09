@section('css-section')
<style>
.dataTables_paginate {
display: flex;
}
.dataTables_paginate  .pagination{
margin: 15px auto !important;
}

.multiselect-container>li>a>label{
height: 0px !important;
}
.dropdown-menu.show{
width: 230px !important;
}

.multiselect-container>li>a>label{
width: 230px !important;
}
.multiselect-container>li>a{
width: 230px !important;
}
ul.multiselect-container.dropdown-menu.show {
height: 120px;
padding-top: 20px;
border-radius: 5px;
width: 100% !important;
}
.date-id {
padding-left: 2px;
padding-right: 2px;
}
.dropdown-menu .active label {
    color: none !important;
}
.buttons-html5
{
  background: #5db5c5;
  color: white;
  border:#5db5c5;
  padding: 10px;
  margin-right: 5px;
}
</style>
@endsection

@extends('admin.common.master')
@section('main-content')
 <meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('public/css/multiple-select.css')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('public/css/bootstrap-multiselect.css')}}">


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
                                        <h4 class="card-title mb-0">New Commission</h4>
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
           
                                            <form class="form" method="post" action="{{route('addNewCommission')}}" enctype="multipart/form-data" data-parsley-validate="">
                                               {{csrf_field()}}
                                                <div class="form-body">
                                                    <div class="row" style="max-width: 450px;">

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">User Type</label>
                                                                <select class="form-control commission_type" name="type" required="">
                                                                  <option value="0" 
                                                                  <?php if($type == '0'){echo("selected");} ?> >Transporter</option>
                                                                  <option value="1" <?php if($type == '1'){echo("selected");} ?> >Driver</option>
                                                                  <option value="2" <?php if($type == '2'){echo("selected");} ?> >Shipper</option>
                                                              </select>
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-6">
                                                           <fieldset class="form-group">
                                                               <label for="basicInput"> Select User </label> 
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-6">
                                                           <fieldset class="form-group">
                                                                  <select name="user_id[]" id="user_id" class="form-control multiselect-ui" multiple="" aria-multiselectable="true" required="">
                                                                    
                                                                    @if($user != '[]')
                                                                    @foreach($user as $user)
                                                                    <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}} </option> 
                                                                    @endforeach 
                                                                    @else
                                                                    <option></option> 
                                                                    @endif
                                                                  </select>
                                                            </fieldset>
                                                      </div>

                                                        <div class="col-md-12">
                                                            <fieldset class="form-group transporter_percent">
                                                                <label for="basicInput">Admin Percentage</label>
                                                                <input type="number" min="1" max="100" name="admin_percent" class="form-control" id="percent" required="">
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
 <script src="{{asset('public/js/bootstrap-multiselect.js')}}"></script>
<script type="text/javascript">

    $(function() {
          $('#user_id').multiselect({
              includeSelectAllOption: true
          });
      });

   
      $('.commission_type').change(function(){
              
          var commission_type = $(this).val();
          
          window.location.href = "/admin/commission/add/"+commission_type;
    }); 

</script>
@endsection
