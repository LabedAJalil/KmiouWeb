@extends('admin.common.master')

@section('main-content')
    
<!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <!-- [ breadcrumb ] start -->

        <ul class="navbar-nav ml-auto col-md-2">
             <li>
                <select class="form-control" style="margin-bottom: 15px;" id="filter_type">
                        <option value="0">24 hours</option>
                        <option value="1">Last 7 Days</option>
                        <option value="2">One Month</option>
                        <option value="3">One year</option>
                        <option value="4" selected>Lifetime</option>
                </select>
            </li>
        </ul>
                    <!-- [ breadcrumb ] end -->
                    <div class="main-body">
                        <div class="page-wrapper">
                            <!-- [ Main Content ] start -->
                            <!-- <select name="">
                                    <option value="0">Last 7 Days</option>
                                    <option value="1">One Month</option>
                                    <option value="2">One Year</option>
                                    <option value="2">Lifetime</option>
                                </select> -->
                            <div class="row">

                                <!--[ daily sales section ] start-->
                                <div class="col-md-4">
                                    <div class="card daily-sales">
                                        <div class="card-block">
                                            <h6 class="mb-4">Total Accepted Shipment</h6>
                                            <div class="row d-flex align-items-center">
                                                <div class="col-9">
                                                    <h3 class="f-w-300 d-flex align-items-center m-b-0" id="total_accepted_shipment">{{$data['total_accepted_shipment']}}</h3>
                                                    <a href="{{route('shipmentApprovedList',['filter_type' => '1'])}}">View All</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--[ daily sales section ] end-->
                                
                                <!--[ year  sales section ] starts-->
                                <div class="col-md-4">
                                    <div class="card yearly-sales">
                                        <div class="card-block">
                                            <h6 class="mb-4">Total Cancelled Shipment</h6>
                                            <div class="row d-flex align-items-center">
                                                <div class="col-9">
                                                    <h3 class="f-w-300 d-flex align-items-center  m-b-0" id="total_cancelled_shipment">{{$data['total_cancelled_shipment']}}</h3>
                                                    <a href="{{route('shipmentCancelledList')}}">View All</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--[ year  sales section ] end-->
                                
                                <!--[ Monthly  sales section ] starts-->
                                <div class="col-md-4">
                                    <div class="card Monthly-sales">
                                        <div class="card-block">
                                            <h6 class="mb-4">Total Reported Shipment</h6>
                                            <div class="row d-flex align-items-center">
                                                <div class="col-9">
                                                    <h3 class="f-w-300 d-flex align-items-center  m-b-0" id="total_reported_shipment">{{$data['total_reported_shipment']}}</h3>
                                                    <a href="{{route('shipmentReportedList')}}">View All</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card Monthly-sales">
                                        <div class="card-block">
                                            <div class="row d-flex align-items-center">
                                            	<div class="col-auto">
                                                    <i class="feather icon-zap f-30 text-c-green"></i>
                                                </div>
                                                <div class="col">
                                                    <h3 class="f-w-300" id="total_shipper">{{$data['total_shipper']}}</h3>
                                                    <span class="d-block text-uppercase">TOTAL SHIPPER</span>
                                                    <a href="{{route('userList')}}">View All</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card Monthly-sales">
                                        <div class="card-block">
                                            <div class="row d-flex align-items-center">
                                            	<div class="col-auto">
                                                    <i class="feather icon-map-pin f-30 text-c-blue"></i>
                                                </div>
                                                <div class="col">
                                                    <h3 class="f-w-300" id="total_transporter">{{$data['total_transporter']}}</h3>
                                                    <span class="d-block text-uppercase">TOTAL TRANSPORTER</span>
                                                    <a href="{{route('transporterUserList',['filter_type' => '1'])}}">View All</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card Monthly-sales">
                                        <div class="card-block">
                                            <div class="row d-flex align-items-center">
                                                <div class="col-auto">
                                                    <i class="feather icon-map-pin f-30 text-c-blue"></i>
                                                </div>
                                                <div class="col">
                                                    <h3 class="f-w-300" id="single_driver">{{$data['single_driver']}}</h3>
                                                    <span class="d-block text-uppercase">SINGLE DRIVER</span>
                                                    <a href="{{route('transporterUserList',['filter_type' => '2'])}}">View All</a>
                                                </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card Monthly-sales">
                                        <div class="card-block">
                                            <div class="row d-flex align-items-center">
                                                <div class="col">
                                                    <h6 class="mb-3">Pending Shipment Request (48 Hours)</h6>
                                                </div>
                                            </div>
                                            <h3 class="f-w-300 d-flex align-items-center  m-b-0" id="total_pending_request">{{$data['total_pending_request']}}<sub class="text-muted f-14">&nbsp; Requests</sub></h3>
                                            <a href="{{route('shipmentRequestList',['filter_type' => '1'])}}">View All</a>
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card Monthly-sales">
                                        <div class="card-block">
                                            <div class="row d-flex align-items-center">
                                                <div class="col">
                                                    <h6 class="mb-3">Instant Quote Request</h6>
                                                </div>
                                            </div>
                                            <h3 class="f-w-300 d-flex align-items-center  m-b-0" id="instant_quote_request">{{$data['instant_quote_request']}}<sub class="text-muted f-14">&nbsp; Requests</sub></h3>
                                            <a href="{{route('shipmentQuoteRequestList',['filter_type' => '1'])}}">View All</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card Monthly-sales">
                                        <div class="card-block">
                                            <div class="row d-flex align-items-center">
                                                <div class="col">
                                                    <h6 class="mb-3">Shipment Request</h6>
                                                </div>
                                            </div>
                                            <h3 class="f-w-300 d-flex align-items-center  m-b-0" id="total_request">{{$data['total_request']}}<sub class="text-muted f-14">Requests</sub></h3>
                                            <a href="{{route('shipmentRequestList')}}">View All</a>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ Main Content ] end -->
@endsection

@section('js-section')

 <!-- <script src="{{asset('public/js/parsley.js')}}"></script> -->

<script type="text/javascript">
  $(document).ready(function(){  

        DashboardFilter();
    });

        $("#filter_type").change(function() {  
            
            DashboardFilter();
            
        });


        function DashboardFilter(){
            
            var filter_type = $('#filter_type').val();

            $.ajax({
                url:"{{route('adminFilterDashboard')}}",
                type:"POST",
                data:{'_token':"{{csrf_token()}}",filter_type:filter_type},
                success:function(response){
                    
                    res = JSON.parse(response);
                    data = res.result;
                    if(res.success == '1'){
                        
                        $('#total_accepted_shipment').html(data.total_accepted_shipment);
                        $('#total_cancelled_shipment').html(data.total_cancelled_shipment);
                        $('#total_reported_shipment').html(data.total_reported_shipment);
                        $('#total_request').html(data.total_request);
                        $('#total_pending_request').html(data.total_pending_request);
                        $('#total_shipper').html(data.total_shipper);
                        $('#total_transporter').html(data.total_transporter);
                        $('#single_driver').html(data.single_driver);
                        $('#instant_quote_request').html(data.instant_quote_request);
                    }
                }
            });
        }
    

</script>
@endsection