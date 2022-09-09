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
                                        <h4 class="card-title" style="display: inline-block;"> Commission List</h4>
                                        <a href="{{route('showAddNewCommission',['type' => '0'])}}" type="button" class="btn btn-raised btn-primary btn-min-width mr-1 mb-1 float-right"><i class="fa fa-user-o" style="margin-right: 10px;"></i>Add New Commission</a>
                                    </div>
                                    <div class="col-md-12 text-right">
                                    </div>
                                   <div class="card-body collapse show">
                                        <div class="card-block card-dashboard" style="padding: 0px 10px;">
                                            <!-- <p class="card-text">View and manage your stores</p> -->
                                            <table class="table table-striped table-bordered file-export" id="users_datatable" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>User Name</th>
                                                        <th>User Type</th>
                                                        <th>Email</th>
                                                        <th>Mobile</th>
                                                        <th>Percentage</th>
                                                        <th>Created Date</th>
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
                                                          @if($data->type == '0')
                                                          Transporter
                                                          @elseif($data->type == '1')
                                                          Driver
                                                          @else
                                                          Shipper
                                                          @endif
                                                        </td>
                                                        <td>{{$data->email}}</td>
                                                        <td>{{$data->mobile_no}}</td>
                                                        <td>{{$data->admin_percent}}</td>
                                                        
                                                        <td>{{date('Y-m-d',strtotime($data->created_at))}}</td>
                                                        <td>
                                                            <a href="javascript:void(0);" class="remove_commission" data-commission_id="{{$data->id}}">Delete</a>&nbsp;

                                                            <a href="{{$data->id}}/edit">Edit</a>
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

<!-- <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script> -->

<script type="text/javascript">
/* ------------------ serch function ----------------*/
    
   $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();

      $('#users_datatable').DataTable({
         
         "iDisplayLength": 10,
         "dom": '<"dt-buttons"Bf><"clear">irtp',
         "paging": true,
         "autoWidth": true
        });
        //"buttons": ['excel']
    });


   $(document).on("click",".remove_commission",function(){

          var click = $(this);
          var commission_id = click.data('commission_id');
          if(confirm("Are You sure you want to remove Commission ?"))
          {
              $.ajax({
                   type:"POST",
                   url:'{{route("removeCommission")}}',
                   data : {
                   "_token": "{{ csrf_token() }}",
                    "commission_id": commission_id},
                   success:function(data){
                     res =  $.parseJSON(data);
                      if(res.success == '1')                    
                      
                        window.location.reload();
                   }

              });
          }

      });

</script>
@endsection
    