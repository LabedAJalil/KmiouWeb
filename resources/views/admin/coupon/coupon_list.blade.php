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
                            <a href="{{route('showAddNewCoupon')}}" type="button" class="btn btn-raised btn-primary btn-min-width mr-1 mb-1"><i class="fa fa-user-o" style="margin-right: 10px;"></i>Add New Coupon</a>
                            <br>
                            <br>
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
                                        <h4 class="card-title" style="display: inline-block;"> Coupon List</h4>
                                       
                                    </div>
                                   <div class="card-body collapse show">
                                        <div class="card-block card-dashboard" style="padding: 0px 10px;">
                                            <!-- <p class="card-text">View and manage your stores</p> -->
                                            <table class="table table-responsive table-striped table-bordered file-export dataTable no-footer" id="users_datatable" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Coupon Code</th>
                                                        <th>Title</th>
                                                        <th>Discount</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>status</th>
                                                        <th>status</th>
                                                        <th>Created Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                     </thead>
                                                    <tbody> 
                                                        <?php $i = 1; ?>
                                                        
                                                     @foreach($data as $data)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$data->coupon_code}}</td>
                                                        <td>{{$data->title}}</td>
                                                        <td>{{$data->discount}}</td>
                                                        <td>{{$data->start_date}}</td>
                                                        <td>{{$data->end_date}}</td>
                                                        <td>@if($data->status == '1')
                                                          active
                                                          @else
                                                          inactive 
                                                          @endif

                                                        </td>
                                                        <td>
                                                          <input type="checkbox" class="switch_1" data-on="online" data-off="offline" data-toggle="toggle" value="{{$data->status}}" data-coupon_id="{{$data->id}}" data-onstyle="success" data-offstyle="danger"
                                                          @if($data->status == '1')
                                                          checked
                                                          @endif>

                                                        </td>
                                                        <td>{{date('Y-m-d',strtotime($data->created_at))}}</td>
                                                        <td>
                                                            <a href="javascript:void(0);" class="remove_coupon" data-coupon_id="{{$data->id}}">Delete</a>&nbsp;

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
             columns: [0,1,2,3,4,5,6,8]
         }
         }],
          "columnDefs": [
            {
                "targets": [ 6 ],
                "visible": false,
                "searchable": false
            },
          
        ]

        });
        
    });

      $(document).on('change',".switch_1",function(){
            
            var click = $(this);
            var cur_val = click.val();
            var coupon_id = click.data('coupon_id');
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
                 url:'{{route("changeCouponStatus")}}',
                 data : { "_token": "{{ csrf_token() }}","coupon_id": coupon_id,'status':new_val},
                 success:function(data){
                     res =  $.parseJSON(data);

                         $(".flash-message").html('<div class="alert alert-success"> Coupon Status Changed <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a> </div>'); 
                      
                 }

            });
            
    });

   $(document).on("click",".remove_coupon",function(){

          var click = $(this);
          var coupon_id = click.data('coupon_id');
          if(confirm("Are You sure you want to remove coupon ?"))
          {
              $.ajax({
                   type:"POST",
                   url:'{{route("removeCoupon")}}',
                   data : {
                   "_token": "{{ csrf_token() }}",
                    "coupon_id": coupon_id},
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
    