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



                          <form id="sendNotificationFromAdmin" method="post" action="{{route('sendNotification')}}">  
                              {{ csrf_field() }}
                              <div class="form-body" >
                                
                                <div class="row">

                                   <div class="col-md-4">
                                   <fieldset class="form-group">
                                    <label for="user_type">User :</label>  
                                  <select id="user_type" name="user_type" class="form-control input-md" required>
                                    <option value="2"> Shipper  </option>
                                    <option value="3"> Transporter </option>
                                    <option value="4"> Driver </option>
                                  </select>
                                  </fieldset>
                                </div><br><br>

                                   <div class="col-md-4">
                                    <fieldset class="form-group">
                                    <label for="user_id">User :</label>  
                                    <select name="user_id[]" id="user_id" class="form-control multiselect-ui" multiple="" aria-multiselectable="true" required="">
                                    @if($user != [])
                                    @foreach($user as $user)
                                    <option value="{{$user->id}}" class="shipper_option">{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                    @endif

                                    @if($transporter != [])
                                    @foreach($transporter as $user)
                                    <option value="{{$user->id}}" class="transporter_option">{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                    @endif

                                    @if($driver != [])
                                    @foreach($driver as $user)
                                    <option value="{{$user->id}}" class="driver_option">{{$user->first_name}} {{$user->last_name}}</option>
                                    @endforeach
                                    @endif

                                    
                                  </select>
                                  </fieldset>
                                </div><br><br>


                                 <div class="col-md-4">
                                    <fieldset class="form-group">
                                    <label for="first_name">Title :</label>
                                    <input type="text" id="title" name="title" class="form-control input-md" required>
                                  </fieldset>
                                </div>
                                <br><br>

                                 <div class="col-md-8">
                                    <fieldset class="form-group">
                                    <label for="first_name">Message :</label>
                                    <textarea type="text" id="title" name="message" class="form-control input-md" required rows="5" cols="10"></textarea>
                                  </fieldset>
                                </div>
                                <br><br>

                                <!-- Button -->
                                 <div class="col-md-8">
                                    <fieldset class="form-group raw">
                                    <label for="singlebutton"></label>
                                    <button type="submit" name="submit" class="btn btn-primary">Send</button>
                                  </fieldset>
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
/* ------------------ serch function ----------------*/
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();

      $(function() {
          $('#user_id').multiselect({
              includeSelectAllOption: true
          });
      });


        $('#user_type').change(function(){
            
            // user type message
            var user_type = $(this).val();
                    
            //$('#user_id').attr('checked', false); // UnChecks it
            //$("#user_id > checkbox").prop("selected",false);
            $( ".shipper_option" ).removeClass( "active" )
            $( ".transporter_option" ).removeClass( "active" )
            $( ".driver_option" ).removeClass( "active" )
            $(".multiselect-selected-text").html("None Selected");

            /*user type dropdowns */
            if(user_type == '2'){
              
              $('.shipper_option').css('display','inline-flex');
              $('.transporter_option').css('display','none');
              $('.driver_option').css('display','none');


            }else if(user_type == '3'){
              
                $('.shipper_option').css('display','none');
                $('.transporter_option').css('display','inline-flex');
                $('.driver_option').css('display','none');  
            
            }else{
              
              $('.shipper_option').css('display','none');
              $('.transporter_option').css('display','none');
              $('.driver_option').css('display','inline-flex');
            }
            /*user type dropdowns */
      });

    });

</script>
@endsection
    