@section('css-section')


<style>

  span.dtr-data {
    display: inline-block;
    white-space: initial;
    max-width: 800px;
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
                            <a href="{{route('showAddNewUser')}}" type="button" class="btn btn-raised btn-primary btn-min-width mr-1 mb-1"><i class="fa fa-user-o" style="margin-right: 10px;"></i>Add New User</a>
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
                                        <h4 class="card-title" style="display: inline-block;">Available Shippers</h4>
                                       
                                    </div>
                                   <div class="card-body collapse show">
                                        <div class="card-block card-dashboard" style="padding: 0px 10px;">
                                            <!-- <p class="card-text">View and manage your stores</p> -->
                                            <table class="table table-striped table-responsive table-bordered file-export" id="users_datatable" style="width: 100% !important;">
                                    
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>User Name</th>
                                                        <th>User Type</th>
                                                        <th>Email</th>
                                                        <th>Phone Number</th>
                                                        <th>City</th>
                                                        <th>Approval Status</th>
                                                        <th>Equipment Type Operate</th>
                                                        <th>FTL Shipments Per Month</th>
                                                        <th>Primarily Shipping City</th>
                                                        <th>Device Type</th>
                                                        <th>UDID</th>
                                                        <th>Registration Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                     </thead>
                                                    <tbody> 
                                                        <?php $i = 1; ?>
                                                     @foreach($data as $data)
                                                    <tr>
                                                         
                                                        <td>{{$i}}</td>
                                                        <td>{{$data->first_name}} {{$data->last_name}}</td>
                                                         <td>
                                                          @if($data->shipper_type == '0') 
                                                          Indivdual
                                                          @elseif($data->shipper_type == '1')
                                                          Company
                                                          @endif</td>
                                                        <td>{{$data->email}}</td>
                                                        <td>{{$data->mobile_no}}</td>
                                                        <td>{{$data->city}}</td>
                                                        
                                                        <td>
                                                          @if($data->approve == '0')
                                                            No
                                                          @else
                                                            Yes
                                                          @endif
                                                        </td>

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
                                                        <td>
                                                          <?php 
                                                          $city_name = isset($data->shipping_city_name)?trim(substr($data->shipping_city_name, strpos($data->shipping_city_name, '-') + 1)):'';
                                                          ?>
                                                          {{$city_name}}</td>
                                                        
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

                                                          @if($data->approve == '0')
                                                            <a href="javascript:void(0)" class="verification_status" data-status="1" data-id="{{$data->id}}"> <i class="fa fa-check"> </i> </a>  <a href="javascript:void(0)" class="verification_status" data-status="2" data-id="{{$data->id}}"> &nbsp; | &nbsp;<i class="fa fa-window-close"> </i> </a>
                                                          @else
                                                            <a href="javascript:void(0);" class="delete" data-id="{{$data->id}}">Delete</a>&nbsp;

                                                            <a href="edit/{{$data->id}}">Edit</a>
                                                          @endif
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

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script type="text/javascript">
/* ------------------ serch function ----------------*/
    
   $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();

      $('#users_datatable').DataTable({
         
         "iDisplayLength": 10,
         "dom": '<"dt-buttons"Bf><"clear">irtp',
         "paging": true,
         "autoWidth": true,
         "buttons": ['excel']
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

                        alert("User Removed");
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
                          alert("User Approved and Mail Sent");

                        }else{
                          
                          alert("Email Already Exists");
                        }
                     }

                });
        }

    });

</script>
@endsection
    