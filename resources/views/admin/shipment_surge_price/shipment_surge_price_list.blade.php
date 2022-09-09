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

  .upload-doc{
        border-radius: 10px;
        max-width: 100px;
        width: 60px;
        height: 60px;
        border: 2px solid #b5b5b5;
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
                                        <h4 class="card-title" style="display: inline-block;"> Surge Price Request List</h4>
                                       
                                    </div>
                                   <div class="card-body collapse show">
                                        <div class="card-block card-dashboard" style="padding: 0px 10px;">
                                            <!-- <p class="card-text">View and manage your stores</p> -->
                                            <table class="table table-striped table-responsive table-bordered file-export" id="users_datatable" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <!-- <th>Sr. No.</th> -->
                                                        <th>Order ID</th>
                                                        <th>Shipper Name</th>
                                                        <th>Surge Price for Pickup</th>
                                                        <th>Surge Price for Drop</th>
                                                        <th>Pick Time Difference</th>
                                                        <th>Pick Surge Amount</th>
                                                        <th>Drop Time Difference</th>
                                                        <th>Drop Surge Amount</th>
                                                        <th>Comment</th>
                                                        <th>Rejection Comment</th>
                                                        <th>POD Document</th>
                                                        <th>Status</th>
                                                        <th>Created Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                     </thead>
                                                    <tbody> 
                                                        
                                                        <?php $i = 1; ?>
                                                        
                                                     @foreach($data as $data)
                                                    <tr>
                                                        <!-- <td>{{$i}}</td> -->
                                                        <td>{{$data->shipment_id}} </td>
                                                        <td>{{$data->shipper_first_name}} {{$data->shipper_last_name}} </td>
                                                        <td>
                                                          @if($data->surge_price_for_pickup == '0')
                                                          No
                                                          @else
                                                          Yes
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if($data->surge_price_for_drop == '0')
                                                          No
                                                          @else
                                                          Yes
                                                          @endif
                                                        </td>
                                                        <td>
                                                          @if($data->surge_price_for_pickup == '1')
                                                          {{$data->pick_time_diff}}
                                                          @else
                                                          ---
                                                          @endif
                                                      </td>
                                                        <td>{{$data->pickup_amount}}</td>
                                                        <td>
                                                          @if($data->surge_price_for_drop == '1')
                                                          {{$data->drop_time_diff}}
                                                          @else
                                                          ---
                                                          @endif
                                                        </td>
                                                        <td>{{$data->drop_amount}}</td>
                                                        <td>{{$data->comment}}</td>
                                                        <td>{{$data->reject_comment}}</td>
                                                        <td>
                                                          @if($data->doc != null && $data->doc != '')

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
                                                          @if($data->status == '0')
                                                          Pending
                                                          @elseif($data->status == '1')
                                                          Approved
                                                          @else
                                                          Rejected
                                                          @endif
                                                        </td>
                                                        
                                                        <td>{{date('Y-m-d',strtotime($data->created_at))}}</td>
                                                        <td>
                                                          @if($data->status == '0')
                                                            <a href="javascript:void(0)" class="surge_price_status" data-status="1" data-id="{{$data->id}}"> <i class="fa fa-check"> </i> </a>  <a href="javascript:void(0)" class="surge_price_status" data-status="2" data-id="{{$data->id}}"> &nbsp; | &nbsp;<i class="fa fa-window-close"> </i> </a>
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
             columns: [0,1,2,3,4,5,6,7,8,11,12]
         }
         }]
        });
    
  
    });


    $(document).on("click",".surge_price_status",function(){

        var click = $(this);
        var id = click.data('id');
        var status = click.data('status');

        if(status == '1'){

          $.ajax({
               type:"POST",
               url:'{{route("changeShipmentSurgePriceStatus")}}',
               data : {
               "_token": "{{ csrf_token() }}",
                "surge_price_id": id,
                "status": status},
               success:function(data){
                 res =  $.parseJSON(data);
                  if(res.success == '1')
                  
                  window.location.reload();
               }

          });

        }else{

          
          prompt = prompt("Rejection Comment","");
          if(prompt){
              
              $.ajax({
                 type:"POST",
                 url:'{{route("changeShipmentSurgePriceStatus")}}',
                 data : {
                 "_token": "{{ csrf_token() }}",
                  "surge_price_id": id,
                  "reject_comment": prompt,
                  "status": status},
                 success:function(data){
                   res =  $.parseJSON(data);
                    if(res.success == '1')
                    
                    window.location.reload();
                 }

            });
          }
        }
    
    });

</script>
@endsection
    