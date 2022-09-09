@extends('admin.common.master')

@section('main-content')

<style>
  span.dtr-data {
    display: inline-block;
    white-space: initial;
    max-width: 800px;
}


.buttons-html5/**/
{
  background: #5db5c5;
  color: white;
  border:#5db5c5;
  padding: 10px;
  margin-right: 5px;
}
</style>


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
                                        <h4 class="card-title" style="display: inline-block;">Available Transporters</h4>
                                       
                                    </div>
                                    <div class="" align="right">
                                    <lable><b>Status</b></lable>
                                    <select name="approve" class="col-md-2 form-select" id="approve_status">
                                    <option value="-1" @if($status == "-1") selected @endif>Select Status</option>
                                    <option  value="0" @if($status == "0") selected @endif> Pending </option>
                                    <option  value="1" @if($status == "1") selected @endif> Approved </option>
                                    <!-- <option value="2" @if($status == "2") selected @endif> Not Approved </option> -->
                                   
                                </select>
                                    <!-- <select class="status" id="dynamic_select">
                                      <option value="all">Select Status </option>
                                     <option value="0">Approved</option>
                                    <option value="1">Not Approved</option>
                                     </select> -->
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
                                                        <th>Equipment Use</th>
                                                        <th>Transporter Truck Operate In Fleet</th>
                                                        <th>Connected Transporter Name</th>
                                                        <th>Company Name</th>
                                                        <th>Carrier Number</th>
                                                        <th>Approval Status</th>
                                                        <th>Payment Type</th>
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
                                                        <td>
                                                          @if($data->user_type == '2')
                                                            Shipper
                                                          @elseif($data->user_type == '3')
                                                            Transporter
                                                          @elseif($data->ref_id == '0')
                                                            Single
                                                          @endif                                                         
                                                        </td>
                                                        <td>{{$data->email}}</td>
                                                        <td>{{$data->mobile_no}}</td>
                                                        <td>{{$data->city}}</td>

                                                        <td>
                                                          @if($data->equipment_use == '1') 
                                                          Full Truckload Tautliner
                                                          
                                                          @elseif($data->equipment_use == '2')
                                                          Full Truckload Tilt
                                                          
                                                          @elseif($data->equipment_use == '3')
                                                          Full Truckload Box
                                                          
                                                          @elseif($data->equipment_use == '4')
                                                          Full Truckload Road Train/Jumbo
                                                          
                                                          @elseif($data->equipment_use == '5')
                                                          Full Truckload Mega

                                                          @elseif($data->equipment_use == '6')
                                                          Full Truckload Frigo/Reefer

                                                          @elseif($data->equipment_use == '7')
                                                          Other
                                                          @endif</td>


                                                        <td>
                                                          @if($data->truck_count == '1') 
                                                          1
                                                          @elseif($data->truck_count == '2')
                                                          2-5
                                                          @elseif($data->truck_count == '3')
                                                          6-15
                                                          @elseif($data->truck_count == '4')
                                                          16-50
                                                          @elseif($data->truck_count == '5')
                                                          50+
                                                          @endif

                                                        </td>

                                                         <td>{{$data->transporter_first_name}} {{$data->transporter_last_name}}</td>
                                                        <td>{{$data->company_name}}</td>
                                                        <td>{{$data->carrier_number}}</td>
                                                        <td>
                                                          @if($data->approve == '0')
                                                            Pending
                                                          @elseif($data->approve == '1')
                                                            Approved
                                                                                                 
                                                          @endif
                                                          <!-- {{ $data->approve == 0 ? 'Pending' : 'Approved' }} -->
                                                        </td>
                                                        <td>
                                                          @if($data->payment_type == "0")
                                                          Cash/Card
                                                          @else
                                                          Via Transfer
                                                          @endif
                                                        </td>
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
                                                        <td class="doc-ment"> 
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
                                                          
                                                          @if($data->approve == '1')
                                                            <a href="javascript:void(0);" class="delete" data-id="{{$data->id}}">Delete</a>&nbsp;
<!-- edit/{{$data->id}} -->
                                                            <a href="{{route('transporterEdit', ['id'=>$data->id])}}">Edit</a>
                                                          @else
                                                             <a href="javascript:void(0)" class="verification_status" data-status="1" data-id="{{$data->id}}"> <i class="fa fa-check"> </i> </a>  <a href="javascript:void(0)" class="verification_status" data-status="2" data-id="{{$data->id}}"> &nbsp; | &nbsp;<i class="fa fa-window-close"> </i> </a>
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

$(function(){
      $('#approve_status').on('change', function () {
          var id = $(this).val(); // get selected value
          if(id){ 
            window.location = "/admin/transporter/user_list/0/" + id; 
          }
          return false;
      });
    });
/* ------------------ serch function ----------------*/
    
   $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();

       
      $('#users_datatable').DataTable({
         
         "iDisplayLength": 10,
         "dom": '<"dt-buttons"Bf><"clear">irtp',
         "paging": true,
         "autoWidth": true,
         "buttons": [{
           extend: 'excel',
         text: 'Excel',
         className: 'btn btn-default',
         exportOptions: {
             columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16]
         }
         }]
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
 
<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#ddlCountry").on("change", function () {
            var country = $('#ddlCountry').find("option:selected").val();
            // var age = $('#ddlAge').find("option:selected").val();
            SearchData(country)
        });
    });
    function SearchData(country) {
        if (country.toUpperCase() == 'ALL') {
            $('#table11 tbody tr').show();
        } else {
            $('#table11 tbody tr:has(td)').each(function () {
                var rowCountry = $.trim($(this).find('td:eq(1)').text());
                // var rowAge = $.trim($(this).find('td:eq(2)').text());
                if (country.toUpperCase() != 'ALL') {
                    if (rowCountry.toUpperCase() == country.toUpperCase()) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                } else if ($(this).find('td:eq(1)').text() != '' || $(this).find('td:eq(1)').text() != '') {
                    if (country != 'all') {
                        if (rowCountry.toUpperCase() == country.toUpperCase()) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                    if (age != 'all') {
                        if (rowAge == age) {
                            $(this).show();
                        }
                        else {
                            $(this).hide();
                        }
                    }
                }
 
            });
        }
    }
</script> -->

@endsection
    