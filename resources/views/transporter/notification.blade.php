@extends('transporter.common.master')
@section('main-content')

<style>
.card-info-shipp{
width: 100%;
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
                                <h2>Notification List</h2>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- <div class="page-header-title">
                                        <h5 class="m-b-10">Info Heading</h5>
                                    </div> -->
                                    <div class="card Recent-Users">
                                        <div class="card-block px-0 py-3">
                                            <div class="card-info-shipp">
                                                <!-- <div class="user-name">
                                                    <p>Alex Christano</p>
                                                </div> --> 
                                        @if($notification_list != null && $notification_list != '[]')
                                    
                                            @foreach($notification_list as $key => $value)
                                                
                                                <label class="privacy-tit">{{$value['user_name']}}</label> <b><span class="float-right">{{$value['created_at']}}  <a href="javascript:void(0)" class="delete_notification" data-id="{{$value['id']}}" data-user_id="{{$value['to_user_id']}}"> &nbsp; &nbsp; &nbsp;  &nbsp;<i class="fa fa-trash"> </i> </a></span></b>
                                                <p>{{$value['message']}} &nbsp; &nbsp; 
                                                
                                                @if($value['noti_type'] != '13')
                                                    <a href="{{route('transporterShowShipmentRequestDetails',array('id'=>$value['ref_id']))}}">

                                                @elseif($value['noti_type'] == '7')
                                                    <a href="{{route('transporterShowCancelShipmentDetails',array('id'=>$value['ref_id']))}}">
                                                @else
                                                    <a href="javascript:void(0);" data-shipment_id="{{$value['ref_id']}}" id="pickup_noti_type" >
                                                
                                                @endif
                                                
                                                click here to check details </a>
                                            </p>
                                            
                                            @endforeach
                                        @else
                                        <h3> Notification List is Empty </h3>
                                        @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [ Main Content ] end -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ================ contact section end ================= -->
    </main>

       <!--arrival-drop Modal -->
      <div class="modal fade" id="pickupdrop" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" >
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Shipment ID : #SD21129123</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="row">
                  <div class="col-md-12 driv-block">
                        <div class="invoice-info">
                            <label class="det-label pickupdrop-mdl">Arrival time:</label>
                            <p class="pick-p" id="arrived_pickup_time">Mar 29, 09:41 CST</p>
                        </div>

                        <div class="invoice-info">
                            <label class="det-label pickupdrop-mdl">Start Shipment:</label>
                            <p class="pick-p" id="start_shipment_time">Mar 29, 09:41 CST</p>
                        </div>

                        <div class="invoice-info">
                            <label class="det-label pickupdrop-mdl">Pickup time Difference:</label>
                            <p class="pick-p" id="pick_time_diff">Mar 29, 09:41 CST</p>
                        </div>

                        <div class="invoice-info">
                            <label class="det-label pickupdrop-mdl">Depature time:</label>
                            <p class="pick-p" id="arrived_drop_time">Mar 29, 09:41 CST</p>
                        </div>

                        <div class="invoice-info">
                            <label class="det-label pickupdrop-mdl">Drop off time:</label>
                            <p class="pick-p" id="delivered_time">Mar 29, 09:41 CST</p>
                        </div>

                        <div class="invoice-info">
                            <label class="det-label pickupdrop-mdl">Drop time Difference:</label>
                            <p class="pick-p" id="drop_time_diff">Mar 29, 09:41 CST</p>
                        </div>
                      <!-- <p class="user-mob">+1 273 122 12</p> -->
                  </div>
              </div>
            </div>
            <!-- <div class="modal-footer text-center">
              <button type="button" class="btn btn-primary">Select</button>
            </div> -->
          </div>
        </div>
      </div>
  
    <!--arrival-drop Modal -->

@endsection

@section('js-section')

<script type="text/javascript">
    

        $(document).on("click","#pickup_noti_type",function(){
            
            var shipment_id = $(this).data('shipment_id');
        
            $.ajax({
                url:"{{route('showShipmentDetaintionInfo')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",shipment_id:shipment_id},
                success:function(data){
                        
                    res = JSON.parse(data);
                    var track = res.result[0];
                    

                    console.log(track[0]);
                    
                    if(res.success == '1'){

                        $('#exampleModalLongTitle').html("Shipment ID : "+track[0].shipment_id);
                        $('#arrived_pickup_time').html(track[0].arrived_pickup_time);
                        $('#start_shipment_time').html(track[0].start_shipment_time);
                        $('#pick_time_diff').html(track[0].pick_time_diff);
                        $('#arrived_drop_time').html(track[0].arrived_drop_time);
                        $('#delivered_time').html(track[0].delivered_time);
                        $('#drop_time_diff').html(track[0].drop_time_diff);

                        $('#pickupdrop').modal('show');
                    }  
                }  
            });
        });  


        $(document).on("click",".delete_notification",function(){
            
            var notification_id = $(this).data('id');
            var user_id = $(this).data('user_id');
        
            $.ajax({
                 type:"POST",
                 url:'{{route("deleteNotification")}}',
                 data : {
                  "_token": "{{ csrf_token() }}",
                  "user_id": user_id,
                  "notification_id": notification_id},
                 success:function(data){
                   
                    window.location.reload();
                }

            });
        }); 

</script>

@endsection
