@extends('admin.common.master')

@section('main-content')

 <meta name="csrf-token" content="{{ csrf_token() }}">
<div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                 <div class="row">
                        <div class="col-md-6">
                            <!-- 
                            <div class="content-header">User</div>
                                <p class="content-sub-header">Available User</p> -->
                        </div>
                        <div class="col-md-6 text-right content-header">
                         <!--    <a href="{{route('showAddNewUser')}}" type="button" class="btn btn-raised btn-primary btn-min-width mr-1 mb-1"><i class="fa fa-user-o" style="margin-right: 10px;"></i>Add New User</a> -->
                        </div>
                    </div>
                   
                    <!-- File export table -->
                    <section id="file-export">
                        <div class="row">
                            <div class="col-12">
                                 <!-- Alert Message -->  
                                  <div class="flash-message">
                                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                      @if(Session::has('alert-' . $msg))
                                      <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                                      @endif
                                    @endforeach
                                  </div>
                                <!-- Alert Message -->

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title" style="display: inline-block;">New Users List</h4>
                                       
                                    </div>
                                   <div class="card-body collapse show">
                                        <div class="card-block card-dashboard" style="padding: 0px 10px;">
                                            <!-- <p class="card-text">View and manage your stores</p> -->
                                            <table class="table table-responsive table-striped table-bordered file-export" id="users_datatable" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>User Name</th>
                                                        <th>Email</th>
                                                        <th>Phone Number</th>
                                                        <th>City</th>
                                                        <th>Equipment Type Operate</th>
                                                        <th>FTL Shipments Per Month</th>
                                                        <th>Primarily Shipping City</th>
                                                        <th>Device Type</th>
                                                        <th>UDID</th>
                                                        <th>Registration Date</th>
                                                        <th>Document</th>
                                                        <th>Action</th>
                                                    </tr>
                                                     </thead>
                                                    <tbody> 
                                                        <?php $i = 1; ?>
                                                     @foreach($data as $data)
                                                    <tr>
                                                         
                                                        <td>{{$i}}</td>
                                                        <td>{{$data->first_name}} {{$data->last_name}}</td>
                                                        <td>{{$data->email}}</td>
                                                        <td>{{$data->mobile_no}}</td>
                                                        <td>{{$data->city}}</td>
                                                        
                                                        <td>
                                                          @if($data->operated_equipment_type == '1') 
                                                          Full Truckload
                                                          @elseif($data->operated_equipment_type == '2')
                                                          Less-than-Truckload
                                                          @elseif($data->operated_equipment_type == '3')
                                                          Parcel
                                                          @elseif($data->operated_equipment_type == '4')
                                                          Other
                                                          @endif</td>
                                                        <td>

                                                          @if($data->shipment_per_month == '1') 
                                                          1-5
                                                          @elseif($data->shipment_per_month == '2')
                                                          6-20
                                                          @elseif($data->shipment_per_month == '3')
                                                          21-50
                                                          @elseif($data->shipment_per_month == '4')
                                                          51-100
                                                          @elseif($data->shipment_per_month == '4')
                                                          100+
                                                          @endif

                                                        </td>
                                                        <td>{{$data->shipping_city_name}}</td>
                                                        
                                                        <td>
                                                          @if($data->device_type == "0")
                                                          Web
                                                          @elseif($data->device_type == "1")
                                                          Android
                                                          @elseif($data->device_type == "2")
                                                          iOS
                                                          @endif
                                                        </td>
                                                        <td>{{$data->udid}}</td>
                                                        <td>{{date('Y-m-d',strtotime($data->created_at))}}</td>
                                                        <td>
                                                          
                                                          @if($data->user_type != "2" && $data->doc != null && $data->doc != '')

                                                          <?php 
                                                          $doc = array();
                                                           if($data && $data->doc != '' && $data->doc != null){

                                                              $str = $data->doc;

                                                              $doc = explode ("#####", $str);
                                                           }
                                                          ?>
                                                          @foreach($doc as $key => $doc_url)
                                                            @if($key != '0')
                                                            |
                                                            @endif
                                                            <a href="{{$doc_url}}" target="_blank">view</a>
                                                          @endforeach

                                                          @endif
                                                        </td>
                                                        <td>
                                                            <a href="javascript:void(0)" class="verification_status" data-status="1" data-id="{{$data->id}}"> <i class="fa fa-check"> </i> </a>  <a href="javascript:void(0)" class="verification_status" data-status="2" data-id="{{$data->id}}"> &nbsp; | &nbsp;<i class="fa fa-window-close"> </i> </a>
                                                        </td>
                                                         
            
                                                    </tr>
                                                    <?php $i++; ?>
                                                     @endforeach 
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- File export table -->

                    </div>
                </div>
            </div>
        </div>

 @endsection
 @section('js-section')  
<script type="text/javascript">
/* ------------------ serch function ----------------*/
    
   $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();

      var table = $('#users_datatable').dataTable({
         "aLengthMenu": [ [5, 10, 20, -1], [5, 10, 20, "All"] ],
         "iDisplayLength": 10,
         /*responsive:true,   */ 
      });
  

    
    });
/* ------------------ end serch function ----------------*/

/* ------------------ Delete ajax ----------------*/
    $(document).on('click', '.delete',function(){
     var click = $(this);
     var id = click.attr('data-id');

        if(confirm("Are You Sure You Want To Delete This User ?"))
        {
            $.ajax({
            url:"{{route('removeUser')}}",
            type:"POST",
            data:{'_token':"{{csrf_token()}}",id:id},
            success:function(data){
                    click.parent().parent().remove();
                }
             })
        }
        else{
            return false;
        }
     });


/*------------------ end delete ----------------*/
</script>


<script>
    $(document).on("click",".verification_status",function(){

        var click = $(this);
        var id = click.data('id');
        var status = click.data('status');

        if(status == '2'){

            if(confirm("Are You sure you want to remove ?"))
            {
                  $.ajax({
                     type:"POST",
                     url:'{{route("changeApproveStatus")}}',
                     data : {
                     "_token": "{{ csrf_token() }}",
                      "id": id,
                      "status": status},
                     success:function(data){
                       res =  $.parseJSON(data);
                        if(res.success == '1')
                        click.parent().parent().remove();   

                       $('.flash-message').html('<p class="alert alert-success"> User Removed <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>');
                     }

                });
            }

        }else{

                $.ajax({
                     type:"POST",
                     url:'{{route("changeApproveStatus")}}',
                     data : {
                     "_token": "{{ csrf_token() }}",
                      "id": id,
                      "status": status},
                     success:function(data){
                       res =  $.parseJSON(data);
                      if(res.success == '1'){

                        click.parent().parent().remove();   

                       $('.flash-message').html('<p class="alert alert-success"> User Approved and Mail Sent <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>');
                      }else{
                        $('.flash-message').html('<p class="alert alert-error"> Email Already Exists <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>');
                      }
                     }

                });
        }

    });
</script>
@endsection
    