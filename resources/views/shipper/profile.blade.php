@extends('shipper.common.master')
@section('main-content')
<style>
.card-labl{
margin-top: 10px;
}
.card-card{
background: #efefef;
border-radius: 7px;
padding: 10px;
margin-bottom: 15px;
}
.card-card label.det-label {
    font-weight: 500;
}
</style>
 <main>
        <!--? slider Area Start-->
        <div class="slider-area ">
            <div class="single-slider hero-overly slider-height2 d-flex align-items-center" data-background="{{asset('public/img/hero/about.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap">
                                <h2>Profile</h2>
                              <!--   <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
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
                                <div class="col-md-8">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <form method="POST" id="signup_form" action="{{ route('shipperUpdateProfile') }}" enctype="multipart/form-data">
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

                                                <div class="user-img">
                                                    <label class="det-label">Profile Pic: </label>
                                                    <img class="profile-logo profile_pic" 
                                                    @if($user->profile_pic != null && $user->profile_pic != '')
                                                    src="{{$user->profile_pic}}"
                                                    @else
                                                    src="{{asset('public/images/user.png')}}"
                                                    @endif
                                                    >

                                                    <input type="file" class="profile_pic_input" name="profile_pic" value="{{$user->profile_pic}}" placeholder="choose document" style="padding-left: 40px;" accept=".jpg,.jpeg,.png">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">First Name: </label>
                                                    <input type="text" name="first_name" value="{{$user->first_name}}"  class="form-control edit-pro" placeholder="Enter here" required>
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Last Name: </label>
                                                    <input type="text" name="last_name" value="{{$user->last_name}}"  class="form-control edit-pro" placeholder="Enter here" required>
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Mobile No: </label>
                                                    <input type="number" name="mobile_no" value="{{$user->mobile_no}}"  class="form-control edit-pro" placeholder="Enter here" required>
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Email: </label>
                                                    <input type="email" name="email" value="{{$user->email}}"  class="form-control edit-pro" placeholder="Enter here" readonly="" disabled="">
                                                </div>
                                                <!-- <div class="pickup-info edit-info">
                                                    <label class="det-label">Address: </label>
                                                    <input type="text" name="address" value="{{$user->address}}"  class="form-control edit-pro" placeholder="Enter here" required>
                                                </div> -->
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Push Notification: </label>
                                                <select class="form-control noti-sel" id="exampleFormControlSelect1" name="push_notification" required>
                                                    @if($user->push_notification == '1')
                                                    <option value="1" selected>Yes</option>
                                                    <option value="0">No</option>
                                                    @else
                                                    <option value="1">Yes</option>
                                                    <option value="0" selected>No</option>
                                                    @endif
                                                </select>
                                                </div>
                                                <br>
                                                <br>
                                                <div class="pickup-info edit-info">
                                                  <label class="det-label">Commercial register : </label>
                                                  @foreach($doc as $doc)
                                                      <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$doc}}"></a>
                                                  @endforeach
                                                  <div class="pickup-info edit-info">
                                                      <label class="det-label">Upload Document: </label>
                                                      <input type="file" name="doc[]" class="form-control edit-pro" value="{{$user->doc}}" multiple="" accept=".jpg,.jpeg,.png">
                                                  </div>
                                                </div>
                                                <br>
                                                <br>
                                                <div class="pickup-info edit-info">
                                                  <label class="det-label">Owner ID : </label>
                                                  @if($user->owner_id_doc != '')
                                                      <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$user->owner_id_doc}}"></a>
                                                  @endif
                                                  <div class="pickup-info edit-info">
                                                      <label class="det-label">Upload Document: </label>
                                                      <input type="file" name="owner_id_doc[]" class="form-control edit-pro" value="{{$user->owner_id_doc}}"  accept=".jpg,.jpeg,.png">
                                                  </div>
                                                </div>
                                                 <!-- <div class="pickup-info edit-info">
                                                    <label class="det-label">Add New Card : </label>
                                                    <a href="javascript:void(0);"  data-toggle="modal" data-target="#exampleModalAddNewCard">Add</a>
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Your Cards : </label>
                                                    <div class="row">
                                                    @foreach($card as $key => $value)
                                                    	<div class="col-md-10 card-card">
                                                    		<label class="det-label">Card Number: </label>
                                                    		<p class="pick-p">
                                                          <span class="card_no_{{$value->id}}">{{$value->card_no}}</span>
                                                        </p>

                                                    		<label class="det-label">Name: </label>
                                                    		<p class="pick-p">
                                                          <span class="holder_name_{{$value->id}}">{{$value->holder_name}}</span>
                                                        </p>
                                                    	
                                                    		<label class="det-label">Expiry Date: </label>
                                                    		<p class="pick-p">
                                                          <span class="expiry_month_{{$value->id}}">{{$value->expiry_month}}</span>
                                                          /
                                                          <span class="expiry_year_{{$value->id}}">{{$value->expiry_year}}</span> 
                                                        </p>
                                                        <span hidden="true" class="cvv_{{$value->id}}">{{$value->cvv}}</span>
                                                    		<a href="javascript:void(0);" data-id="{{$value->id}}" class="edit_card">Edit</a>
                                                    		<span>/</span>
                                                    		<a href="javascript:void(0);" data-id="{{$value->id}}" class="delete_card">Delete</a>
                                                    	</div>
                                                  @endforeach
                                                </div> -->
                                                <div class="full-width text-center">
                                                    <button class="btn btn-primary mb-4">Save</button>
                                                </div>
                                            </form>
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

       <!-- Add New Card Modal -->
      <div class="modal fade" id="exampleModalAddNewCard" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
            	<h4>Add Card Details</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <form method="POST" id="report_emergency_form" action="{{ route('shipperAddNewCard') }}" enctype="multipart/form-data">
                @csrf

              <div class="row">

                  <input type="hidden" name="card_id" id="card_id" value="0" >

                  <div class="col-md-12">
                  	<label class="card-labl">Card Number: </label>
                  	<input type="number" name="card_no" id="card_no" class="form-control" placeholder="Enter here" required="">
                  </div>
                  <div class="col-md-12">
                  	<label class="card-labl">Name: </label>
                  	<input type="text" name="holder_name" id="holder_name" class="form-control" placeholder="Enter here" required="">
                  </div>
                  <div class="col-md-6">
                  	<label class="card-labl">Expiry Month: </label>
                  	<input type="number" name="expiry_month" id="expiry_month" maxlength="2" minlength="2" min="01" max="12" class="form-control" placeholder="MM" required="">
                  </div>
                  <div class="col-md-6">
                  	<label class="card-labl">&nbsp; </label>
                  	<input type="number" name="expiry_year" id="expiry_year" min="{{date('Y')}}" minlength="4" maxlength="4" class="form-control" placeholder="YYYY" required="">
                  </div>
                  <div class="col-md-6">
                  	<label class="card-labl">CVV: </label>
                  	<input type="password" name="cvv" id="cvv" minlength="3" maxlength="3" class="form-control" placeholder="Enter here" required="">
                  </div>
                  <div class="full-width text-center">
                  <button class="btn btn-primary mb-4" type="submit">Add</button>
                  </div>
                
                </div>

              </form>

            </div>
            <!-- <div class="modal-footer text-center">
              <button type="button" class="btn btn-primary">Select</button>
            </div> -->
          </div>
        </div>
      </div>
  
   <!-- Add New Card Modal -->

    

@endsection

@section('js-section')

<script type="text/javascript">
    
    function readURL(input) {

        if (input.files && input.files[0]) {
        var reader = new FileReader();

            reader.onload = function(e) {
                $('.profile_pic').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $(".profile_pic_input").change(function() {
        readURL(this);
    });


    $(document).on("click",".edit_card",function(){
            
         var card_id = $(this).data('id');
            
         var card_no = $(".card_no_"+card_id).text();
         var holder_name = $(".holder_name_"+card_id).text();
         var expiry_month = $(".expiry_month_"+card_id).text();
         var expiry_year = $(".expiry_year_"+card_id).text();
         var cvv = $(".cvv_"+card_id).text();

          $('#card_id').val(card_id);
          $('#card_no').val(card_no);
          $('#holder_name').val(holder_name);
          $('#expiry_month').val(expiry_month);
          $('#expiry_year').val(expiry_year);
          $('#cvv').val(cvv);
      
          $('#exampleModalAddNewCard').modal('show');
      });


      $(document).on("click",".delete_card",function(){
            
         var card_id = $(this).data('id');
            
           if(confirm("Are You Sure You Want To Delete This Card ?"))
          {
              $.ajax({
              url:"{{route('shipperDeleteCard')}}",
              type:"POST",
              data:{'_token':"{{csrf_token()}}",card_id:card_id},
              success:function(data){
                     
                     var res = JSON.parse(data);

                     if(res.success == '1'){
                        window.location.href = "{{route('shipperShowProfile')}}";
                     }
                  }
               })
          }
          else{
              return false;
          }
      });


</script>

@endsection