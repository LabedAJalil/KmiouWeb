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

<div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                 <div class="row">
                        <div class="col-md-6">
                            <!--
                            <div class="content-header">Shipment</div>
                             <p class="content-sub-header">Available Driver</p> -->
                        </div>
                        <div class="col-md-6 text-right content-header">
                            <!-- <a href="{{route('showAddNewUser')}}" type="button" class="btn btn-raised btn-primary btn-min-width mr-1 mb-1"><i class="fa fa-user-o" style="margin-right: 10px;"></i>Add New Driver</a> -->
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

                                <form method="POST" class="frm_search row">

                                  {{ csrf_field() }}

                                  <div class="col-md-3">
                                       <fieldset class="form-group">
                                           <label for="basicInput">From Date </label>
                                              <input type="date" name="from_date" id="from_date" class="form-control date-id">
                                        </fieldset>
                                  </div>
                                
                                  <div class="col-md-3">
                                       <fieldset class="form-group">
                                           <label for="basicInput">To Date </label>
                                              <input type="date" name="end_date" id="to_date" class="form-control date-id">
                                        </fieldset>
                                  </div>                                
                                
                                  <div class="col-md-12 mb-1">
                                      <button style="background: #5db5c5;color: white;border:#5db5c5;" class="btncommon col-md-2 form-control" type="submit">SEARCH</button>
                                  </div>                                
                                </form>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title" style="display: inline-block;">Past Shipment List</h4>
                                    </div>
                                       
                                   <div class="card-body collapse show">
                                        <div class="card-block card-dashboard" style="padding: 0px 10px;">
                                            <!-- <p class="card-text">View and manage your stores</p> -->
                                            <table class="table table-responsive table-striped table-bordered file-export" id="users_datatable" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Shipment ID</th>
                                                        <th>Shipper Name</th>
                                                        <th>Driver Name</th>
                                                        <th>transporter Name</th>
                                                        <th>Pickup Address</th>
                                                        <th>Drop Address</th>
                                                        <th>Pickup Date Time</th>
                                                        <th>Arrived at Pickup Time</th>
                                                        <th>Arrived at Drop Time</th>
                                                        <th>Amount</th>
                                                        <th>Order Date</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                     </thead>
                                                    <tbody class="table_body"> 
                                                        <?php $i = 1; ?>
                                                     @foreach($data as $data)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$data->unique_id}}</td>
                                                        <td>{{$data->user_first_name}} {{$data->user_last_name}}</td>
                                                        <td>{{$data->driver_first_name}} {{$data->driver_last_name}}</td>
                                                        <td>{{$data->transporter_first_name}} {{$data->transporter_last_name}}</td>
                                                        <td>{{$data->pickup}}</td>
                                                        <td>{{$data->drop}}</td>
                                                        <td>{{$data->pickup_date}}</td>
                                                        <td>{{$data->arrive_pickup_date}}</td>
                                                        <td>{{$data->arrive_drop_date}}</td>
                                                        <td>{{$data->amount}}</td>
                                                        <td>{{$data->created_at}}</td>
                                                        <td>
                                                            Delivered
                                                        </td>
                                                        <td>
                                                            <a href="{{route('showPastShipmentDetails',array('id' => $data->id))}}" class="" data-id="{{$data->id}}">View More</a>&nbsp;
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
        
            
            $(".frm_search").submit(function(e){

            e.preventDefault();

            var frm_data = $(".frm_search").serialize();

              $.ajax({
                   type:'POST',
                   url:"{{route('shipmentCompletedListFilter')}}",
                   data :frm_data,
                   success:function(data){
                  $("#users_datatable").DataTable().destroy();
                  $(".table_body").html("");
                  
                  res = JSON.parse(data); 
                  
                if(res.result.length >0){

                    $.each(res.result, function( k, v ) { 

                      var user_name = "";
                      var driver_name = "";
                      var transporter_name = "";
                      var pickup_date = "";
                      var arrive_pickup_date = "";
                      var arrive_drop_date = "";

                      if(v.pickup_date != null){
                         pickup_date = v.pickup_date;
                      }
                      
                      if(v.arrive_pickup_date != null){
                         arrive_pickup_date = v.arrive_pickup_date;
                      }
                      
                      if(v.arrive_drop_date != null){
                         arrive_drop_date = v.arrive_drop_date;
                      }
                      
                     
                     user_name = ((v.user_first_name == null)?'':v.user_first_name)+' '+((v.user_last_name == null)?'':v.user_last_name);
                      
                      driver_name = (v.driver_first_name == null)?'':v.driver_first_name+' '+((v.driver_last_name == null)?'':v.driver_last_name);
                      
                      transporter_name = (v.transporter_first_name == null)?'':v.transporter_first_name+' '+((v.transporter_last_name == null)?'':v.transporter_last_name);


                        $('.table_body').append('<tr> <th>'+(k+1)+'</th> <td>'+(v.unique_id)+'</td> <td style="max-width:200px;"> '+user_name+'</td> <td> '+driver_name+'</td> <td> '+transporter_name+'</td> <td> '+v.pickup+'</td> <td> '+v.drop+'</td> <td> '+pickup_date+' </td> <td> '+arrive_pickup_date+' </td> <td> '+arrive_drop_date+' </td> <td> '+v.amount+' </td> <td> '+v.created_at+' </td> <td> Delivered </td> <td> <a href="{{url("/admin/shipment/past")}}/'+v.id+'/details" data-id="'+v.id+'">View More</a>&nbsp;</td> </tr> '); 
                    });

                    $('#users_datatable').DataTable({
         
                       "iDisplayLength": 10,
                       "dom": '<"dt-buttons"Bf><"clear">irtp',
                       "paging": true,
                       "autoWidth": true,
                       "buttons": ['excel']
                      });

                    $('.dataTables_info').css('display',"none");
                    $('.dataTables_paginate').css('display',"none");


                }else{
                 
                  $('.table_body').append('<tr><td></td><td></td> <td> </td> <td class="custom_td"  style="max-width:250px;"> </td> <td  colspan="3" class="custom_td text-center">No Data Available </td> <td class="custom_td"></td> <td class="custom_td"></td> <td> </td> <td></td> <td></td> <td> </td> <td> </td>  </tr>'); 
                     
                     $('.dataTables_info').css('display',"none");
                    $('.dataTables_paginate').css('display',"none");
                }
                
              }
            });
        });
               

    });


/* ------------------ end serch function ----------------*/


</script>
@endsection
    