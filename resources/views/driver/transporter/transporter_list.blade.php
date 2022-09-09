@extends('driver.common.master')
@section('main-content')

<style>
.user-name-info{
display: block;
}
.add-transporter{
background: #3c874b;
color: white;
padding: 10px 20px;
border-radius: 5px;
margin-left: 15px;
}
.add-transporter:hover{
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
</style>
<main>
        <!--? slider Area Start-->
        <div class="slider-area ">
            <div class="single-slider hero-overly slider-height2 d-flex align-items-center" data-background="{{asset('public/img/hero/about.jpg')}}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="hero-cap">
                                <h2>Transporter List</h2>
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
                                <div class="row" style="float:left;">
                                	<div class="col-md-12 card-card">
                                		<!-- <a class="add-driver" id="add_new_driver" href="javascript:void(0);"  data-toggle="modal" data-target="#exampleModalSelectDriver">Add New Driver</a> -->
                                	</div>
                                </div>
                            </div>
                            <div class="row" style="float:left;">
                                @foreach($request_list as $key => $value)
                                <div class="col-md-4">
                                    <div class="page-header-title">
                                        @if($key == '0')
                                        <h5 class="m-b-10"> Transporter Request List </h5>
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
                                                    	<label class="det-label">Join On:</label>
                                                    	<p class="pick-p">{{date('d-M-Y h:i A',strtotime($value['created_at']))}}</p>
                                                	</div>
                                                    <div class="pickup-info">
                                                    	<label class="det-label">Mobile:</label>
                                                    	<p class="pick-p">{{$value['mobile']}}</p>
                                                	</div>
                                                	<div class="pickup-info">
                                                    	<label class="det-label">Email:</label>
                                                    	<p class="pick-p">{{$value['email']}}</p>
                                                	</div>
                                                	 <a href="javascript:void(0);" class="accept_reject_join_request" data-request_id="{{$value['request_id']}}" data-is_accept="1" >Accept</a>
                                                       &nbsp; | &nbsp; 
                                                     <a href="javascript:void(0);" class="accept_reject_join_request" data-request_id="{{$value['request_id']}}" data-is_accept="0" >Reject</a>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            <div class="row" style="display: block ruby;">
                                @foreach($transporter_list as $key => $value)
                                <div class="col-md-4">
                                    <div class="page-header-title">
                                        @if($key == '0')
                                        <h5 class="m-b-10">Connected Transporter </h5>
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
                                                        <label class="det-label">Join On:</label>
                                                        <p class="pick-p">{{date('d-M-Y h:i A',strtotime($value['created_at']))}}</p>
                                                    </div>
                                                    <div class="pickup-info">
                                                        <label class="det-label">Mobile:</label>
                                                        <p class="pick-p">{{$value['mobile']}}</p>
                                                    </div>
                                                    <div class="pickup-info">
                                                        <label class="det-label">Email:</label>
                                                        <p class="pick-p">{{$value['email']}}</p>
                                                    </div>
                                                    <!-- <a href="javascript:void(0);" class="remove_driver" data-driver_id="{{$value['user_id']}}">Remove</a> -->
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </div>
                            </div>
                            </div>

                            @if($request_list == null && $transporter_list == null)
                            <h3> Transporter List is Empty </h3>
                            @endif
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

<script type="text/javascript">

    /* ------------------ driver list for request ajax ----------------*/

      
        // send join request to driver

        $(document).on('click', '.accept_reject_join_request',function(){

            var click = $(this);
            var request_id = click.data('request_id');
            var is_accept = click.data('is_accept');

            $.ajax({
                url:"{{route('driverAccpetRejectJoinRequest')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",request_id:request_id,is_accept:is_accept},
                success:function(data){
                        
                    res = JSON.parse(data);

                    window.location.href = "{{route('driverShowTransporterList')}}";    
                }
            });
         //end function
        });


    /* ------------------ driver list for request ajax ----------------*/

</script>
 
@endsection
