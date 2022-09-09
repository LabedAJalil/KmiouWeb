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
</style>
@endsection

@extends('admin.common.master')
@section('main-content')
 <meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('public/css/multiple-select.css')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('public/css/bootstrap-multiselect.css')}}">

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
                                <div class="container">
                                <form method="POST" class="frm_search row">

                                {{ csrf_field() }}
                                 
                                  <div class="col-xl-2 col-lg-2 col-md-2 mb-1">
                                       <fieldset class="form-group">
                                          <label for="basicInput">User Type </label>
                                            <select name="user_type" id="user_type" class="form-control">
                                              <!-- <option value="">All</option>  -->
                                              <option value="3">Transporter</option> 
                                              <option value="4">Driver</option> 
                                            </select>
                                        </fieldset>
                                  </div>

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

                                  <div class="col-md-12 mb-1">
                                      <button style="background: #5db5c5;color: white;border:#5db5c5;" class="btncommon col-md-2 form-control" type="submit">SEARCH</button>
                                  </div>
                                 </form>
                                </div>                             

                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title" style="display: inline-block;">Performance Report List</h4>
                                       
                                    </div>
                                   <div class="card-body collapse show">
                                        <div class="card-block card-dashboard" style="padding: 0px 10px;">
                                            <!-- <p class="card-text">View and manage your stores</p> -->
                                            <table class="table table-striped table-responsive table-bordered file-export" id="users_datatable"  style="width: 100% !important;">
                                                <thead>
                                                 <?php $total_amount = 0; ?>
                                                    <tr>
                                                        <th>Sr. No.</th>
                                                        <th>User Name</th>
                                                        <th>User Type</th>
                                                        <th>Accepted Shipments</th>
                                                        <th>Rejected Shipments</th>
                                                        <!-- <th>Action</th> -->
                                                    </tr>
                                                     </thead>
                                                    <tbody class="table_body"> 
                                                        <?php $i = 1; ?>
                                                     @foreach($data as $data)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{$data['user_first_name']}} {{$data['user_last_name']}}</td>
                                                        <td>
                                                          @if($data['user_type'] == '3')
                                                          Transporter
                                                          @elseif($data['user_type'] == '4')
                                                          Driver
                                                          @endif
                                                        </td>
                                                        <td>{{$data['accepted_shipment_count']}}</td>
                                                        <td>{{$data['rejected_shipment_count']}}</td>
                                                                    
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
                //return "Total Amount: " + $(".total_amount").text();
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
                //return "Total Amount: " + $(".total_amount").text();
              },
              footer: true,
            }]
        });
    });
    
/*
      var table = $('#users_datatable').dataTable({
         // "aLengthMenu": [ [5, 10, 20, -1], [5, 10, 20, "All"] ],
         "iDisplayLength": 10,
         dom: "Bfrtip",
          buttons: ['excel','pdf']
         //responsive:true,

      });*/

/* ------------------ end serch function ----------------*/

    
    $(".frm_search").submit(function(e){

      e.preventDefault();

      var frm_data = $(".frm_search").serialize();

        $.ajax({
             type:'POST',
             url:"{{route('performanceReportFilter')}}",
             data :frm_data,
             success:function(data){
            $("#users_datatable").DataTable().destroy();
            $(".table_body").html("");
            
            res = JSON.parse(data); 
            
            if(res.result.length >0){

              $.each(res.result, function( k, v ) { 
                
                var user_name = "";
                var user_type = "";
                
               user_name = ((v.user_first_name == null)?'':v.user_first_name)+' '+((v.user_last_name == null)?'':v.user_last_name);
                
                if(v.user_type != null && v.user_type == '3'){
                    user_type = "Transporter";
                }else if(v.user_type != null && v.user_type == '4'){
                    user_type = "Driver";
                }
                

                $('.table_body').append('<tr> <th>'+(k+1)+'</th> <td style="max-width:200px;"> '+user_name+'</td> <td> '+user_type+' </td> <td> '+v.accepted_shipment_count+' </td> <td> '+v.rejected_shipment_count+' </td> </tr> '); 

              }); 

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
                        //return "Total Amount: " + $(".total_amount").text();
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
                                text: 'Performance Report',
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
                        //return "Total Amount: " + $(".total_amount").text();
                      },
                      footer: true,
                    }]
                });

                $('.dataTables_info').css('display',"none");
                $('.dataTables_paginate').css('display',"none");


            }else{
                 
              $('.table_body').append('<tr><td></td><td></td><td  colspan="2" class="custom_td text-center">No Data Available </td> <td> </td>  </tr>'); 
                 
                 $('.dataTables_info').css('display',"none");
                $('.dataTables_paginate').css('display',"none");
             }
            }

        });                           
  }); 


</script>
@endsection
    