@section('css-section')

<style>

 
input[type="checkbox"].switch_1{
    font-size: 20px;
    -webkit-appearance: none;
       -moz-appearance: none;
            appearance: none;
    width: 3.5em;
    height: 1.5em;
    background: #ddd;
    border-radius: 3em;
    position: relative;
    cursor: pointer;
    outline: none;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
  }
  
  input[type="checkbox"].switch_1:checked{
    background: #00874A;
  }
  
  input[type="checkbox"].switch_1:after{
    position: absolute;
    content: "";
    width: 1.5em;
    height: 1.5em;
    border-radius: 50%;
    background: #fff;
    -webkit-box-shadow: 0 0 .25em rgba(0,0,0,.3);
            box-shadow: 0 0 .25em rgba(0,0,0,.3);
    -webkit-transform: scale(.7);
            transform: scale(.7);
    left: 0;
    -webkit-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
  }
  
  input[type="checkbox"].switch_1:checked:after{
    left: calc(100% - 1.5em);
  }

.add-truck{
background: #3c874b;
color: white;
padding: 10px 20px;
border-radius: 5px;
margin-left: 15px;
float:right;
}

    .upload-doc{
        border-radius: 10px;
        max-width: 100px;
        width: 60px;
        height: 60px;
        border: 2px solid #b5b5b5;
    }
</style>

@endsection

@extends('driver.common.master')
@section('main-content')

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
                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Personal Info</h5>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <form method="POST" id="signup_form" action="{{ route('driverUpdateProfile') }}" enctype="multipart/form-data">
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
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">First Name: </label>
                                                    <input type="text" name="first_name" value="{{$user->first_name}}"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Last Name: </label>
                                                    <input type="text" name="last_name" value="{{$user->last_name}}"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Mobile No: </label>
                                                    <input type="text" name="mobile_no" value="{{$user->mobile_no}}"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Email: </label>
                                                    <input type="email" value="{{$user->email}}"  class="form-control edit-pro" placeholder="Enter here" readonly="" disabled="">
                                                </div>
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Address: </label>
                                                    <input type="text" name="address"  value="{{$user->address}}"  class="form-control edit-pro" placeholder="Enter here">
                                                </div>
                                                <!-- <div class="pickup-info edit-info">
                                                    <label class="det-label">Number of Vehicle: </label>
                                                    <input type="number" name="no_of_vehicle" value="{{$user->no_of_vehicle}}"  class="form-control edit-pro" placeholder="Enter here">
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
                                                <!-- <div class="view-more">
                                                    <a href="view">View More</a>
                                                </div> -->
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

                                <div class="col-md-12">
                                    <div class="page-header-title">
                                        <h5 class="m-b-10">Vehicle Info</h5>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> -->

                                                @if($truck != [])
                                                <div class="col-md-12 driv-block"><img class="user-logo driv-name" src="{{$truck[0]['truck_img']}}"><b> {{$truck[0]['truck_name']}} </b> 
                                                    &nbsp;
                                                    <input type="checkbox" class="switch_1 float-right" id="switch_{{$truck[0]['status']}}" data-on="online" data-off="offline" data-toggle="toggle" data-onstyle="success" value="{{$truck[0]['status']}}" data-offstyle="danger" data-truck_id="{{$truck[0]['truck_id']}}"
                                                        @if($truck[0]['status'] == '1')
                                                        checked
                                                        @endif>

                                                 <a href="javascript:void(0);" data-truck_id="{{$truck[0]['truck_id']}}" class="send-req text-right" data-toggle="modal" id="add_new_truck" data-target="#exampleModalSelectTruck">&nbsp; &nbsp; Change</a></div>
                                                @else
                                                    <div class="col-md-12 card-card">
                                                        <a class="add-truck" id="add_new_truck" href="javascript:void(0);"  data-toggle="modal" data-target="#exampleModalSelectTruck">Add New Truck</a>
                                                    </div>
                                                @endif
                                                <br>
                                                <br>
                                                <label class="det-label">Driving License : </label>
                                                @foreach($doc as $doc)
                                                    <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$doc}}"></a>
                                                @endforeach
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Upload Document: </label>
                                                    <input type="file" name="doc[]" class="form-control edit-pro" value="{{$user->doc}}" multiple="" accept=".jpg,.jpeg,.png">
                                                </div>

                                                <br>
                                                <br>
                                                <label class="det-label">Registration Card : </label>
                                                @if($user->owner_id_doc != '')
                                                    <a href="javascript:void(0);"><img class="upload-doc vehicle_document" src="{{$user->owner_id_doc}}"></a>
                                                @endif
                                                <div class="pickup-info edit-info">
                                                    <label class="det-label">Upload Document: </label>
                                                    <input type="file" name="owner_id_doc[]" class="form-control edit-pro" value="{{$user->owner_id_doc}}"  accept=".jpg,.jpeg,.png">
                                                </div>

                                                
                                                </div>
                                                <!-- <div class="view-more">
                                                    <a href="view">View More</a>
                                                </div> -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                    <div class="full-width text-center">
                                        <button class="btn btn-primary mb-4">Save</button>
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

      <!-- Modal -->
    <div class="modal fade" id="exampleModalSelectTruck" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Select Truck</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body" style="overflow: scroll; height: 500px;">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="search_truck" onkeypress="add_truck_list()" placeholder="Search Truck" aria-label="search_truck" aria-describedby="basic-addon1">
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

    $(".profile_pic_input").change(function() {
        readURL(this);
    });

     $(document).on('click', '#add_new_truck',function(){
         
            $('#search_truck').val('');
            add_truck_list('');
            $('#exampleModalSelectTruck').modal('show');
        //end function
        });

    /* ------------------ truck list for request ajax ----------------*/
      
        
        $(document).on('click', '.add_truck_list',function(){
         
            add_truck_list('');
        //end function
        });
        
        $(document).on('click', '.remove_search_string',function(){
                
            $('#search_truck').val('');
            add_truck_list('');
        //end function
        });
        
        function add_truck_list(search_string){

            var search_string = $('#search_truck').val();
            
            $.ajax({
                url:"{{route('driverShowTruckListForAdd')}}",
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

                            var desc = '';

                            if(v.capacity != null && v.capacity != '' ){
                                desc = '  -  '+v.capacity;
                            }

                            if(v.weight_type != null && v.weight_type != '' ){
                                if(v.weight_type == '0'){

                                    desc += '  Kg';
                                }else{

                                    desc += '  Ton';
                                }
                            }
                        
                            $('.truck_body').append('<div class="col-md-12 driv-block"><img class="user-logo driv-name" src="'+truck_img+'"><a href="javascript:void(0);" class="user-name">'+v.truck_name+' '+desc+'</a><a href="javascript:void(0);" data-truck_id='+v.truck_id+' data-user_name="'+v.user_name+'" data-truck_img="'+truck_img+'" class="send-req add-truck select_truck">Add</a></div>'); 
                        });
                    
                    }else{
                        
                        $('.truck_body').append('<div class="text-center mb-3 ml-15"> No Data Found </div>');
                    }
                    
                }
            });

        //end function
        }

        $(document).on('click', '.switch_1',function(){
        
            var click = $(this);
            var truck_id = click.data('truck_id');
            var cur_val = click.val();
            var new_val = '0';

            if(cur_val == '0')
            { 
              new_val = '1';
              var opt_val =  click.val('1');
            } 
            else
            {
              var opt_val =  click.val('0');
            }        

            $.ajax({
                url:"{{route('driverChangeTruckStatus')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",truck_id:truck_id,status:new_val},
                success:function(data){
                       
                       var res = JSON.parse(data);

                         if(res.success == '1'){
                            alert("Truck Status Changed");
                         }
                    }
                 })
            
         });

         $(document).on('click', '.select_truck',function(){
                
                var click = $(this);
                var truck_id = click.attr('data-truck_id');
                
                $.ajax({
                    url:"{{route('adminDownloadExcelTrackShipment')}}",
                    type:"POST",
                    data:{'_token':"{{csrf_token()}}",truck_id:truck_id},
                    success:function(data){
                            
                        res = JSON.parse(data);

                        if(res.success == '1'){
                            
                            $('#exampleModalSelectTruck').modal('hide');
                            window.location.reload();
                        }else{
                            alert("Truck Already Added");
                        }
                        
                    }
                });    
            //end function
            }); 


</script>

@endsection