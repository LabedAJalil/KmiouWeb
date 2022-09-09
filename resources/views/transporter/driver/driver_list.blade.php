@extends('transporter.common.master')
@section('main-content')

<style>
.user-name-info{
display: block;
}
.add-driver{
background: #3c874b;
color: white;
padding: 10px 20px;
border-radius: 5px;
margin-left: 15px;
}
.add-driver:hover{
color: white;
}
.card-info-shipp{
width: 100%;
}
.card-card{
text-align: right;
}
.send-req{
background: #3c874b;
color: white;
float: right;
margin-top: 10px;
padding: 5px 10px;
border-radius: 3px;
}
.send-req:hover{
color: white;
}
.scroll-md{
max-height: 400px;
overflow: hidden;
overflow-y: auto;
}
.doc_btn{
background: #3c874b;
color: #fff;
padding: 10px;
font-size: 13px;
border-radius: 40px;
}
.remove_driver{
background: #ce2020;
color: #fff;
padding: 10px;
font-size: 13px;
border-radius: 40px;
float: left;
margin-top: 10px;
}
.remove_driver:hover{
color: #fff;
}
.doc_btn:hover{
color: #fff;
}
a.cross_remove{
background: black;
width: 20px;
height: 20px;
border-radius: 50%;
display: inline-block;
margin-left: -20px;
margin-top: 0px;
z-index: : 10000;
position: absolute;
}
span.x_span_corss{
margin-left: 6px;
margin-top: -3px;
float: left;
color: #fff;
}
span.x_span_corss:hover{
color: #fff;
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
                                <h2>Driver List</h2>
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
                             <div class="pickup-info edit-info">
                                <div class="row">
                                	<div class="col-md-12 card-card">
                                		<a class="add-driver" id="add_new_driver" href="javascript:void(0);"  data-toggle="modal" data-target="#exampleModalSelectDriver">Add New Driver</a>
                                	</div>
                                </div>
                            </div>
                            <div class="row" style="display: inline-flex;">
                                @foreach($driver_list as $key => $value)
                                <div class="col-md-6">
                                    <div class="page-header-title">
                                        @if($key == '0')
                                        <h5 class="m-b-10">Your Drivers </h5>
                                        @else
                                        <h5 class="m-b-10">&nbsp; </h5>
                                        @endif
                                    </div>
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <div class="pickup-info">
                                                    <div class="user-img">
                                                        <img class="user-logo" 
                                                        @if($value['profile_pic'] != null && $value['profile_pic'] != '')
                                                        src="{{$value['profile_pic']}}"
                                                        @else
                                                        src="{{asset('public/images/user.png')}}"
                                                        @endif>
                                                        <p class="user-name">&nbsp; {{$value['user_name']}}</p>
                                                    </div>
                                                    
                                                    <div class="pickup-info">
                                                    <label class="det-label">Truck Info: </label>
                                                    <div class="user-img">
                                                        <img class="user-logo" 
                                                        @if($value['truck_img'] != null && $value['truck_img'] != '')
                                                        src="{{$value['truck_img']}}"
                                                        @else
                                                        src="{{asset('public/images/user.png')}}"
                                                        @endif
                                                        >
                                                        <p class="user-name"> &nbsp; {{$value['truck_name']}}</p>
                                                    </div>
                                                    <div class="pickup-info">
                                                        <label class="det-label">Join On:</label>
                                                        <p class="pick-p">{{date('d-M-Y h:i A',strtotime($value['created_at']))}}</p>
                                                    </div>
                                                </div>
                                                    <div class="pickup-info">
                                                    	<label class="det-label">Mobile:</label>
                                                    	<p class="pick-p">{{$value['mobile']}}</p>
                                                	</div>
                                                	<!-- <div class="pickup-info">
                                                    	<label class="det-label">Email:</label>
                                                    	<p class="pick-p">{{$value['email']}}</p>
                                                	</div> -->
                                                     <div class="pickup-info edit-info">
                                                    <label class="det-label">Driver License: </label>
                                                    <?php
                                                        $doc = array();

                                                       if($value && $value['doc'] != '' && $value['doc'] != null){

                                                          $str = $value['doc'];

                                                          $doc = explode ("#####", $str);
                                                       }
                                                    ?>

                                                        @foreach($doc as $doc)
                                                            <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$doc}}"></a>
                                                            <!-- <a class="cross_remove" href="#"><span class="x_span_corss">x</span></a> -->
                                                        @endforeach
                                                    </div>
                                                <form method="POST" id="update_doc_form_{{$value['user_id']}}" action="{{ route('transporterUpdateDriverDoc') }}" enctype="multipart/form-data">
                                                @csrf
                                                    <input type="hidden" name="driver_id" value="{{$value['user_id']}}">
                                                    <div class="pickup-info edit-info">
                                                        <label class="det-label">Upload Document: </label>
                                                        <input type="file" name="doc[]" id="input_upload_doc_{{$value['user_id']}}" class="form-control edit-pro" value="{{$value['doc']}}" multiple="" accept=".jpg,.jpeg,.png">
                                                       &nbsp; 
                                                       <a href="javascript:void(0);" class="update_doc doc_btn" data-driver_id="{{$value['user_id']}}">Update Doc</a>
                                                    </div>
                                                </form> 
                                                	<a href="javascript:void(0);" class="remove_driver" data-driver_id="{{$value['user_id']}}">Remove Driver</a>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
     


      <!-- Modal -->
    <div class="modal fade" id="selectTruckModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Select Truck</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="search_truck" onkeypress="truck_list()" placeholder="Search Truck" aria-label="search_truck" aria-describedby="basic-addon1">
                <div class="input-group-prepend">
                    <span class="input-group-text remove_search_string" id="basic-addon1"> 
                        <span aria-hidden="true">&times;</span>
                    </span>
                </div>
            </div>
            <div class="row scroll-mb truck_body">
                
            </div>
          </div>
          <!-- <div class="modal-footer text-center">
            <button type="button" class="btn btn-primary">Select</button>
          </div> -->
        </div>
      </div>
    </div>
    <!-- Modal -->

     <!-- Modal -->
    <div class="modal fade" id="exampleModalSelectDriver" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Add New Driver</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
        <form method="POST" id="signup_form" action="{{ route('transporterAddDriver') }}" enctype="multipart/form-data">
            @csrf
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="first_name" id="first_name"  placeholder="First Name" required="">
                <input type="text" class="form-control" name="last_name" id="last_name"  placeholder="Last Name" required="">
            </div>
            <!-- <div class="input-group mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required="" id="email" pattern="[A-Za-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$" title="Ex:-> example@yourdomain.com" >
            </div> -->
            <div class="input-group mb-3 email_validator_div"></div>
            <div class="input-group mb-3">
                <input type="number" class="form-control" name="mobile_no" id="mobile_no"  placeholder="Phone" required=""> 
                <input type="password" class="form-control" name="password" id="password"  minlength="8" placeholder="password" required="">
            </div>
            <div class="input-group mb-3">
                <select class="form-control" name="language"  id="language" required>
                    <option value="" selected disabled> Preferred Language </option>
                    <option value="1"> English </option>
                    <option value="2"> Arabic </option>
                    <option value="3"> French </option>
                    
                </select>
            </div>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Carrier number" name="carrier_number" id="carrier_number" required="">
            </div>
            <div class="input-group mb-3">
                <a href="javascript:void(0);" data-toggle="modal" data-target="#selectTruckModal" class="text-right" id="btn_select_truck" > SELECT TRUCK </a>
                <input type="text" style="display: none !important;" name="truck_id" class="form-control" id="selected_truck_id" value="0" required="">
                <div class="pickup-info">
                    <label class="det-label">Truck Info: </label>
                    <div class="user-img">
                        <img class="user-logo selected_truck_img" src="">
                        <p class="user-name selected_truck_name"> &nbsp; </p>
                    </div>
                </div>
            </div>
            <div class="input-group mb-3">
                <span style="display: block;margin-bottom: 10px; padding-right: 200px;">Select Document</span> &nbsp;
                 <input type="file" id="select_document" name="doc[]" required="" placeholder="choose document" multiple="" accept=".jpg,.jpeg,.png">
            </div>
          </div>
          <div class="modal-footer text-center">
            <button type="submit" class="btn btn-primary btn_save_new_driver">ADD</button>
          </div>
        </form>
        </div>
      </div>
    </div>
    <!-- Modal -->

@endsection


@section('js-section')

<script type="text/javascript">

    /* ------------------ driver list for request ajax ----------------*/

        $(document).on('click', '#add_new_driver',function(){
         
            $('#exampleModalSelectDriver').modal('show');
        //end function
        });

        $(".btn_save_new_driver").click(function(e){
         
            var first_name = $("#first_name").val();
            var last_name = $("#last_name").val();
            var mobile_no = $("#mobile_no").val();
            var password = $("#password").val();
            var language = $("#language").val();
            var carrier_number = $("#carrier_number").val();
            var selected_truck_id = $("#selected_truck_id").val();
            var select_document = $("#select_document").val();
                
            e.preventDefault();
            
            if(first_name != '' && last_name != '' && mobile_no != '' && password != '' && language != '' && carrier_number != '' && selected_truck_id != '0' && selected_truck_id != ''){

                    $("#signup_form").submit();
                    return;
            }else{
                if(first_name != '' && last_name != '' && mobile_no != '' && password != '' && language != '' && carrier_number != ''){
                    alert("please Select Truck");
                }
                return;    
            }


        //end function
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
                            $('.email_validator_div').html('<h5 class="email_validator" style="color:red;"> * '+ res.msg +'</h5>'); 
                        }else{

                            $('.email_validator_div').html(''); 
                        }
                    }
                })
            } 
        });
        

    /* ------------------ driver list for request ajax ----------------*/


     $(document).on("click",".vehicle_document",function(){
            
            var img = $(this).attr('src');
            
            $('#ModalImage').attr("src",img);

            $('#exampleModalCenter').modal('show');
        });


     /* ------------------ remove driver ajax ----------------*/

        $(document).on('click', '.remove_driver',function(){
         var click = $(this);
         var driver_id = click.attr('data-driver_id');

            if(confirm("Are u sure you want to Remove Driver ?"))
            {

                $.ajax({
                    url:"{{route('transporterRemoveJoinDriver')}}",
                    type:"POST",
                    data:{'_token':"{{csrf_token()}}",driver_id:driver_id},
                    success:function(data){
                            
                        res = JSON.parse(data);
                        
                       window.location.href="{{route('transporterShowDriverList')}}";
                        
                    }
                })
            }
            else{
                return false;
            }
        });

    /* ------------------ remove driver ajax ----------------*/

    $(document).on('click', '#btn_select_truck',function(){
    
        $('#selectTruckModal').modal('show');              
        $('#exampleModalSelectDriver').modal('hide');
    //end function
    });

    $(document).on('click', '#add_new_driver',function(){
         
        truck_list('');
    //end function
    });


    $(document).on('click', '.remove_search_string',function(){
            
        $('#search_truck').val('');
        truck_list('');
    //end function
    });

    function truck_list(search_string){

            var search_string = $('#search_truck').val();

            $.ajax({
                url:"{{route('transporterShowTruckListForDriverAdd')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",search_string:search_string},
                success:function(data){
                        
                    res = JSON.parse(data);
                    
                    $('.truck_body').html("");

                    if(res.result.length > 0){

                        $.each(res.result, function( k, v ) { 
                            
                            var truck_img = "{{asset('public/images/user.png')}}";
                            
                            if(v.truck_img != null && v.truck_img != '' ){
                                truck_img = v.truck_img;
                            }

                            $('.truck_body').append('<div class="col-md-12 driv-block"><img class="user-logo driv-name" src="'+truck_img+'"><a href="javascript:void(0);" class="user-name">'+v.truck_name+'</a><a href="javascript:void(0);" data-truck_id='+v.truck_id+' data-truck_name="'+v.truck_name+'" data-truck_img="'+truck_img+'" class="send-req select_truck">Select</a></div>'); 
                        });
                    
                    }else{
                        
                        $('.truck_body').append('<div class="text-center mb-3 ml-15"> No Data Found </div>');
                    }
                    
                }
            });

        //end function
        }

        $(document).on('click', '.select_truck',function(){
            
            var truck_id = $(this).data('truck_id');
            var truck_name = $(this).data('truck_name');
            var truck_img = $(this).data('truck_img');
            
            $('#selected_truck_id').val(truck_id);
            $('.selected_truck_name').html(truck_name);
            $('.selected_truck_img').attr("src",truck_img);

            $('#selectTruckModal').modal('hide');            
            $('#exampleModalSelectDriver').modal('show');

        });

    /* ------------------ change driver doc ajax ----------------*/

        $(document).on('click', '.update_doc',function(){
         var click = $(this);
         var driver_id = click.attr('data-driver_id');
         var input_upload_doc = $('#input_upload_doc_'+driver_id).val();
         
            if(input_upload_doc != null && input_upload_doc != '' &&  input_upload_doc != '[]'){

                $('#update_doc_form_'+driver_id).submit();
            }else{
                alert("document required");
            }
           
        });

    /* ------------------ remove driver ajax ----------------*/

</script>
 
@endsection
