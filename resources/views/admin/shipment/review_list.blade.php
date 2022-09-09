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

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title" style="display: inline-block;">Review List</h4>
                                       
                                    </div>
                                   <div class="card-body collapse show">
                                        <div class="card-block card-dashboard" style="padding: 0px 10px;">
                                            <!-- <p class="card-text">View and manage your stores</p> -->
                                            <table class="table table-striped table-bordered file-export" id="users_datatable" style="width: 100% !important;">
                                                <thead>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Shipper Name</th>
                                                        <th>Driver Name</th>
                                                        <th>Review Text</th>
                                                        <th>Rate</th>
                                                        <th>Review Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                     </thead>
                                                    <tbody> 
                                                        <?php $i = 1; ?>
                                                     @foreach($data as $data)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$data->user_first_name}} {{$data->user_last_name}}</td>
                                                        <td>{{$data->driver_first_name}} {{$data->driver_last_name}}</td>
                                                        <td>{{$data->review_text}}</td>
                                                        <td>{{$data->rate}}</td>
                                                        <td>{{$data->created_at}}</td>
                                                        <td>
                                                            <a href="javascript:void(0);" class="delete" data-id="{{$data->id}}"><i class="fa fa-trash"></i></a>&nbsp;
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

        if(confirm("Are u sure you want to Delete ?"))
        {
            $.ajax({
            url:"{{route('removeReview')}}",
            type:"POST",
            data:{'_token':"{{csrf_token()}}",id:id},
            success:function(data){
                    click.parent().parent().remove();

                    $('.flash-message').html('<p class="alert alert-success"> Review Removed Successfully <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>');
                }
             })
        }
        else{
            return false;
        }
     });


/*------------------ end delete ----------------*/
</script>
@endsection
    