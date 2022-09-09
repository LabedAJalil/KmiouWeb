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
                            <a href="{{route('showAddNewSurgePrice')}}" type="button" class="btn btn-raised btn-primary btn-min-width mr-1 mb-1"><i class="fa fa-user-o" style="margin-right: 10px;"></i>Add New Surge Price</a>
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
                                        <h4 class="card-title" style="display: inline-block;"> Surge Price List</h4>
                                       
                                    </div>
                                   <div class="card-body collapse show">
                                        <div class="card-block card-dashboard" style="padding: 0px 10px;">
                                            <!-- <p class="card-text">View and manage your stores</p> -->
                                            <table class="table table-striped table-bordered file-export" id="users_datatable" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Total Difference Hours</th>
                                                        <th>Price Per Hour</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Created Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                     </thead>
                                                    <tbody> 
                                                        <?php $i = 1; ?>
                                                        
                                                     @foreach($data as $data)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$data->total_diff_hours}}</td>
                                                        <td>{{$data->price_per_hour}}</td>
                                                        
                                                        <td>
                                                          @if($data->type == '0')
                                                          Pickup
                                                          @else
                                                          Drop
                                                          @endif
                                                        </td>

                                                        <td>
                                                          <input type="checkbox" class="switch_1" data-on="online" data-off="offline" data-toggle="toggle" value="{{$data->status}}" data-price_id="{{$data->id}}" data-onstyle="success" data-offstyle="danger"
                                                          @if($data->status == '1')
                                                          checked
                                                          @endif>
                                                        </td>
                                                        
                                                        <td>{{date('Y-m-d',strtotime($data->created_at))}}</td>
                                                        <td>
                                                            <a href="javascript:void(0);" class="remove_price" data-price_id="{{$data->id}}">Delete</a>&nbsp;

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
         "buttons": ['excel']
        });
  
    });


    $(document).on('change',".switch_1",function(){
            
            var click = $(this);
            var cur_val = click.val();
            var price_id = click.data('price_id');
            var new_val = '0';

            if(cur_val == '1')
            { 
              var opt_val =  click.val(parseInt(new_val));
            } 
            else
            {
              new_val = '1';
              var opt_val =  click.val(parseInt(new_val));
            }


            $.ajax({
                 type:'POST',
                 url:'{{route("changeSurgePriceStatus")}}',
                 data : { "_token": "{{ csrf_token() }}","price_id": price_id,'status':new_val},
                 success:function(data){
                     res =  $.parseJSON(data);

                         $(".flash-message").html('<div class="alert alert-success"> Truck Status Changed <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>'); 
                      
                 }

            });
            
    });


   $(document).on("click",".remove_price",function(){

          var click = $(this);
          var price_id = click.data('price_id');
          if(confirm("Are You sure you want to remove Surge Price ?"))
          {
              $.ajax({
                   type:"POST",
                   url:'{{route("removeSurgePrice")}}',
                   data : {
                   "_token": "{{ csrf_token() }}",
                    "price_id": price_id},
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
    