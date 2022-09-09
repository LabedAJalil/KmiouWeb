@extends('admin.common.master')
@section('css-section')

<style>
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
.dropdown-menu .active label{
color: green !important;
}
ul.multiselect-container.dropdown-menu{
z-index: 1;
}
.input-group-addon i{
display: none;
}
ul.multiselect-container.dropdown-menu li a:hover{
color: #197CF6;
}
.multiselect-clear-filter{
    background: black!important;
    border: 0px!important;
    margin-top: 5px!important;
    margin-right: 10px!important;
    margin-left: 10px!important;
}

.dropdown-menu {
    width: 300px!important;
}

 ul.multiselect-container.dropdown-menu.show li {
    padding: 10px!important;
}
ul.multiselect-container.dropdown-menu.show{
width: 350px!important;
max-height: 200px;
overflow: hidden auto;
}

ul.multiselect-container.dropdown-menu.show span.input-group-addon{
display: none!important;
}

ul.multiselect-container.dropdown-menu.show span.input-group-btn{
display: none!important;
}
</style>

   <link rel="stylesheet" href="{{asset('public/css/bootstrap-multiselect.css')}}">

@endsection
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
                                        <h4 class="card-title mb-0">New User</h4>
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
           
                                            <form class="form" method="post" action="{{route('addNewUser')}}" enctype="multipart/form-data" data-parsley-validate="">
                                               {{csrf_field()}}
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">First Name</label>
                                                                <input type="text" name="first_name" class="form-control" id="first_name" required="">
                                                            </fieldset>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Last Name</label>
                                                                <input type="text" name="last_name" class="form-control" id="last_name" required="">
                                                            </fieldset>
                                                        </div>

                                                         <div class="col-md-4">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">User Type</label>
                                                                    <select class="form-control" name="user_type"  id="user_type" required>
                                                                        <option value="" selected disabled> -- select user type -- </option>
                                                                        <option value="2"> Shipper </option>
                                                                        <option value="3"> Carrier - Fleet Owner </option>
                                                                        <option value="4"> Driver </option>
                                                                    </select>
                                                                </fieldset>
                                                        </div>

                                                    <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Email</label>
                                                            <input type="email" class="form-control" name="email" placeholder="Email" required="" id="email" pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" title="Ex:-> example@yourdomain.com" >
                                                        </fieldset>
                                                    </div>

                                                    <div class="col-md-12 email_validator_div" style="display: none !important;">

                                                    </div>
                                                    <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Password</label>
                                                        <input type="password" class="form-control" name="password" minlength="8" placeholder="password" required="">
                                                        </fieldset>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">City </label>
                                                            <input type="text" name="city" class="form-control" id="city" required="">
                                                        </fieldset>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">State</label>
                                                            <input type="text" name="state" class="form-control" id="state" required="">
                                                        </fieldset>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Country Code</label>
                                                         <select class="form-control" name="country_code" id="country_code" required> 
                                                         <option value="" selected disabled> Select Country Code </option>
                                                            @if($country_code)
                                                            @foreach($country_code as $data)
                                                            <option value="{{$data->country_code}}">{{$data->country_name}}  &nbsp;&nbsp;&nbsp;{{$data->country_code}}</option>
                                                            @endforeach
                                                            @endif
                                                            
                                                        </select>
                                                        </fieldset>
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Phone Number</label>
                                                            <input type="number" name="mobile_no" class="form-control" id="mobile_no" required="" maxlength="11" data-parsley-error-message="Invalid Mobile Number">
                                                        </fieldset>
                                                    </div>
                                                    
                                                    
                                                   <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">City</label>
                                                            <select class="form-control" name="city" id="city" required> 
                                                             <option value="" selected disabled> Select Your City </option>
                                                                @foreach($city as $key => $value)
                                                                <option value="{{$value->city_name}}">{{$value->city_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </fieldset>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Language</label>
                                                            <select class="form-control" name="language"  id="language" required>
                                                                <option value="" selected disabled> Preferred Language </option>
                                                                <option value="1"> English </option>
                                                                <option value="2"> Arabic </option>
                                                                <option value="3"> French </option>
                                                                
                                                            </select>
                                                        </fieldset>
                                                    </div>


                                                        <div class="col-md-4 registration_type" style="display: none !important;">
                                                            <fieldset class="form-group">
                                                                <label for="basicInput">Shipper Registration Type</label>
                                                                <select class="form-control" name="register_as" id="registration_type" required> 
                                                                 <option value="" selected disabled> -- Select Register Type -- </option>
                                                                    
                                                                    <option value="0">Individual</option>
                                                                    <option value="1">Company</option>
                                                                    
                                                                </select>
                                                            </fieldset>
                                                        </div>
                                                    
                                                    
                                                    <!-- <div class="input-group mb-3">
                                                        <input type="text" class="form-control" name="country" placeholder="Country" required="">
                                                        <input type="text" class="form-control" name="zipcode" placeholder="Zip Code" required="">
                                                    </div> -->

                                                    <div class="col-md-12 mt-4 mb-4">
                                                      <h4 class="customMsg"> </h4>
                                                    </div>

                                                    <div class="col-md-4 equipment_type">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Equipment Use</label>
                                                                <select class="form-control" name="equipment_use"  id="equipment_type" required>
                                                                    <option value="" selected disabled> What Type of Equipment Do You Use? </option>
                                                                    <option value="1"> Full Truckload Tautliner </option>
                                                                    <option value="2"> Full Truckload Tilt </option>
                                                                    <option value="3"> Full Truckload Box </option>
                                                                    <option value="4"> Full Truckload Road Train/Jumbo </option>
                                                                    <option value="5"> Full Truckload Mega </option>
                                                                    <option value="6"> Full Truckload Frigo/Reefer </option>
                                                                    <option value="7"> Other </option>
                                                                </select>
                                                            </fieldset>
                                                    </div>

                                                    <div class="col-md-4 operated_equipment_type">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Equipment Types</label>
                                                                <select class="form-control" name="operated_equipment_type"  id="operated_equipment_type" required>
                                                                    <option value="" selected disabled> What Equipment Types do you Operate? </option>
                                                                    <option value="1"> Full Truckload </option>
                                                                    <option value="2"> Less-than-Truckload </option>
                                                                    <option value="3"> Parcel </option>
                                                                    <option value="4"> Other </option>
                                                                </select>
                                                            </fieldset>
                                                    </div>

                                                    <div class="col-md-4 how_many_trucks">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Truck Operate</label>
                                                                <select class="form-control" name="truck_count"  id="how_many_trucks" required>
                                                                    <option value="" selected disabled> How many trucks do you operate in your fleet? </option>
                                                                    <option value="1"> 1 </option>
                                                                    <option value="2"> 2-5 </option>

                                                                    <option value="3"> 6-15 </option>
                                                                    <option value="4"> 16-50 </option>
                                                                    <option value="5"> 50+ </option>
                                                                </select>
                                                            </fieldset>
                                                    </div>

                                                    <div class="col-md-4 shipping_city">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Shipping City</label>
                                                            <select class="form-control" name="shipping_city"  id="shipping_city" required>
                                                                <option value="" selected disabled> Which city are you primarily shipping from? </option>
                                                                @foreach($city as $key => $value)
                                                                <option value="{{$value->id}}">{{$value->city_name}}</option>
                                                                @endforeach
                                                                
                                                            </select>
                                                        </fieldset>
                                                    </div>

                                                    <div class="col-md-4 shipment_per_month">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">FTL Shipments</label>
                                                            <select class="form-control" name="shipment_per_month"  id="shipment_per_month" required>
                                                                <option value="" selected disabled> FTL Shipments Per Month ? </option>
                                                                <option value="1"> 1-5 </option>
                                                                <option value="2"> 6-20 </option>

                                                                <option value="3"> 21-50 </option>
                                                                <option value="4"> 51-100 </option>
                                                                <option value="5"> 100+ </option>
                                                                <option value="0"> Don't Ship FTL </option>
                                                                
                                                            </select>
                                                        </fieldset>
                                                    </div>

                                                    <div class="col-md-4 truck_type_have">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Truck Type Have</label>
                                                            <select class="form-control noti-sel multiselect-ui" name="truck_type[]"  id="truck_type_have" required multiple="" aria-multiselectable="true">
                                                                <!-- <option value="" selected disabled> Truck Type Use In Carrier </option> -->
                                                                @foreach($truck as $key => $value)
                                                                <?php $weight_type = ($value->weight_type == '0')?'Kg':'Ton';
                                                                ?>
                                                                <option value="{{$value->id}}">{{$value->truck_name}} :   {{$value->capacity}} - {{$weight_type}}</option>
                                                                @endforeach
                                                                
                                                            </select>
                                                        </fieldset>
                                                    </div>

                                                    <div class="col-md-4 single_truck_type_have">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Truck Type Have</label>
                                                            <select class="form-control" name="single_truck_type"  id="single_truck_type_have" required>
                                                                <option value="" selected disabled> Which Type of Truck You Have? </option>
                                                                @foreach($truck as $key => $value)
                                                                <?php $weight_type = ($value->weight_type == '0')?'Kg':'Ton';
                                                                ?>
                                                                <option value="{{$value->id}}">{{$value->truck_name}} :   {{$value->capacity}} - {{$weight_type}}</option>
                                                                @endforeach
                                                                
                                                            </select>
                                                        </fieldset>
                                                    </div>

                                                    <div class="col-md-4 carrier_number">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Carrier Number</label>
                                                            <input type="text" class="form-control" placeholder="Carrier number" name="carrier_number" id="carrier_number" required="">
                                                        </fieldset>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Company City</label>
                                                            <select class="form-control" name="headquarters_city"  id="headquarters_city" required>
                                                                <option value="" selected disabled> Company Headquarters City </option>
                                                                @foreach($city as $key => $value)
                                                                <option value="{{$value->id}}">{{$value->city_name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </fieldset>
                                                    </div>

                                                    <div class="col-md-8">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput">Address</label>
                                                            <textarea name="address" class="form-control" id="address" required="" rows="5" cols="20"></textarea>
                                                        </fieldset>
                                                    </div>

                                                    <div class="col-md-6 select_document">
                                                        <fieldset class="form-group">
                                                            <label for="basicInput" style="display: block;" class="comm_registration_text">Commercial Registration</label> &nbsp;
                                                            <label for="basicInput" style="display: none;" class="driving_license_text">Driving License</label> &nbsp;

                                                        <input type="file" id="select_document" name="doc[]" required="" placeholder="choose document" multiple="" accept=".jpg,.jpeg,.png">
                                                    </div>

                                                    <div class="col-md-6 owner_id_doc">
                                                        <label for="basicInput" style="display: block;" class="owner_id_doc_text">Owner ID</label> &nbsp;
                                                        <label for="basicInput" style="display: none;" class="registration_card_text">Registration Card</label> &nbsp;
                                                        <input type="file" id="owner_id_doc" name="owner_id_doc" required="" placeholder="choose document" accept=".jpg,.jpeg,.png">
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

<script src="{{asset('public/js/bootstrap-multiselect.js')}}"></script>

<script type="text/javascript">
  $(document).ready(function(){  

    $(function() {
           
           $('#truck_type_have').multiselect({
                includeSelectAllOption: true,
                buttonWidth: 250,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                searchable:true,
                placeholder:'Select Truck Type'

            }); 

           $('.multiselect-ui').css('display','none');
           $('.multiselect-item button').removeClass('btn btn-default');
           $('.multiselect-item button i').removeClass('fa fa-close');

            $('.multiselect-item button i').addClass('fa fa-times');
    });

    $("#email").focusout(function() {  
        
        if($(this).val()!='') {  
            var email = $(this).val();
            $.ajax({
                url:"{{route('checkEmailExists')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",email:email},
                success:function(response){
                    res = JSON.parse(response);
                    if(res.success == '0'){
                        $('#email').removeAttr('value');       
                        $('.email_validator_div').css('display','inline-flex');
                        $('.email_validator_div').html('<h5 class="email_validator" style="color:red;"> * '+ res.msg +'</h5>'); 
                    }else{

                        $('.email_validator_div').html(''); 
                        $('.email_validator_div').css('display','none');
                    }
                }
            })
        } 
    }); 

        
    $('.select_document').css('display','none');
    $('.carrier_number').css('display','none');
    $('.operated_equipment_type').css('display','none');
    $('.shipping_city').css('display','none');
    $('.equipment_type').css('display','none');
    $('.how_many_trucks').css('display','none');
    $('.truck_type_have').css('display','none');
    $('.single_truck_type_have').css('display','none');
    $('.shipment_per_month').css('display','none');
    $('.owner_id_doc').css('display','none');
    $('.registration_type').css('display','none');

    
    $('#select_document').attr('required', false);
    $('#carrier_number').attr('required', false);
    $('#operated_equipment_type').attr('required', false);
    $('#shipping_city').attr('required', false);
    $('#equipment_type').attr('required', false);
    $('#how_many_trucks').attr('required', false);
    $('#truck_type_have').attr('required', false);
    $('#single_truck_type_have').attr('required', false);
    $('#shipment_per_month').attr('required', false);
    $('#email').attr('required', true);
    $('#company_name').attr("placeholder", "Company Name");
    $('#registration_type').attr('required', false);
            

        $('#user_type').change(function(){
            
            // user type message
            var user_type = $(this).val();

             if(user_type == '2'){
             
                $('.customMsg').text('You want to move loads with KMIOU.');
             
             }else if(user_type == '3'){
             
                $('.customMsg').text('You own a company that owns trucks and employes drivers.');
             
             }else if(user_type == '4'){
             
                $('.customMsg').text('A company owns the truck you drive and you move loads that the company assigns to you.');
             
             } 
            
            /*user type dropdowns */
            if(user_type == '2'){

              $('.select_document').css('display','inline-flex');
              $('.carrier_number').css('display','none');
              $('.operated_equipment_type').css('display','inline-flex');
              $('.shipping_city').css('display','inline-flex');
              $('.equipment_type').css('display','none');
              $('.how_many_trucks').css('display','none');
              $('.truck_type_have').css('display','none');
              $('.single_truck_type_have').css('display','none');
              $('.shipment_per_month').css('display','inline-flex');
              $('.owner_id_doc').css('display','inline-flex');
              $('.owner_id_doc_text').css('display','inline-flex');
              $('.registration_card_text').css('display','none');
              $('.comm_registration_text').css('display','inline-flex');
              $('.driving_license_text').css('display','none');
              $('.registration_type').css('display','inline-flex');
             
              $('#select_document').attr('required', false);
              $('#carrier_number').attr('required', false);
              $('#operated_equipment_type').attr('required', true);
              $('#shipping_city').attr('required', true);
              $('#equipment_type').attr('required', false);
              $('#how_many_trucks').attr('required', false);
              $('#truck_type_have').attr('required', false);
              $('#single_truck_type_have').attr('required', false);
              $('#shipment_per_month').attr('required', true);
              $('#registration_type').attr('required', true);

              $('#company_name').attr("placeholder", "Company Name");
              $('#email').attr('required', true);
            
            }else if(user_type == '3'){

              
              $('.select_document').css('display','inline-flex');
              $('.carrier_number').css('display','inline-flex');
              $('.operated_equipment_type').css('display','none');
              $('.shipping_city').css('display','none');
              $('.equipment_type').css('display','inline-flex');
              $('.how_many_trucks').css('display','inline-flex');
              $('.truck_type_have').css('display','inline-flex');
              $('.single_truck_type_have').css('display','none');
              $('.shipment_per_month').css('display','none');
              $('.owner_id_doc').css('display','inline-flex');
              $('.owner_id_doc_text').css('display','inline-flex');
              $('.registration_card_text').css('display','none');
              $('.comm_registration_text').css('display','inline-flex');
              $('.driving_license_text').css('display','none');
              $('.registration_type').css('display','none');

             
              $('#select_document').attr('required', true);
              $('#carrier_number').attr('required', true);
              $('#operated_equipment_type').attr('required', false);
              $('#shipping_city').attr('required', false);
              $('#equipment_type').attr('required', true);
              $('#how_many_trucks').attr('required', true);
              $('#truck_type_have').attr('required', true);
              $('#single_truck_type_have').attr('required', false);
              $('#shipment_per_month').attr('required', false);
              $('#registration_type').attr('required', false);
              
              $('#company_name').attr("placeholder", "Company Name");
              $('#email').attr('required', false);

            }else{
              
              $('.select_document').css('display','inline-flex');
              $('.carrier_number').css('display','none');
              $('.operated_equipment_type').css('display','none');
              $('.shipping_city').css('display','none');
              $('.equipment_type').css('display','inline-flex');
              $('.how_many_trucks').css('display','none');
              $('.truck_type_have').css('display','none');
              $('.single_truck_type_have').css('display','inline-flex');
              $('.shipment_per_month').css('display','none');
              $('.owner_id_doc').css('display','inline-flex');
              $('.owner_id_doc_text').css('display','none');
              $('.registration_card_text').css('display','inline-flex');
              $('.comm_registration_text').css('display','none');
              $('.driving_license_text').css('display','inline-flex');
              $('.registration_type').css('display','none');


              $('#select_document').attr('required', false);
              $('#carrier_number').attr('required', false);
              $('#operated_equipment_type').attr('required', false);
              $('#shipping_city').attr('required', false);
              $('#equipment_type').attr('required', true);
              $('#how_many_trucks').attr('required', false);
              $('#truck_type_have').attr('required', false);
              $('#single_truck_type_have').attr('required', true);
              $('#shipment_per_month').attr('required', false);
              $('#registration_type').attr('required', false);
              
              $('#company_name').attr("placeholder", "Truck Registration Number");
              $('#email').attr('required', false);
            }
            /*user type dropdowns */
      });

  }); 

</script>
@endsection