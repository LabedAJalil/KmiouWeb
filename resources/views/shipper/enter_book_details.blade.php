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
    background: black;
    border: 0px;
    margin-top: 5px;
    margin-right: 10px;
    margin-left: 10px;
}
.parsley-min{
max-width: 135px;
line-height: 18px;
}
</style>
   <link rel="stylesheet" href="{{asset('public/css/bootstrap-multiselect.css')}}">
   <link rel="stylesheet" href="{{asset('public/css/parsley.css')}}">
    <link rel="stylesheet" type="text/css" href="https://www.jqueryscript.net/demo/Clean-jQuery-Date-Time-Picker-Plugin-datetimepicker/jquery.datetimepicker.css">    
@endsection

@extends('shipper.common.master')
@section('main-content')

<main>
        <!--? slider Area Start-->
        <div class="slider-area ">
            <div class="single-slider hero-overly slider-height2 d-flex align-items-center" data-background="assets/img/hero/about.jpg">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap">
                                <h2>Enter Booking Details</h2>
                              <!--   <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                                        <li class="breadcrumb-item"><a href="#">Contact</a></li> 
                                    </ol>
                                </nav> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- slider Area End-->
        <!-- ================ contact section start ================= -->
        <section class="contact-section">
            <div class="container">
                <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <div class="row">
                            <div class="col-md-12">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <form method="POST" id="form" action="{{ route('shipperBookNewShipment') }}" enctype="multipart/form-data" data-parsley-validate="parsley"> 
                                                    @csrf
                                                
                                                <!-- Alert Message -->  
                                                    <div class="flash-message">
                                                      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                                        @if(Session::has('alert-' . $msg))
                                                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                                        {{ Session::forget('alert-' . $msg) }}
                                                         {{ Session::save() }}
                                                        @endif
                                                      @endforeach
                                                    </div>
                                                  <!-- Alert Message -->
                                                  
                                                  <input type="hidden" name="pickup" value="{{$pickup}}">
                                                  <input type="hidden" name="pickup_lat" value="{{$pickup_lat}}">
                                                  <input type="hidden" name="pickup_long" value="{{$pickup_long}}">
                                                  <input type="hidden" name="drop" value="{{$drop}}">
                                                  <input type="hidden" name="drop_lat" value="{{$drop_lat}}">
                                                  <input type="hidden" name="drop_long" value="{{$drop_long}}">
                                                  <input type="hidden" name="vehicle_id" value="{{$vehicle_id}}">
                                                  
                                                  <input type="hidden" name="promo_id" id="promo_id" value="0">
                                                  
                                                  <input type="hidden" name="discount" id="discount" value="0">
                                                  

                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Select Quotation Type: </label>
                                                    <!-- <input type="checkbox" class="custom-checkbox" id="customCheck1"> -->
                                                <!-- <select class="form-control noti-sel" id="exampleFormControlSelect1"> -->
                                                    <select class="form-control noti-sel quotation_type" name="quotation_type" required="">
                                                        <option value="" selected="" disabled="">- select quotation type -</option>
                                                        <option value="0">Auction</option>
                                                        <option value="1">Fixed</option>
                                                        <!-- <option value="2">Instant Quote</option> -->
                                                    </select>
                                                    </div>
                                                <div class="pickup-info edit-info quotation_amount">
                                                    <label class="det-label">Fixed Quotation Amount :<b><span class="per_truck" style="color:black;">&nbsp;&nbsp;&nbsp;&nbsp; Per Truck</span></b>  </label>
                                                    <input type="number" name="quotation_amount" required="" class="form-control edit-pro" placeholder="amount" id="quotation_amount">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Select Pick-up Date: </label>
                                                    <input type="date" class="form-control edit-pro" placeholder="Select" name="pickup_date" id="pickup_date" required="">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Select Pick-up Time: </label>
                                                    <input type="text" class="form-control edit-pro" placeholder="Select time" name="pickup_time" id="pickup_time" required="">
                                                </div>
                                                <!-- <div class="pickup-info edit-info">
                                                    <label class="det-label">&nbsp;</label>
                                                    <span class="pick-p"> (Set time 2 hours before the scheduled pick-up) </span>
                                                </div> -->
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Select Goods Type: </label>
                                                    <select class="form-control noti-sel multiselect-ui" id="goods_type" name="goods_type[]"  multiple="" aria-multiselectable="true" required="">
                                                    @foreach($goods_type as $goods_type)
                                                        <option value="{{$goods_type->id}}">{{$goods_type->goods_type_name}}</option>
                                                    @endforeach
                                                    </select>
                                                </div>
                                                
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Estimated Weight: </label> 
                                                    <input type="number"  class="form-control edit-pro" placeholder="Enter Weight" min="0" name="weight" required="">
                                                </div>

                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Estimated Weight Type: </label>
                                                    <select class="form-control noti-sel" name="weight_type" required="">
                                                        <option value="" selected disabled="">-select weight type-</option>
                                                        <option value="0">kg</option>
                                                        <option value="1">ton</option>
                                                    </select>
                                                </div>

                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Sender & Receiver info: </label>
                                                    <a class="det-label" data-toggle="modal" data-target="#exampleModalCenter" href="#" >Enter Here</a>
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Number of Vehicle: </label>
                                                    <select class="form-control noti-sel" name="no_of_vehicle" id="no_of_vehicle" required="">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                    </select>
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Promo Code: </label>
                                                    <input type="text"  class="form-control edit-pro" placeholder="Enter Here" id="promo_code" name="promo_code" > &nbsp;&nbsp;
                                                    <a href="javascript:void(0);" style="margin-left: -55px;" id="btnApplyPromoCode"> apply </a>
                                                </div>
                                                <!-- <div class="pickup-info edit-info">
                                                    <label class="det-label">Sender & Receiver info: </label>
                                                    <input type="email" value=""  class="form-control edit-pro" placeholder="Enter here">
                                                </div> -->
                                                <!--- Modal -->
                                                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Enter info</h5>
                                                        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button> -->
                                                      </div>
                                                      <div class="modal-body">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Sender Details</h5>
                                                        <div class="row">
                                                            <div class="col-md-12 driv-block">
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">First Name: </label>
                                                                    <input type="text" name="sender_info[0][sender_first_name]"  class="form-control edit-pro" placeholder="Enter first name" id="sender_first_name">
                                                                </div>
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">Last Name: </label>
                                                                    <input type="text" name="sender_info[0][sender_last_name]" class="form-control edit-pro" placeholder="Enter last name" id="sender_last_name">
                                                                </div>
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">Mobile: </label>
                                                                    <input type="text" name="sender_info[0][sender_mobile]" class="form-control edit-pro" placeholder="Enter mobile" id="sender_mobile">
                                                                </div>
                                                                <!-- <div class="pickup-info edit-info">
                                                                    <label class="det-label">Email: </label>
                                                                    <input type="email" name="sender_info[0][sender_email]" class="form-control edit-pro" placeholder="Enter email" id="sender_email">
                                                                </div> -->
                                                            </div>
                                                        </div>
                                                        <h5 style="margin-top: 5px;" class="modal-title" id="exampleModalLongTitle">Receiver Details</h5>
                                                        <div class="row">
                                                            <div class="col-md-12 driv-block">
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">First Name: </label>
                                                                    <input type="text" name="sender_info[0][receiver_first_name]"  class="form-control edit-pro" placeholder="Enter first name" id="receiver_first_name">
                                                                </div>
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">Last Name: </label>
                                                                    <input type="text" name="sender_info[0][receiver_last_name]"   class="form-control edit-pro" placeholder="Enter last name" id="receiver_last_name">
                                                                </div>
                                                                <div class="pickup-info edit-info">
                                                                    <label class="det-label">Mobile: </label>
                                                                    <input type="text" name="sender_info[0][receiver_mobile]"   class="form-control edit-pro" placeholder="Enter mobile" id="receiver_mobile">
                                                                </div>
                                                                <!-- <div class="pickup-info edit-info">
                                                                    <label class="det-label">Email: </label>
                                                                    <input type="email" name="sender_info[0][receiver_email]"    class="form-control edit-pro" placeholder="Enter email" id="receiver_email">
                                                                </div> -->
                                                            </div>
                                                            <div class="full-width text-center">
                                                                <a href="javascript:void(0);" id="btnSender" class="btn btn-primary mb-4">Continue</a>
                                                            </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                                <!--- Modal -->
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Upload Document: </label>
                                                    <input type="file" class="form-control edit-pro" placeholder="choose document" name="document[]"  multiple="" accept=".jpg,.jpeg,.png">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Add Instructions: </label>
                                                    <textarea class="form-control edit-pro" id="exampleFormControlTextarea1" placeholder="Enter Here" rows="3" name="info"></textarea>
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    @if($user_payment_type == '0')
                                                    <label class="det-label">Select Payment Info: </label>
                                                    <select class="form-control noti-sel" name="payment_type" required="">
                                                        <option value="0">Cash</option>
                                                        @if($shipper_type == '1')
                                                        <option value="3">Invoice</option>
                                                        @endif
                                                    </select>
                                                    @else
                                                    <label class="det-label">Payment Via : </label>
                                                    <p class="pick-p"><b> ACH (Via Transfer)</b></p>
                                                    @endif
                                                </div>
                                                <!-- <div class="view-more">
                                                    <a href="view">View More</a>
                                                </div> -->
                        
                                                <!---credit card Modal -->
                                               <!--  <div class="modal fade" id="exampleModalCenter1" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                  <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLongTitle">Select Card</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                      <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12 driv-block">
                                                                <div class="pickup-info edit-info">
                                                                    <div class="col-md-12 driv-block">  <img src="{{asset('public/images/visa.png')}}" style="height: 45px;width: 50px;" > 
                                                                    ****-****-****-8246 
                                                                        <input type="checkbox"class="canc-check">
                                                                    </div>
                                                                    <div class="col-md-12 driv-block">  <img src="{{asset('public/images/visa.png')}}" style="height: 45px;width: 50px;" > 
                                                                    ****-****-****-8246 
                                                                        <input type="checkbox"class="canc-check">
                                                                    </div>
                                                                    <div class="col-md-12 driv-block">  <img src="{{asset('public/images/visa.png')}}" style="height: 45px;width: 50px;" > 
                                                                    ****-****-****-8246 
                                                                        <input type="checkbox"class="canc-check">
                                                                    </div>
                                                                    <div class="col-md-12 driv-block">  <img src="{{asset('public/images/visa.png')}}" style="height: 45px;width: 50px;" > 
                                                                    ****-****-****-8246 
                                                                        <input type="checkbox"class="canc-check">
                                                                    </div>
                                                                </div>
                                                        <a href="{{route('shipperShowDashboard')}}" class="btn btn-primary mb-4 float-right" onclick="alert('Booking Added Successfully')" style="margin-top: 20px;margin-right: 100px;" >Proceed to pay</a>
                                                            </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div> -->
                                                <!---credit card Modal -->

                                                <div class="full-width text-center">
                                                    <a href="javascript:void(0);" class="btn btn-primary mb-4" id="btnSubmit">Send Request</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ================ contact section end ================= -->
    </main>

@endsection

@section('js-section')

    <script src="{{asset('public/js/parsley.js')}}"></script>
    <script src="{{asset('public/js/bootstrap-multiselect.js')}}"></script>

    <script src="https://www.jqueryscript.net/demo/Clean-jQuery-Date-Time-Picker-Plugin-datetimepicker/jquery.datetimepicker.js"></script>    

<script type="text/javascript">
  
  $(document).ready(function(){ 
    
    $('.per_truck').css('display','none');

    $('#pickup_time').datetimepicker({
        datepicker:false,
        format:'H:i',
        step:30
    });
       
    $('#form').parsley(); 
     
     $(function () {
        $('#exampleModalCenter').modal({
            show: false,
            keyboard: false,
            backdrop: 'static'
        });
    });

    
        $('#no_of_vehicle').change(function(){
            
            if($(this).val() > '1'){
                 
                $('.per_truck').css('display','inline-flex');
            
            }else{
            
                $('.per_truck').css('display','none');
            }
            
        });

        /*var timepicker = new TimePicker('time', {
          lang: 'en',
          theme: 'dark'
        });
        timepicker.on('change', function(evt) {
          
          var value = (evt.hour || '00') + ':' + (evt.minute || '00');
          evt.element.value = value;
        });*/

    $("#btnSender").click(function(e){

        var sender_first_name = $('#sender_first_name').val();
        var sender_last_name = $('#sender_last_name').val();
        var sender_mobile = $('#sender_mobile').val();
        /*var sender_email = $('#sender_email').val();*/
        var receiver_first_name = $('#receiver_first_name').val();
        var receiver_last_name = $('#receiver_last_name').val();
        var receiver_mobile = $('#receiver_mobile').val();
        /*var receiver_email = $('#receiver_email').val();*/

        if(sender_first_name == '' || sender_mobile == '' || receiver_first_name  == '' || receiver_mobile  == '' ){
            alert("please fill up all details");
        
        /*else if(sender_email != '' && receiver_email != '' && sender_email == receiver_email){
        
            alert("Sender and Receiver E-mail Address Should Not Be Same !!");
        
        }else if( ( sender_email != '' && (IsEmail(sender_email)==false) ) || (receiver_email != '' && (IsEmail(receiver_email)==false))) {
                
            alert("please enter valid email address");
        
            return false;
*/
        }else{
             $('#exampleModalCenter').modal('hide');
        }

    });


    $("#btnApplyPromoCode").click(function(e){

        var promo_code = $('#promo_code').val();

        if(promo_code == '' || promo_code == '0'){
            
            alert("please enter promo code");
        
        }else{
            
            $.ajax({
                url:"{{route('shipperApplyPromoCode')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",promo_code:promo_code},
                success:function(data){
                        
                res = JSON.parse(data);
                
                    if(res.success == '1'){

                        $('#promo_id').val(res.result[0].promo_id);
                        $('#discount').val(res.result[0].discount);

                        alert("Promotion Applied Successfully");

                        $('#btnApplyPromoCode').css('display','none');
                        $('#promo_code').attr('readonly', true);

                    }else{
                        
                        alert(res.msg);
                    }
                }
            });
        }

    });

    function IsEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if(!regex.test(email)) {
           return false;
        }else{
           return true;
        }
      }

    $("#btnSubmit").click(function(e){
            var form = $("#form");
            
            var sender_first_name = $('#sender_first_name').val();
            var sender_last_name = $('#sender_last_name').val();
            var sender_mobile = $('#sender_mobile').val();
            var sender_email = $('#sender_email').val();
            var receiver_first_name = $('#receiver_first_name').val();
            var receiver_last_name = $('#receiver_last_name').val();
            var receiver_mobile = $('#receiver_mobile').val();
            var receiver_email = $('#receiver_email').val();

            if(form.parsley().validate())
            {

                if(sender_first_name == '' || sender_mobile == '' || receiver_first_name  == '' || receiver_mobile  == '' ){

                    $('#exampleModalCenter').modal({
                        show: true,
                        keyboard: false,
                        backdrop: 'static'
                    });
                
                }else{
                        var pickup_time = $('#pickup_time').val();
                        var pickup_date = $('#pickup_date').val();
                        
                        var dtToday = new Date();
                        var hours = dtToday.getHours();
                        var minutes = dtToday.getMinutes();
                        var current_hours = hours + ':' + minutes;

                        var hours = dtToday.getHours();
                        var minutes = dtToday.getMinutes();
                        var current_hours = hours + ':' + minutes;

                        var currentMonth = dtToday.getMonth() + 1;
                        var currentDay = dtToday.getDate();
                        var currentYear = dtToday.getFullYear();
                        
                        if(currentMonth < 10){

                            currentMonth = '0' + currentMonth.toString();
                        }
                            
                        if(currentDay < 10){

                            currentDay = '0' + currentDay.toString();
                        }

                        var maxCurrentDate = currentYear + '-' + currentMonth + '-' + currentDay;
                        
                    if((maxCurrentDate == pickup_date) && (current_hours >= pickup_time)){

                        alert("Please Enter Valid Pickup Time");
                    
                    }else{

                        $("#form").submit();
                    }
                }

            }else{
            } 
        });

     $(function() {
           
           $('#goods_type').multiselect({
                includeSelectAllOption: true,
                buttonWidth: 250,
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                searchable:true,
                placeholder:'Select Goods'

            }); 

           $('.multiselect-item button').removeClass('btn btn-default');
           $('.multiselect-item button i').removeClass('fa fa-close');

            $('.multiselect-item button i').addClass('fa fa-times');
    }); 

        
    $('.quotation_amount').css('display','none');
    $('#quotation_amount').attr('required', false);
    

        $('.quotation_type').change(function(){
              
            var quotation_type = $(this).val();
            if(quotation_type == '0' || quotation_type == '2'){

              $('.quotation_amount').css('display','none');
              $('#quotation_amount').attr('required', false);
            
            }else{
              
              $('.quotation_amount').css('display','inline-flex');
              $('#quotation_amount').attr('required', true);
              
            }
      });


    $(function(){

            var dtToday = new Date();
            
            var month = dtToday.getMonth() + 1;
            var day = dtToday.getDate();
            var year = dtToday.getFullYear();
            
            if(month < 10)
                month = '0' + month.toString();
            if(day < 10)
                day = '0' + day.toString();

            var hours = dtToday.getHours();
            var minutes = dtToday.getMinutes();

            var maxDate = year + '-' + month + '-' + day;
                
            var hours = dtToday.getHours();
            var minutes = dtToday.getMinutes();

            if(minutes < 10)
                minutes = '0' + minutes.toString();

            var maxHours = hours + ':' + minutes;


            $('#pickup_date').attr('min', maxDate);
            $('#pickup_time').attr('min', maxHours);

        });

     $('#pickup_date').change(function(){

        var currentTime = $('#pickup_time').val();
        changeTime(currentTime);   
    
    });
         
    $("#pickup_time").on('focusout wheel', function(){
        
        var currentTime = $(this).val();
        changeTime(currentTime);
       
    });

    function changeTime(currentTime){
            
            var dtToday = new Date();
            var dtPickup = new Date($('#pickup_date').val());

            var currentMonth = dtToday.getMonth() + 1;
            var currentDay = dtToday.getDate();
            var currentYear = dtToday.getFullYear();

            var month = dtPickup.getMonth() + 1;
            var day = dtPickup.getDate();
            var year = dtPickup.getFullYear();

            if(month < 10)
                month = '0' + month.toString();

            if(day < 10)
                day = '0' + day.toString();

            if(currentMonth < 10)
                currentMonth = '0' + currentMonth.toString();
            
            if(currentDay < 10)
                currentDay = '0' + currentDay.toString();
            
            var maxCurrentDate = currentYear + '-' + currentMonth + '-' + currentDay;
            var maxDate = year + '-' + month + '-' + day;

            var hours = dtToday.getHours();
            var minutes = dtToday.getMinutes();

            if(minutes < 10)
                minutes = '0' + minutes.toString();

            var maxHours = hours + ':' + minutes;
            
        
           if(($('#pickup_date').val() == null) || (($('#pickup_date').val() != null) && (maxCurrentDate == maxDate) && (currentTime <= maxHours)) ){

                    $('#pickup_time').attr('min', maxHours);
                
            }else{
                
                $('#pickup_time').removeAttr('min');
            }
    }

// end ready function
  }); 

</script>
@endsection
