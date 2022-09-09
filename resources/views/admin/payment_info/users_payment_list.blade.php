@section('css-section')
<style>
.dataTables_paginate {
display: flex;
}
.dataTables_paginate  .pagination{
margin: 15px auto !important;
}

.multiselect-container>li>a>label{
height: 0px !important;
}
.dropdown-menu.show{
width: 230px !important;
}

.multiselect-container>li>a>label{
width: 230px !important;
}
.multiselect-container>li>a{
width: 230px !important;
}
ul.multiselect-container.dropdown-menu.show {
height: 120px;
padding-top: 20px;
border-radius: 5px;
width: 100% !important;
}
.date-id {
padding-left: 2px;
padding-right: 2px;
}
.dropdown-menu .active label {
    color: none !important;
}
.buttons-html5
{
  background: #5db5c5;
  color: white;
  border:#5db5c5;
  padding: 10px;
  margin-right: 5px;
}




@media (max-width: 420px){ 

div#users_datatable_filter{
margin-top: 40px;
float: left !important;
}
input.form-control.form-control-sm{
width: 100%;
}
header {
display: none;
}
footer{
display: none;
}


}




</style>
@endsection

@extends('layouts.app')
@section('content')
 <meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('public/css/multiple-select.css')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('public/css/bootstrap-multiselect.css')}}">

<div class="pcoded-main-container mt-4 mb-5">
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
                                <div class="container">
                                <form method="POST" class="frm_search row">

                                {{ csrf_field() }}
                                 <!-- <div class="col-xl-2 col-lg-2 col-md-2 mb-1">
                                       <fieldset class="form-group">
                                           <label for="basicInput"> User Type </label> 
                                              <select name="user_type[]" id="user_type" class="form-control multiselect-ui" multiple="" aria-multiselectable="true">
                                                <option value="4">Driver</option> 
                                                <option value="3">Transporter</option> 
                                              </select>
                                        </fieldset>
                                  </div>

                                  <div class="col-xl-2 col-lg-3 col-md-2 mb-1">
                                    <label for="basicInput">Search User </label>
                                    <input type="text" name="search_user" id="search_user" class="form-control">
                                  </div> -->
                                  <input type="hidden" name="user_id" id="user_id" value="{{$user_id}}">

                                  <div class="col-xl-2 col-lg-3 col-md-2 mb-1">
                                       <fieldset class="form-group">
                                           <label for="basicInput">From Date </label>
                                              <input type="date" name="from_date" id="from_date" class="form-control date-id">
                                        </fieldset>
                                  </div>
                                
                                  <div class="col-xl-2 col-lg-2 col-md-2 mb-1">
                                       <fieldset class="form-group">
                                           <label for="basicInput">To Date </label>
                                              <input type="date" name="end_date" id="to_date" class="form-control date-id">
                                        </fieldset>
                                  </div>                                

                                  <div class="col-xl-2 col-lg-2 col-md-2 mb-1">
                                       <fieldset class="form-group">
                                          <label for="basicInput">Quotation Type </label>
                                            <select name="quotation_type" id="quotation_type" class="form-control">
                                              <option value="">All</option> 
                                              <option value="0">Auction</option> 
                                              <option value="1">Fixed</option> 
                                              <option value="2">Instant Quote</option> 
                                            </select>
                                        </fieldset>
                                  </div>

                                  <div class="col-xl-2 col-lg-2 col-md-2 mb-1">
                                       <fieldset class="form-group">
                                          <label for="basicInput">Payment Status </label>
                                            <select name="payment_status" id="payment_status" class="form-control">
                                              <option value="">All</option> 
                                              <option value="1">Paid</option> 
                                              <option value="0">Unpaid</option> 
                                            </select>
                                        </fieldset>
                                  </div>              

                                  <div class="col-xl-2 col-lg-2 col-md-2 mb-1">
                                       <fieldset class="form-group">
                                           <label for="basicInput">Payment Type </label>
                                              <select name="payment_type" id="payment_type" class="form-control">
                                                <option value="">All</option> 
                                                <option value="0">Cash</option> 
                                                <option value="1">Credit card</option> 
                                                <option value="2">Via Transfer</option> 
                                              </select>
                                        </fieldset>
                                  </div>                                
                                  <div class="col-md-12 mb-1">
                                      <button style="background: #5db5c5;color: white;border:#5db5c5;" class="btncommon col-md-2 form-control" type="submit">SEARCH</button>
                                  </div>                                
                                </form>
                                </div>

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title" style="display: inline-block;">Payment Info List</h4>
                                       
                                    </div>
                                   <div class="card-body collapse show">
                                        <div class="card-block card-dashboard" style="padding: 0px 10px;">
                                            <!-- <p class="card-text">View and manage your stores</p> -->
                                            <table class="table table-striped table-responsive table-bordered file-export" id="users_datatable"  style="width: 100% !important;">
                                                <thead>
                                                 <?php $total_amount = 0; ?>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>Shipment ID</th>
                                                        <th>Shipper Name</th>
                                                        <th>Driver Name</th>
                                                        <th>Transporter Name</th>
                                                        <th>Pickup</th>
                                                        <th>Drop</th>
                                                        <th>Payment Type</th>
                                                        <th>Total Amount</th>
                                                        <th>Who Bear Commission</th>
                                                        <th>Commission Percentage</th>
                                                        <th>Admin Commission</th>
                                                        <th>Quotation Type</th>
                                                        <th>Order Date</th>
                                                        <th>Delivery Date</th>
                                                        <th>Order Status</th>
                                                        <th>Payment Status</th>
                                                        <!-- <th>Action</th> -->
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
                                                        <td>
                                                          @if($data->transporter_id != '0')
                                                          {{$data->transporter_first_name}} {{$data->transporter_last_name}}
                                                          @endif
                                                        </td>
                                                        <td>{{$data->pickup}}</td>
                                                        <td>{{$data->drop}}</td>
                                                        <td>
                                                            @if($data->payment_type == '0')
                                                            Cash
                                                            @else
                                                            Card
                                                            @endif
                                                        
                                                        </td>
                                                        <td>{{$data->amount}}
                                                         <?php $total_amount = $total_amount + $data->amount; ?></td>
                                                        <td>@if($data->commission_type == '0')
                                                            Transporter
                                                            @elseif($data->commission_type == '1')
                                                            Driver
                                                            @elseif($data->commission_type == '2')
                                                            Shipper
                                                            @endif</td>
                                                        <td>{{$data->commission_percent}}</td>
                                                        <td>{{$data->admin_portion}}</td>
                                                        <td>
                                                           @if($data->quotation_type == '0')
                                                            Auction
                                                            @elseif($data->quotation_type == '1')
                                                            Fixed
                                                            @else
                                                            Instant Quote
                                                            @endif
                                                        </td>
                                                        <td>{{$data->created_at}}</td>
                                                        <td>
                                                          @if($data->status == '6')
                                                          {{$data->updated_at}}
                                                          @endif
                                                        </td>
                                                        <td>
                                                            @if($data->status == '0')
                                                            Pending
                                                            @elseif($data->status == '1')
                                                            Schedule for Delivery
                                                            @elseif($data->status == '2')
                                                            On the way
                                                            @elseif($data->status == '3')
                                                            Cancelled
                                                            @elseif($data->status == '4')
                                                            Arrived at Pickup Location
                                                            @elseif($data->status == '5')
                                                            Shipment Started
                                                            @elseif($data->status == '8')
                                                            Arrived at Drop off Loctions
                                                            @else
                                                            Delivered
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($data->payment_status == '1')
                                                            Paid
                                                            @else
                                                            Unpaid
                                                            @endif
                                                        </td>
                                                        <!-- <td>
                                                            <a href="javascript:void(0);" class="" data-id="{{$data->id}}">View More</a>&nbsp;
                                                         </td> -->
            
                                                    </tr>
                                                    <?php $i++; ?>
                                                     @endforeach 
                                                </tbody>
                                                  <tfoot class="total_body">
                                                    <tr>
                                                        <th>Total</th>
                                                        <td colspan="7"></td>
                                                        <td class="total_amount">{{$total_amount}}</td>
                                                        <td colspan="4"></td>
                                                     </tr>
                                                  </tfoot>
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

 <script src="{{asset('public/js/bootstrap-multiselect.js')}}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

<script type="text/javascript">
/* ------------------ serch function ----------------*/
    $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();

        $('.total_body').css('display','none');
        $('.goog-close-link').trigger("click");
        $('.dt-button buttons-excel buttons-html5').css('display','none');
        $('.dt-button buttons-pdf buttons-html5').css('display','none');

        // DataTable initialisation
        $('#users_datatable').DataTable(
        {

          "iDisplayLength": 10,

          "dom": '<"dt-buttons"Bf><"clear">irtp',
          "paging": true,
          "autoWidth": true,
          "buttons": [
            {
              text: 'PDF',
              extend: 'pdfHtml5',
              filename: 'KMIOU',
              messageTop: function(){
                return "Total Amount: " + $(".total_amount").text();
              },
              footer: true,
              orientation: 'landscape', //portrait
              pageSize: 'A4', //A3 , A5 , A6 , legal , letter
              exportOptions: {
                columns: ':visible',
                search: 'applied',
                order: 'applied'
              },
              customize: function (doc) {
                //Remove the title created by datatTables
                doc.content.splice(0,1);
                //Create a date string that we use in the footer. Format is dd-mm-yyyy
                var now = new Date();
                var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear();
                // Logo converted to base64
                // The above call should work, but not when called from codepen.io
                // So we use a online converter and paste the string in.
                // Done on http://codebeautify.org/image-to-base64-converter
                // It's a LONG string scroll down to see the rest of the code !!!
               
                // A documentation reference can be found at
                // https://github.com/bpampuch/pdfmake#getting-started
                // Set page margins [left,top,right,bottom] or [horizontal,vertical]
                // or one number for equal spread
                // It's important to create enough space at the top for a header !!!
                doc.pageMargins = [20,60,20,30];
                // Set the font size fot the entire document
                doc.defaultStyle.fontSize = 7;
                // Set the fontsize for the table header
                doc.styles.tableHeader.fontSize = 7;
                // Create a header object with 3 columns
                // Left side: Logo
                // Middle: brandname
                // Right side: A document title
                doc['header']=(function() {
                  return {
                    columns: [

                      {
                        alignment: 'left',
                        italics: true,
                        text: 'Payment info',
                        fontSize: 18,
                        margin: [10,0]
                      }
                    ],
                    margin: 20
                  }
                });
                // Create a footer object with 2 columns
                // Left side: report creation date
                // Right side: current page and total pages
                doc['footer']=(function(page, pages) {
                  return {
                    columns: [
                      {
                        alignment: 'left',
                        text: ['Created on: ', { text: jsDate.toString() }]
                      },
                      {
                        alignment: 'right',
                        text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
                      }
                    ],
                    margin: 20
                  }
                });
                // Change dataTable layout (Table styling)
                // To use predefined layouts uncomment the line below and comment the custom lines below
                // doc.content[0].layout = 'lightHorizontalLines'; // noBorders , headerLineOnly
                var objLayout = {};
                objLayout['hLineWidth'] = function(i) { return .5; };
                objLayout['vLineWidth'] = function(i) { return .5; };
                objLayout['hLineColor'] = function(i) { return '#aaa'; };
                objLayout['vLineColor'] = function(i) { return '#aaa'; };
                objLayout['paddingLeft'] = function(i) { return 4; };
                objLayout['paddingRight'] = function(i) { return 4; };
                doc.content[0].layout = objLayout;
            }
            },
            {
              text: 'excel',
              extend: 'excelHtml5',
              filename: 'KMIOU',
              messageTop: function(){
                return "Total Amount: " + $(".total_amount").text();
              },
              footer: true,
            }]
        });
    });
  
    $('.dt-button buttons-excel buttons-html5').css('display','none');
    $('.dt-button buttons-pdf buttons-html5').css('display','none');

/*
      var table = $('#users_datatable').dataTable({
         // "aLengthMenu": [ [5, 10, 20, -1], [5, 10, 20, "All"] ],
         "iDisplayLength": 10,
         dom: "Bfrtip",
          buttons: ['excel','pdf']
         //responsive:true,

      });*/


      $(function() {
          $('#user_type').multiselect({
              includeSelectAllOption: true
          });
      });
/* ------------------ end serch function ----------------*/

    
    $(".frm_search").submit(function(e){

      e.preventDefault();

      var frm_data = $(".frm_search").serialize();

        $.ajax({
             type:'POST',
             url:"{{route('usersPaymentInfoFilter')}}",
             data :frm_data,
             success:function(data){
            $("#users_datatable").DataTable().destroy();
            $(".table_body").html("");
            
            res = JSON.parse(data); 
            
            if(res.result.length >0){

              var total_amount = 0;

              $.each(res.result, function( k, v ) { 
                
                var status = "";
                var user_name = "";
                var driver_name = "";
                var transporter_name = "";
                var who_bear_commission = "";
                var payment_type = "";
                var payment_status = "";
                var service_type = "";
                var quotation_type = "";
                var updated_at = "";
                var commission_percent = "";
                var admin_portion = "";

                commission_percent = (v.commission_percent == null)?'':v.commission_percent;
                admin_portion = (v.admin_portion == null)?'':v.admin_portion;
                
                if(v.payment_status == '1'){
                  payment_status = "Paid";
                }else{
                  payment_status = "Unpaid";
                }

                if(v.quotation_type == '0'){
                  quotation_type = "Auction";
                }else if(v.quotation_type == '1'){
                  quotation_type = "Fixed";
                }else {
                  quotation_type = "Instant Quote";
                }

                if(v.status == '6'){
                  updated_at = v.updated_at;
                }
               
               user_name = ((v.user_first_name == null)?'':v.user_first_name)+' '+((v.user_last_name == null)?'':v.user_last_name);
                
                driver_name = (v.driver_first_name == null)?'':v.driver_first_name+' '+((v.driver_last_name == null)?'':v.driver_last_name);
                
                transporter_name = (v.transporter_first_name == null)?'':v.transporter_first_name+' '+((v.transporter_last_name == null)?'':v.transporter_last_name);


                if(v.payment_type == '0'){

                   payment_type = 'Cash';
                    
                }else if(v.payment_type == '1'){

                   payment_type = 'Card';
                }else{

                   payment_type = 'Split Payment';
                    
                }

                if(v.status == '1'){
                
                  status = 'Accepted';
                
                }else if(v.status == '2'){
                
                  status = 'On The Way';
                
                }else if(v.status == '3'){
                
                  status = 'Cancelled';
                
                }else if(v.status == '4'){
                  
                  status = 'Arrived';

                }else if(v.status == '5'){
                  
                  status = 'Start Shipment';

                }else if(v.status == '6'){
                
                  status = 'Delivered';
                
                }else if(v.status == '7'){
                
                  status = 'Reported Emergency';
                }

                if(v.commission_type != null && v.commission_type == '0'){
                    who_bear_commission = "Transporter";
                }else if(v.commission_type != null && v.commission_type == '1'){
                    who_bear_commission = "Driver";
                }else if(v.commission_type != null && v.commission_type == '2'){
                    who_bear_commission = "Shipper";
                }
                

                $('.table_body').append('<tr> <th>'+(k+1)+'</th> <td>'+(v.unique_id)+'</td> <td style="max-width:200px;"> '+user_name+'</td> <td> '+driver_name+'</td> <td> '+transporter_name+'</td> <td> '+v.pickup+'</td> <td> '+v.drop+'</td> <td> '+payment_type+' </td> <td> '+v.amount+' </td> <td> '+who_bear_commission+' </td> <td> '+commission_percent+' </td>  <td> '+admin_portion+' </td> <td> '+quotation_type+' </td> <td> '+v.created_at+' </td> <td> '+updated_at+' </td> <td> '+status+' </td> <td> '+payment_status+'</td> </tr> '); 

                total_amount = total_amount + v.amount;

              }); 

              $('.total_amount').text(total_amount);

              $('#users_datatable').DataTable(
                {

                  "iDisplayLength": 10,

                  "dom": '<"dt-buttons"Bf><"clear">irtp',
                  "paging": true,
                  "autoWidth": true,
                  "buttons": [
                    {
                      text: 'PDF',
                      extend: 'pdfHtml5',
                      filename: 'KMIOU',
                      messageTop: function(){
                        return "Total Amount: " + $(".total_amount").text();
                      },
                      footer: true,
                      orientation: 'landscape', //portrait
                      pageSize: 'A4', //A3 , A5 , A6 , legal , letter
                      exportOptions: {
                        columns: ':visible',
                        search: 'applied',
                        order: 'applied'
                      },
                      customize: function (doc) {
                        //Remove the title created by datatTables
                        doc.content.splice(0,1);
                        //Create a date string that we use in the footer. Format is dd-mm-yyyy
                        var now = new Date();
                        var jsDate = now.getDate()+'-'+(now.getMonth()+1)+'-'+now.getFullYear();
                        // Logo converted to base64
                        // The above call should work, but not when called from codepen.io
                        // So we use a online converter and paste the string in.
                        // Done on http://codebeautify.org/image-to-base64-converter
                        // It's a LONG string scroll down to see the rest of the code !!!
                       
                        // A documentation reference can be found at
                        // https://github.com/bpampuch/pdfmake#getting-started
                        // Set page margins [left,top,right,bottom] or [horizontal,vertical]
                        // or one number for equal spread
                        // It's important to create enough space at the top for a header !!!
                        doc.pageMargins = [20,60,20,30];
                        // Set the font size fot the entire document
                        doc.defaultStyle.fontSize = 7;
                        // Set the fontsize for the table header
                        doc.styles.tableHeader.fontSize = 7;
                        // Create a header object with 3 columns
                        // Left side: Logo
                        // Middle: brandname
                        // Right side: A document title
                        doc['header']=(function() {
                          return {
                            columns: [

                              {
                                alignment: 'left',
                                italics: true,
                                text: 'Payment info',
                                fontSize: 18,
                                margin: [10,0]
                              }
                            ],
                            margin: 20
                          }
                        });
                        // Create a footer object with 2 columns
                        // Left side: report creation date
                        // Right side: current page and total pages
                        doc['footer']=(function(page, pages) {
                          return {
                            columns: [
                              {
                                alignment: 'left',
                                text: ['Created on: ', { text: jsDate.toString() }]
                              },
                              {
                                alignment: 'right',
                                text: ['page ', { text: page.toString() },  ' of ', { text: pages.toString() }]
                              }
                            ],
                            margin: 20
                          }
                        });
                        // Change dataTable layout (Table styling)
                        // To use predefined layouts uncomment the line below and comment the custom lines below
                        // doc.content[0].layout = 'lightHorizontalLines'; // noBorders , headerLineOnly
                        var objLayout = {};
                        objLayout['hLineWidth'] = function(i) { return .5; };
                        objLayout['vLineWidth'] = function(i) { return .5; };
                        objLayout['hLineColor'] = function(i) { return '#aaa'; };
                        objLayout['vLineColor'] = function(i) { return '#aaa'; };
                        objLayout['paddingLeft'] = function(i) { return 4; };
                        objLayout['paddingRight'] = function(i) { return 4; };
                        doc.content[0].layout = objLayout;
                    }
                    },
                    {
                      text: 'excel',
                      extend: 'excelHtml5',
                      filename: 'KMIOU',
                      messageTop: function(){
                        return "Total Amount: " + $(".total_amount").text();
                      },
                      footer: true,
                    }]
                });

                $('.dataTables_info').css('display',"none");
                $('.dataTables_paginate').css('display',"none");
                $('.dt-button buttons-excel buttons-html5').css('display','none');
                $('.dt-button buttons-pdf buttons-html5').css('display','none');

            }else{
                 
              $('.table_body').append('<tr><td></td><td></td> <td> </td> <td> </td> <td> </td> <td class="custom_td"  style="max-width:250px;"> </td> <td  colspan="4" class="custom_td text-center">No Data Available </td> <td class="custom_td"></td> <td class="custom_td"></td> <td> </td> <td></td> <td></td> <td> </td> <td> </td>  </tr>'); 
                 
                 $('.dataTables_info').css('display',"none");
                $('.dataTables_paginate').css('display',"none");
                $('.dt-button buttons-excel buttons-html5').css('display','none');
                $('.dt-button buttons-pdf buttons-html5').css('display','none');
             }
            }

        });                           
  }); 


</script>
@endsection
    