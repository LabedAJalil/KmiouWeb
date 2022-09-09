@extends('transporter.common.master')
@section('main-content')

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


.user-name-info{
display: block;
}
.add-truck{
background: #3c874b;
color: white;
padding: 10px 20px;
border-radius: 5px;
margin-left: 15px;
}
.add-truck:hover{
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
.remove_truck{
background: #ce2020;
color: #fff;
padding: 10px;
font-size: 13px;
border-radius: 40px;
float: left;
margin-top: 10px;
}
.remove_truck:hover{
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
                                <h2>Truck List</h2>
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
                                		<a class="add-truck" id="add_new_truck" href="javascript:void(0);"  data-toggle="modal" data-target="#exampleModalSelectTruck">Add New Truck</a>
                                	</div>
                                </div>
                            </div>
                            <div class="row">
                                @if($truck_list != null && $truck_list != [])

                                @foreach($truck_list as $key => $value)
                                <div class="col-md-6">
                                    <div class="page-header-title">
                                        @if($key == '0')
                                        <h5 class="m-b-10">Your Trucks </h5>
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
                                                        @if($value['truck_img'] != null && $value['truck_img'] != '')
                                                        src="{{$value['truck_img']}}"
                                                        @else
                                                        src="{{asset('public/images/user.png')}}"
                                                        @endif>
                                                        <p class="user-name">&nbsp; {{$value['truck_name']}}</p>
                                                    </div>
                                                    <div class="pickup-info">
                                                    	<label class="det-label">Truck Type :</label>
                                                    	<p class="pick-p">{{$value['truck_type']}}</p>
                                                	</div>
                                                	<div class="pickup-info">
                                                    	<label class="det-label">Capacity:</label>
                                                    	<p class="pick-p">{{$value['capacity']}}
                                                          @if($value['weight_type'] == '0')
                                                          Kg
                                                          @else
                                                          Ton
                                                          @endif
                                                        </p>
                                                	</div>
                                                    <div class="pickup-info">
                                                        <label class="det-label">Status :</label>
                                                        <p class="pick-p"><input type="checkbox" class="switch_1" id="switch_{{$value['truck_id']}}" data-on="online" data-off="offline" data-toggle="toggle" data-onstyle="success" value="{{$value['status']}}" data-offstyle="danger" data-truck_id="{{$value['truck_id']}}"
                                                        @if($value['status'] == '1')
                                                        checked
                                                        @endif>
                                                        </p>
                                                    </div>
                                                	<a href="javascript:void(0);" class="remove_truck" data-truck_id="{{$value['truck_id']}}">Remove Truck</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                            <div class="row">
                                    <h2> Truck List Is Empty </h2>
                            </div>
                            @endif
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
                url:"{{route('transporterShowTruckListForAdd')}}",
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
                        
                            $('.truck_body').append('<div class="col-md-12 driv-block"><img class="user-logo driv-name" src="'+truck_img+'"><a href="javascript:void(0);" class="user-name">'+v.truck_name+' '+desc+'</a><a href="javascript:void(0);" data-truck_id='+v.truck_id+' data-user_name="'+v.user_name+'" data-truck_img="'+truck_img+'" class="send-req select_truck">Add</a></div>'); 
                        });
                    
                    }else{
                        
                        $('.truck_body').append('<div class="text-center mb-3 ml-15"> No Data Found </div>');
                    }
                    
                }
            });

        //end function
        }


    /* ------------------ truck list for request ajax ----------------*/

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
                url:"{{route('transporterChangeTruckStatus')}}",
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
                    url:"{{route('transporterAddTruck')}}",
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


     /* ------------------ remove driver ajax ----------------*/

        $(document).on('click', '.remove_truck',function(){
         var click = $(this);
         var truck_id = click.attr('data-truck_id');

            if(confirm("Are u sure you want to Remove Truck ?"))
            {

                $.ajax({
                    url:"{{route('transporterRemoveAddedTruck')}}",
                    type:"POST",
                    data:{'_token':"{{csrf_token()}}",truck_id:truck_id},
                    success:function(data){
                            
                        res = JSON.parse(data);
                        
                       window.location.href="{{route('transporterShowTruckList')}}";
                        
                    }
                })
            }
            else{
                return false;
            }
        });

    /* ------------------ remove driver ajax ----------------*/


</script>
 
@endsection
