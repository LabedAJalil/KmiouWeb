<?php

namespace App\Http\Controllers\transporter;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Hash;
use Mail;
use Exception;
use Validator;
use DateTime;
use PDF;
use Illuminate\Support\Facades\Auth;
use App\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\User;
use App\Review;
use App\Driver;
use App\Shipment;
use App\Goods_type;
use App\Payment_info;
use App\Shipment_bid;
use App\Shipment_info;
use App\Track_shipment;
use App\Transporter_truck;

class ShipmentController extends Controller
{

  private $timezone;

  public function show_request_list($filter_type = '4')
  { 
      $filter_type = ($filter_type == null || $filter_type == '')?'4':$filter_type;
      $city_list = DB::select("select * from city WHERE status ='1'");
      
      return view('transporter.request_list',compact('filter_type','city_list'));  
  }

  public function request_list_filter(Request $request)
    {
       $this->timezone = Auth::guard('transporter')->user()->timezone;

         $user_id = Auth::guard('transporter')->user()->id; 

         // update request count
        DB::table('shipment_request_count')->where('user_id',$user_id)->where('is_read',"!=","2")->update(['is_read' => '1']);

          $request_list = array(); 

          $query = '';  
          
          $request_list = array();  

          $get_rejected_shipment = DB::select('select GROUP_CONCAT( shipment_id ) as shipment_ids from shipment_request_count where user_id = '.$user_id.' AND is_read="2" ');


          // filter type 0 // schedule for delivery
          if($request->filter_type == "0"){
             
                $query = ' shipment.status = "0" AND shipment.transporter_id = "0" AND shipment.driver_id = "0" AND shipment.bid_status = "0" AND info.quotation_type != "2" ';
            

          // filter type 1 // bidded
          }else if($request->filter_type == "1"){
             
                $query = ' shipment.status = "0" AND info.quotation_type = "0" AND bid.user_id = '.$user_id.' AND bid.status = "0" AND shipment.bid_status = "0" ';
            

          // filter type 2 // pending award acceptence
          }else if($request->filter_type == "2"){
             
                $query = ' shipment.status = "0" AND info.quotation_type = "0" AND bid.status = "1" AND shipment.transporter_id = '.$user_id.' AND shipment.bid_status = "1" ';

            
          // filter type 3 // pending driver assignment
          }else if($request->filter_type == '3'){

                  $query = ' shipment.status = "1" AND shipment.transporter_id = '.$user_id.' AND shipment.driver_id = "0" ';
              
          // filter type 4 // All
          }else{

              /*$query = ' ( (shipment.status = "0" AND info.quotation_type != "2" ) OR (shipment.status = "1" AND shipment.driver_id = "0" AND shipment.transporter_id = '.$user_id.') ) '; */ //OLD
              
              $query = ' (shipment.status = "0" AND info.quotation_type != "2") ';
          }


          if($get_rejected_shipment[0]->shipment_ids != null){
            
            $query .= ' AND shipment.id not in ('.$get_rejected_shipment[0]->shipment_ids.') ';  
          }

          $check_user = User::find($user_id);
            // for newly registered user
            if($check_user->email_verified_at != null){
              
              $query .= ($query != '')?' AND (shipment.created_at >= "'.$check_user->email_verified_at.'" ) ':' (shipment.created_at >= "'.$check_user->email_verified_at.'" ) ';

            }

            if(!empty($request->departing_city))
            {

              $query .= ' AND (LOWER(CAST(shipment.pickup AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.trim(substr($request->departing_city, strpos($request->departing_city, '-') + 1)).'%")';    
            }


            if(!empty($request->arriving_city))
            {
              $query .= ' AND (LOWER(CAST(shipment.drop AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.trim(substr($request->arriving_city, strpos($request->arriving_city, '-') + 1)).'%")';     
            }

             if(!empty($request->from_date))
            {
              $query .= " AND DATE(info.pickup_date) >= '".$request->from_date."'";     
            }

            if(!empty($request->to_date))
            {
              $query .= " AND DATE(info.pickup_date) <= '".$request->to_date."'";     
            }

          $select_shipment = DB::select('SELECT shipment.*,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,info.quotation_type, info.pickup_date
            FROM shipment
            left join shipment_info as info on info.shipment_id=shipment.id
            left join shipment_bid as bid on bid.shipment_id = shipment.id
            left join users on users.id=shipment.user_id
           WHERE '.$query.' group by shipment.id order by info.pickup_date');
          
            if($select_shipment != null){

              foreach ($select_shipment as $key => $value) {
                  
                $select_request_count_shipment = DB::select('select * from shipment_request_count where user_id = '.$user_id.' AND shipment_id = '.$value->id.' limit 1 ');
                
                if(($select_request_count_shipment == [] || $select_request_count_shipment == null) && $value->quotation_type != '2' ){
                        
                        continue;
                    }
                    

                $status_color = '';
                $status_string = '';

                $select_bidder = Shipment_bid::where('shipment_id',$value->id)->where('user_id',$user_id)->first();

              if($value->bid_status == '0' || ($value->bid_status == '1' && isset($select_bidder->user_id) == $user_id && $select_bidder->status == '1' ) ){
                                  
                if($value->status == '0'){
                        
                      $status_color = '#FFC70D';
                      //$status_string = trans('word.Schedule For Delivery');//OLD
                      $status_string = 'Waiting For Acceptance';

                    if($value->bid_status == '1'){

                      $status_color = '#00874A';
                      $status_string = 'Pending Awards Acceptance';

                    }else if($value->bid_status == '0' && $value->quotation_type == '0'){
                    
                      if($select_bidder != null){

                        $status_color = '#00874A';
                        $status_string = 'Bidded';

                        if($request->filter_type == "0"){
                          continue;
                        }
                      
                      }
                    }
                  
                  }else{
                    
                    $status_color = '#00874A';
                    $status_string = 'Pending Driver Assignment';
                  }
                
                $data1 = array();

                $data1['shipment_id'] = $value->id;
                $data1['ship_id'] = $value->unique_id;
                $data1['shipper_id'] = $value->user_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
                $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                $data1['drop'] = is_null($value->drop)?'':$value->drop;
                $data1['amount'] = is_null($value->amount)?'':$value->amount;
                $data1['status_color'] = $status_color;
                $data1['status_string'] = $status_string;
                $data1['status'] = $value->status;
                $data1['bid_status'] = is_null($select_bidder)?'0':'1';
                /*$data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'d-M-Y h:i A', $this->timezone);*/
                $data1['created_at'] = date('jS F Y H:i', strtotime($value->pickup_date));  

                $total_amount = $value->amount;
                if($value->discount_amount != '0') {
                  $total_amount = $total_amount - $value->discount_amount;
                }
                $data1['amount'] = $total_amount.' DA';
                
                if($value->quotation_type == "0" && $value->status == '0') { //Auction
                  $data1['amount'] = 'Give Your Price';
                }
                
                array_push($request_list,$data1);
              }
            }
          }

        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $request_list ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' => $request_list ]);
    } 

    public function show_request_shipment_details($id)
    { 
        $this->timezone = Auth::guard('transporter')->user()->timezone;

        $user_id = Auth::guard('transporter')->user()->id; 
        
        $details = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,info.person_name,info.id_proof_image,info.signature_image,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,truck.truck_img, truck.truck_name, info.sender_first_name, info.sender_last_name, info.sender_mobile, info.receiver_first_name, info.receiver_last_name, info.receiver_mobile
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.user_id
              left join users as driver on driver.id=shipment.driver_id
              left join truck on truck.id=shipment.vehicle_id
             WHERE shipment.id IN('.$id.') LIMIT 1');
            
          if($select_shipment != null && ($select_shipment[0]->status == '0' || ($select_shipment[0]->status == '1' && $select_shipment[0]->driver_id == '0' ) ) ){

              $bid_amount = 0;
              $is_bid_selected = '0';

              if($select_shipment[0]->quotation_type == '0'){

                  $check_bid = Shipment_bid::where('shipment_id',$id)->where('user_id',$user_id)->first();

                  if($check_bid != null){

                    if($check_bid->status == '1'){
                    
                      $bid_amount = $check_bid->bid_amount;
                      $is_bid_selected = '1';
                    
                    }else if($check_bid->status == '0'){

                      $bid_amount = $check_bid->bid_amount;
                    
                    }else if($check_bid->status == '2'){

                        session()->flash('alert-warning','Shipper Rejected Your Bid');
                        return redirect()->route('transporterShowRequestList');
                    }
                  }
              }
            
              if($select_shipment[0]->transporter_id == $user_id){
                 $is_bid_selected = '1';
              }

              foreach ($select_shipment as $key => $value) {
                
                  $data1 = array();

                  $total_amount = $value->amount;

                  if($value->discount_amount != '0'){
                    $total_amount = $total_amount - $value->discount_amount;
                  }

                  $goods = array();

                   if($value->goods_type != '' && $value->goods_type != null){

                      $goods = explode (",", $value->goods_type);
                   }

                   $goods_type_name = '';

                  foreach($goods as $key => $goods_value){
                      
                       if($key != '0'){
                          $goods_type_name .= ', ';
                       } 

                        $goods = Goods_type::find($goods_value);
                      
                        if($goods != null){
                            $goods_type_name .= $goods->goods_type_name;
                        }
                  }

                  $data1['truck'] = [];
                  
                  $data1['shipment_id'] = $value->id;
                  $data1['ship_id'] = $value->unique_id;
                  $data1['shipper_id'] = $value->user_id;
                  $data1['driver_id'] = $value->driver_id;
                  $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                  $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                  $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
                  $data1['driver_first_name'] = is_null($value->driver_first_name)?'':$value->driver_first_name;
                  $data1['driver_last_name'] = is_null($value->driver_last_name)?'':$value->driver_last_name;
                  $data1['driver_profile_pic'] = is_null($value->driver_profile_pic)?'':$value->driver_profile_pic;
                    $data1['quotation_type'] = $value->quotation_type;
                    $data1['quotation_amount'] = is_null($value->quotation_amount)?0:$value->quotation_amount;
                    $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                    $data1['pickup_date'] = date('jS F Y H:i',strtotime($value->pickup_date));
                    $data1['drop'] = is_null($value->drop)?'':$value->drop;
                    $data1['service_type'] = '';
                    $data1['goods_type_name'] = $goods_type_name;
                    $data1['weight'] = is_null($value->weight)?0:$value->weight;
                    $data1['weight_type'] = is_null($value->weight_type)?0:$value->weight_type;
                    $data1['no_of_vehicle'] = is_null($value->total_vehicle)?0:$value->total_vehicle;
                    $data1['info'] = is_null($value->info)?'':$value->info;
                    $data1['amount'] = is_null($value->amount)?'':$value->amount;
                    $data1['base_fare'] = is_null($value->amount)?'0':$value->amount;
                    $data1['total_amount'] = $total_amount;
                    $data1['tax_per'] = is_null($value->tax_per)?'0':$value->tax_per;
                    $data1['tax_amount'] = is_null($value->tax_amount)?'0':$value->tax_amount;
                    $data1['discount_per'] = is_null($value->discount_per)?'0':$value->discount_per;
                    $data1['discount_amount'] = is_null($value->discount_amount)?'0':$value->discount_amount;

                    $get_kmiou_charges = Payment_info::where('shipment_id',$value->id)->first();

                  if($get_kmiou_charges != null){

                      $data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent)?'0 %':$get_kmiou_charges->percent.'(%)';
                      $data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion)?'0 DA':$get_kmiou_charges->admin_portion.' DA';

                    $base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
                    $data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0')?'':$base_fare;

                    }else{

                      $data1['kmiou_charges_per'] = '0 %';
                      $data1['kmiou_charges_amount'] = '0 DA';
                    }


                    $data1['bid_amount'] = $bid_amount;
                    $data1['is_bid_selected'] = $is_bid_selected;
                    $data1['document'] = is_null($value->document)?'':$value->document;
                    $data1['person_name'] = is_null($value->person_name)?'':$value->person_name;
                    $data1['id_proof_image'] = is_null($value->id_proof_image)?'':$value->id_proof_image;
                    $data1['signature_image'] = is_null($value->signature_image)?'':$value->signature_image;
                    $data1['status'] = is_null($value->status)?'':$value->status;
                    $data1['truck_name'] = is_null($value->truck_name)?'': $value->truck_name;
                    $data1['truck_img'] = is_null($value->truck_img)?'': $value->truck_img;
                    $data1['payment_type'] = is_null($value->payment_type)?0:$value->payment_type;
                    $data1['payment_status'] = is_null($value->payment_status)?'':$value->payment_status;
                    $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);

                    $truck_arr = array();

                    if($value->total_vehicle > '1'){

                        $get_total_truck_of_shipment = DB::select(' select * from shipment where unique_id = '.$value->unique_id.' AND (shipment.status = "0" OR (shipment.status = "1" AND shipment.driver_id = "0") )  AND bid_status="'.$value->bid_status.'"');

                        $get_truck_shipment_ids = DB::select(' select GROUP_CONCAT(id) as shipment_ids from shipment where unique_id = '.$value->unique_id.' AND (shipment.status = "0" OR (shipment.status = "1" AND shipment.driver_id = "0")) AND bid_status="'.$value->bid_status.'"');

                        if(COUNT($get_total_truck_of_shipment) > '1' && $value->status == '0')
                        {
                            $data3 = array();
                            $data3['title'] = 'All';
                            $data3['shipment_id'] = $get_truck_shipment_ids[0]->shipment_ids;
            
                            array_push($truck_arr,$data3);
                        
                        }
                      
                        foreach ($get_total_truck_of_shipment as $key => $value) {
                          
                          //get truck number
                          $get_truck_number = Shipment_info::where('shipment_id',$value->id)->first();

                          $data2 = array();
                          $data2['title'] = 'Truck No : '.$get_truck_number->no_of_vehicle;
                          $data2['shipment_id'] = $value->id;
          
                          array_push($truck_arr,$data2);
                      
                        }
                    }
                    
                    $data1['truck'] = $truck_arr;
                    
                    array_push($details,$data1);
              }
          }else{
            
            if($select_shipment[0]->status == '3'){
            
              return redirect()->route('transporterShowCancelShipmentDetails',['id'=>$id]);
            
            }else if($select_shipment[0]->status == '7'){
              
              return redirect()->route('transporterShowReportShipmentDetails',['id'=>$id]);
            
            }else if($select_shipment[0]->status == '6'){
              
              return redirect()->route('transporterShowPastShipmentDetails',['id'=>$id]);
            
            }else{
              return redirect()->route('transporterShowRequestList');
            }
          }

          $doc = array();

         if($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null){

            $str = $select_shipment[0]->document;

            $doc = explode ("#####", $str);
         }

        return view('transporter.request_shipment_details',compact('details','doc','id'));  
    } 

    public function place_new_bid(Request $request)
    {
    	$user_id = Auth::guard('transporter')->user()->id;


      $shipment_ids = explode (",", $request->shipment_id);

      $msg = '';

      foreach ($shipment_ids as $key => $shipment_id) {

  		$shipment = Shipment::find($shipment_id);

  		if($shipment != null && $shipment->status == '0' && $shipment->bid_status == '0'){

  			$check_bid = is_null(Shipment_bid::where('shipment_id',$shipment_id)->where('user_id',$user_id)->first())? new Shipment_bid : Shipment_bid::where('shipment_id',$shipment_id)->where('user_id',$user_id)->first();

  			$check_bid->shipment_id = $shipment_id;
  			$check_bid->user_id = $user_id;
  			$check_bid->bid_amount = $request->bid_amount;
  			
  			$check_bid->save();

        $get_truck_no = Shipment_info::where('shipment_id',$shipment_id)->first();

        // send notification
          Helper::send_push_notification($user_id,$shipment->user_id,'New Bid Placed','Placed New Bid to Your Order Truck No. '.$get_truck_no->no_of_vehicle.' Order No. #'.$shipment->unique_id,'2',$shipment->id);

   		
        }
        else
        {
          session()->flash('alert-danger','Shipment Not Found');

        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
            return json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]);
            
        }
      }

      session()->flash('alert-success','Bid Placed Successfully');

      Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Bid Placed Successfully','result' => [] ]));
      return json_encode(['success' => 1, 'msg' => 'Bid Placed Successfully','result' => [] ]);
    }

    public function edit_delete_bid(Request $request)
      {
        try{

            $shipment_ids = explode (",", $request->shipment_id);

            $msg = '';

          foreach ($shipment_ids as $key => $shipment_id) 
          {

            $user_id = Auth::guard('transporter')->user()->id;

            $shipment = Shipment::find($shipment_id);   
            
            $check_bid = Shipment_bid::where('shipment_id',$shipment_id)->where('user_id',$user_id)->first();

            $msg = '';

            if($shipment != null)
            {

              if($check_bid != null)
              {
                if($request->type == '0')
                {

                  $check_bid->shipment_id = $shipment_id;
                  $check_bid->user_id = $user_id;
                  $check_bid->bid_amount = $request->bid_amount;
                  
                  $check_bid->save();

                  $msg = 'Bid Updated Successfully';

                }else if($request->type == '1'){

                  $check_bid->delete();
                  
                  $msg = 'Bid Cancelled Successfully';

                  DB::table('notification')->where('from_user_id',$user_id)->where('noti_type',"2")->where('ref_id',$shipment_id)->delete();
                }
              }
              else
              {
                  $check_bid = new Shipment_bid; 
                  $check_bid->shipment_id = $shipment_id;
                  $check_bid->user_id = $user_id;
                  $check_bid->bid_amount = $request->bid_amount;
                  
                  $check_bid->save();

                  $msg = 'Bid Updated Successfully';

              }
              
            }
            else
            {
              Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Bid Not Found','result' => [] ]));
                return json_encode(['success' => 0, 'msg' => 'Bid Not Found','result' => [] ]);
                
            }
        }

          Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
                return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
       
        
      } catch(Exception $ex) {
              
              Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
              return json_encode(['success' => 0, 'msg' => trans('auth.technical_issue'), 'result' => $ex->getMessage() ]);
        }
      }

	public function accept_reject_shipment_request(Request $request)
  {
  		$user_id = Auth::guard('transporter')->user()->id;

  		//$shipment = Shipment::find($request->shipment_id);	 

      $shipment_ids = explode(",", $request->shipment_id);

        $msg = '';

      foreach ($shipment_ids as $key => $shipment_id) {
        
        $shipment = Shipment::find($shipment_id);  

  		  if($shipment != null && $shipment->status == '0'){

      			$is_accept = $request->is_accept;
      			$msg = '';
                
      			if($is_accept == '0'){
      				
      				$msg = 'Request Rejected Successfully';

              $shipment->transporter_id = '0';
              $shipment->driver_id = '0';
               

              if($shipment->bid_status == '1'){

                $bid = Shipment_bid::where('shipment_id',$request->shipment_id)->where('user_id',$user_id)->first();
                //$bid->status = '2';

                $bid->delete();
                
                $shipment->bid_status = '0';

                DB::table('shipment_request_count')->where('shipment_id',$shipment->id)->update(['is_read' => '0']);
                  
                $shipment_info = Shipment_info::where('shipment_id',$shipment->id)->first();

                    if($shipment_info->quotation_type == '0'){
                        $order_type = 'Bid Shipment Truck No. '.($shipment_info->no_of_vehicle).' Order';
                      }else if ($shipment_info->quotation_type == '1'){
                        $order_type = 'Fixed Shipment Truck No. '.($shipment_info->no_of_vehicle).' Order';
                      }

                      $check_transporter = User::where('user_type',"3")->where('approve',"1")->where('is_verify','1')->where('status','1')->orderBy('created_at','desc')->get(); 
                    
                      foreach ($check_transporter as $key => $value) {

                          app()->setLocale('en'); 
                          $msg_en = trans('word.Book New').' '.$order_type.''.trans('word.No').' #'.$shipment->unique_id;

                          app()->setLocale('fr'); 
                          $msg_fr = trans('word.Book New').' '.$order_type.''.trans('word.No').' #'.$shipment->unique_id;

                          app()->setLocale('ar'); 
                          $msg_ar = trans('word.Book New').' '.$order_type.''.trans('word.No').' #'.$shipment->unique_id;

                            // send notification
                            Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment', $msg_en,'1',$shipment->id,$msg_fr,$msg_ar);
                      }

                      $check_driver = User::where('user_type',"4")->where('approve',"1")->where('is_verify','1')->where('status','1')->where('ref_id','0')->orderBy('created_at','desc')->get(); 

                      foreach ($check_driver as $key => $value) {
                      
                        app()->setLocale('en'); 
                        $msg_en = trans('word.Book New').' '.$order_type.''.trans('word.No').' #'.$shipment->unique_id;

                        app()->setLocale('fr'); 
                        $msg_fr = trans('word.Book New').' '.$order_type.''.trans('word.No').' #'.$shipment->unique_id;

                        app()->setLocale('ar'); 
                        $msg_ar = trans('word.Book New').' '.$order_type.''.trans('word.No').' #'.$shipment->unique_id;

                      // send notification
                      Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment', $msg_en,'1',$shipment->id,$msg_fr,$msg_ar);
                      }

              }else{
                
                // mark as rejecet shipment and not showing this shipment later
                DB::table('shipment_request_count')->where('shipment_id',$shipment->id)->where('user_id',$user_id)->update(['is_read' => '2']);
              }
                
              // no need to send notification to shipper when transporter reject shipment before acceptance
                
              /*$get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

              //send notification to shipper 
                Helper::send_push_notification($user_id,$shipment->user_id,'Order Rejected','rejected your order Truck No. '.$get_truck_no->no_of_vehicle.' Order No. #'.$shipment->unique_id,'9',$shipment->id);*/

      			}else{

                  // update discount in shipment
                  if($shipment->discount_per != '0' && $shipment->bid_status == '1'){
                      $shipment->discount_amount = ($shipment->amount * $shipment->discount_per) / 100;
                  }

                  if($request->assign_driver_id != '0'){


                    $check_truck = Transporter_truck::where('truck_id',$shipment->vehicle_id)->where('user_id',$request->assign_driver_id)->where('status',"1")->first();

                    if($check_truck != null) {

                      $shipment->driver_id = $request->assign_driver_id;
                        
                      $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();
                      
                          app()->setLocale('en'); 
                        $msg_en = trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

                        app()->setLocale('fr'); 
                        $msg_fr = trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

                        app()->setLocale('ar'); 
                        $msg_ar = trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;
          
                        // send notification
                        Helper::send_push_notification($user_id,$shipment->driver_id,'Assign Driver',$msg_en,'4',$shipment->id,$msg_fr,$msg_ar);
                    }else {

                      app()->setLocale('en');
                      $msg = trans('word.This Driver have different truck');
                      session()->flash('alert-warning', $msg);
                      Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
                      return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
                    }
                  }

                  // take commission from shipper
                  $get_commission_percent = User::find($shipment->user_id);

                  if($get_commission_percent->is_commission == '1'){

                     $admin_portion = ($shipment->amount * $get_commission_percent->commission_percent) / 100;

                        $payment_info = new Payment_info;
                            $payment_info->shipment_id = $shipment->id;
                            $payment_info->user_id = $shipment->user_id;
                            $payment_info->percent = $get_commission_percent->commission_percent;
                            $payment_info->admin_portion = $admin_portion; 
                            $payment_info->amount = $shipment->amount;
                            $payment_info->type = "2";
                            $payment_info->payment_status = $shipment->payment_status;
                    
                        $payment_info->save();
                  }

                  // take commission from transporter
                    $get_transporter_commission_percent = User::find($shipment->transporter_id);

                  if($get_transporter_commission_percent != null &&  $get_transporter_commission_percent->is_commission == '1'){

                      $admin_portion = ($shipment->amount * $get_transporter_commission_percent->commission_percent) / 100;

                        $payment_info = new Payment_info;
                            $payment_info->shipment_id = $shipment->id;
                            $payment_info->user_id = $shipment->transporter_id;
                            $payment_info->percent = $get_transporter_commission_percent->commission_percent;
                            $payment_info->admin_portion = $admin_portion; 
                            $payment_info->amount = $shipment->amount;
                            $payment_info->type = "0";
                            $payment_info->payment_status = $shipment->payment_status;
                    
                        $payment_info->save();
                    }

                  // take commission from driver
                    $get_driver_commission_percent = User::find($shipment->driver_id);

                  if($get_driver_commission_percent != null &&  $get_driver_commission_percent->is_commission == '1'){

                      $admin_portion = ($shipment->amount * $get_driver_commission_percent->commission_percent) / 100;

                      $payment_info = new Payment_info;
                            $payment_info->shipment_id = $shipment->id;
                            $payment_info->user_id = $shipment->driver_id;
                            $payment_info->percent = $get_driver_commission_percent->commission_percent;
                            $payment_info->admin_portion = $admin_portion; 
                            $payment_info->amount = $shipment->amount;
                            $payment_info->type = "1";
                            $payment_info->payment_status = $shipment->payment_status;
                    
                        $payment_info->save();
                    }


                    $track_shipment = new Track_shipment;
                    $track_shipment->shipment_id = $shipment->id;
                    $track_shipment->status = '1';
                    $track_shipment->payment_status = $shipment->payment_status;
                    $track_shipment->save();

                    $shipment->status = '1';
                    $shipment->transporter_id = $user_id;
                    $msg = 'Request Accepted Successfully';
            }

              // read request count 
              DB::table('shipment_request_count')->where('shipment_id',$shipment->id)->update(['is_read' => '1']);
              $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

              app()->setLocale('en'); 
              $msg_en = trans('word.accepted your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

              app()->setLocale('fr'); 
              $msg_fr = trans('word.accepted your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

              app()->setLocale('ar'); 
              $msg_ar = trans('word.accepted your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

              // send notification
              Helper::send_push_notification($user_id,$shipment->user_id,'Order Accepted',$msg_en,'5',$shipment->id,$msg_fr,$msg_ar);
      			
      			$shipment->save();
        }
        else
        {
        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
            return json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]);
            
        }

      }

        session()->flash('alert-success', $msg);
          
          Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
            return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
  }


  public function show_active_shipment($filter_type = '0')
  { 
      $filter_type = ($filter_type == null || $filter_type == '')?'1':$filter_type;
      $city_list = DB::select("select * from city WHERE status ='1'");
      
      return view('transporter.active_shipment',compact('filter_type','city_list'));  
  }

  public function active_shipment_filter(Request $request)
  {
      $this->timezone = Auth::guard('transporter')->user()->timezone;

      $user_id = Auth::guard('transporter')->user()->id; 

        $active_list = array();  

        $query = '';

        /*if($request->filter_type != "0"){
          
            $query = ' shipment.status = "'.$request->filter_type.'" ';
          
        }else{

            $query = ' shipment.status IN ("1","2","4","5","8","9") ';
        }*/

        if($request->filter_type == '1'){        
          $query = ' shipment.status IN ("1") ';
        }else{
          $query = ' shipment.status IN ("2","4","5","8","9") ';
        }

        if(!empty($request->departing_city))
        {

        $query .= ' AND (LOWER(CAST(shipment.pickup AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.trim(substr($request->departing_city, strpos($request->departing_city, '-') + 1)).'%")';    
        }


        if(!empty($request->arriving_city))
        {
        $query .= ' AND (LOWER(CAST(shipment.drop AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.trim(substr($request->arriving_city, strpos($request->arriving_city, '-') + 1)).'%")';     
        }

        if(!empty($request->from_date))
        {
          $query .= " AND DATE(info.pickup_date) >= '".$request->from_date."'";     
        }

        if(!empty($request->to_date))
        {
          $query .= " AND DATE(info.pickup_date) <= '".$request->to_date."'";     
        }

        
        $select_shipment = DB::select('SELECT shipment.*,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,info.quotation_type, info.pickup_date
          FROM shipment
          left join shipment_info as info on info.shipment_id=shipment.id
          left join users on users.id=shipment.user_id
         WHERE '.$query.' AND shipment.driver_id != "0" AND shipment.transporter_id = '.$user_id.' order by info.pickup_date desc');
          
          if($select_shipment != null){

            foreach ($select_shipment as $key => $value) {
              
              $data1 = array();

              $data1['shipment_id'] = $value->id;
              $data1['ship_id'] = $value->unique_id;
              $data1['shipper_id'] = $value->user_id;
              $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
              $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
              $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
              $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
              $data1['drop'] = is_null($value->drop)?'':$value->drop;
              $data1['status'] = is_null($value->status)?'':$value->status;
              $data1['quotation_type'] = is_null($value->quotation_type)?'':$value->quotation_type;
              $data1['amount'] = is_null($value->amount)?'':$value->amount;
              $data1['created_at'] = Helper::convertTimestampWithTimezone($value->pickup_date,'d-M-Y h:i A', $this->timezone);

              $total_amount = $value->amount;
              if($value->discount_amount != '0') {
                $total_amount = $total_amount - $value->discount_amount;
              }
              $data1['amount'] = $total_amount;
              
              array_push($active_list,$data1);
            }
          }

        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $active_list ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' => $active_list ]);
  }

  public function cancel_shipment(Request $request)
  {

        $user_id = Auth::guard('transporter')->user()->id;

        $shipment = Shipment::find($request->shipment_id);   
        $shipper_user = User::find($shipment->user_id);

        if($shipment != null && ($shipment->status == '1' || $shipment->status == '2') ){

          $msg = '';

            $shipment->status = '3';
            $msg = 'Shipment Cancelled Successfully';

            $shipment->cancel_reason = $request->cancel_reason;
            $shipment->cancel_comment = $request->comment;
            $shipment->cancel_by = $user_id;
          
            $shipment->save();

            $track_shipment = new Track_shipment;
              $track_shipment->shipment_id = $shipment->id;
              $track_shipment->status = '3';
              $track_shipment->payment_status = $shipment->payment_status;
              $track_shipment->save();


          $message = '';

          if($request->cancel_reason == '0'){

          $message = 'Accident';

          }else if($request->cancel_reason == '1'){

          $message = 'Engine Problem';

          }else if($request->cancel_reason == '2'){

          $message = 'Fuel Over';

          }else if($request->cancel_reason == '3'){

          $message = 'Medical Emergency';

          }else if($request->cancel_reason == '4'){

          $message = 'Other Reason';

          }

          $shipment_info = Shipment_info::where('shipment_id',$request->shipment_id)->first();

          // send notification shipper
          Helper::send_push_notification($user_id,$shipment->user_id,'Order Cancelled',' (transporter) cancelled your order due to '.$message.' Truck No. '.$shipment_info->no_of_vehicle.' Order No. #'.$shipment->unique_id,'7',$shipment->id);

          // send notification driver
          if($shipment->driver_id != '0')
          {
            Helper::send_push_notification($user_id,$shipment->driver_id,'Order Cancelled',' (transporter) cancelled your order due to '.$message.' Truck No. '.$shipment_info->no_of_vehicle.' Order No. #'.$shipment->unique_id,'7',$shipment->id);
          }


          //recreate shipment

           
            $discount_per = '0';


            $new_shipment = new Shipment;
            $new_shipment->unique_id = $shipment->unique_id;
            $new_shipment->driver_id = '0';
            $new_shipment->user_id = $shipment->user_id;
            $new_shipment->vehicle_id = $shipment->vehicle_id;
            $new_shipment->card_id = $shipment->card_id;
            $new_shipment->promo_id = isset($shipment->promo_id)?$shipment->promo_id:'0';

            if(isset($shipment->promo_id) && $shipment->promo_id != '0' && $shipment->promo_id != ''){

            $get_coupon = Coupon::find($shipment->promo_id);
            $discount_per = $get_coupon->discount;
            }

            $new_shipment->pickup = $shipment->pickup;
            $new_shipment->drop = $shipment->drop;
            $new_shipment->amount = '0';
            $new_shipment->basic_amount = '0';
            $new_shipment->payment_type = ($shipper_user->payment_type == '1')?'2':$shipment->payment_type;
            $new_shipment->discount_per = $discount_per;
            $new_shipment->receipt = $shipment->receipt;
            $new_shipment->status = '0';
            $new_shipment->payment_status = '0';

            // update discount in shipment
            if($new_shipment->discount_per != '0' && $shipment->quotation_type != '0'){
            $new_shipment->discount_amount = ($new_shipment->amount * $new_shipment->discount_per) / 100;
            }

            $new_shipment->save();



            $new_shipment_info = new Shipment_info;

            $new_shipment_info->shipment_id = $new_shipment->id;
            $new_shipment_info->pickup_date = $shipment_info->pickup_date;
            $new_shipment_info->pickup_lat = $shipment_info->pickup_lat;
            $new_shipment_info->pickup_long = $shipment_info->pickup_long;
            $new_shipment_info->drop_lat = $shipment_info->drop_lat;
            $new_shipment_info->drop_long = $shipment_info->drop_long;
            $new_shipment_info->no_of_vehicle = $shipment_info->no_of_vehicle;
            $new_shipment_info->total_vehicle = $shipment_info->total_vehicle;
            $new_shipment_info->sender_first_name = $shipment_info->sender_first_name;
            $new_shipment_info->sender_last_name = $shipment_info->sender_last_name;
            $new_shipment_info->sender_email = $shipment_info->sender_email;
            $new_shipment_info->sender_mobile = $shipment_info->sender_mobile;
            $new_shipment_info->receiver_first_name = $shipment_info->receiver_first_name;
            $new_shipment_info->receiver_last_name = $shipment_info->receiver_last_name;
            $new_shipment_info->receiver_email = $shipment_info->receiver_email;
            $new_shipment_info->receiver_mobile = $shipment_info->receiver_mobile;
            $new_shipment_info->quotation_type = $shipment_info->quotation_type;
            $new_shipment_info->quotation_amount = $shipment_info->quotation_amount;
            $new_shipment_info->goods_type = $shipment_info->goods_type;
            $new_shipment_info->weight_type = $shipment_info->weight_type;
            $new_shipment_info->weight = $shipment_info->weight;
            $new_shipment_info->document = $shipment_info->document;
            $new_shipment_info->info = $shipment_info->info;

            $new_shipment_info->save();

            $track_shipment = new Track_shipment;
            $track_shipment->shipment_id = $new_shipment->id;
            $track_shipment->status = '0';
            $track_shipment->payment_status = '0';
            $track_shipment->save();

            $order_type = '';

           if($shipment_info->quotation_type == '0'){
              $order_type = 'Bid Shipment Truck No. '.($shipment_info->no_of_vehicle).' Order';
            }
            else if ($shipment_info->quotation_type == '1'){
              $order_type = 'Fixed Shipment Truck No. '.($shipment_info->no_of_vehicle).' Order';
            }


            
            $check_transporter = User::where('user_type',"3")->where('approve',"1")->where('is_verify','1')->where('status','1')->where('id','!=',$user_id)->orderBy('created_at','desc')->get();

            foreach ($check_transporter as $key => $value) {

            // send notification
            Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment',' Book New '.$order_type.' No. #'.$shipment->unique_id,'1',$new_shipment->id);
            }

            $check_driver = User::where('user_type',"4")->where('approve',"1")->where('is_verify','1')->where('status','1')->where('ref_id','0')->where('id','!=',$user_id)->orderBy('created_at','desc')->get();

            foreach ($check_driver as $key => $value) {

            // send notification
            Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment',' Book New '.$order_type.' No. #'.$shipment->unique_id,'1',$new_shipment->id);
            }
            
            session()->flash('alert-success', $msg);
            return redirect()->route('transporterShowActiveShipment');
          }
          else
          {
            return back()->with('alert-warning', 'Shipment Not Found');   
          }
  }

  public function report_emergency(Request $request)
  {
      $user_id = Auth::guard('transporter')->user()->id;

        $shipment = Shipment::find($request->shipment_id);   

        if($shipment != null && ($shipment->status == '1' || $shipment->status == '2'|| $shipment->status == '4' || $shipment->status == '5') ){

          $msg = '';

          $comment_img_url = null;

          if($request->hasFile('comment')){

              $validator = Validator::make($request->all(), [
                'comment' => 'required',
                'comment.*.file' => 'image|mimes:jpg,jpeg,png',
              ]);
              
                 
              foreach ($request->file('comment') as $key => $request_doc) {  
              
                if ($validator->fails()) {
                  return back()->with('alert-warning', 'Only image files allowed !!');
                } 
                else
                {
                  $pro_pic_url = null;          
                  $pro_pic = $request_doc;
                  $name = time().$key.'.'.$pro_pic->getClientOriginalExtension();

                  $destinationPath = public_path('images/doc');
                  $pro_pic->move($destinationPath, $name);

                  $pro_pic_url = asset('public/images/doc').'/'.$name;

                  if($key == '0'){
                      $comment_img_url .= $pro_pic_url;
                  }else{
                      $comment_img_url .= '#####'.$pro_pic_url;
                  }

                }
              }
          }

            $shipment->status = '7';
            $msg = 'Shipment Reported Successfully';

            $shipment->report_emergency = $request->report_reason;
            $shipment->report_comment = $comment_img_url;
            $shipment->updated_by = $user_id;
          
            $shipment->save();

            $track_shipment = new Track_shipment;
              $track_shipment->shipment_id = $shipment->id;
              $track_shipment->status = '7';
              $track_shipment->payment_status = $shipment->payment_status;
              $track_shipment->save();

            session()->flash('alert-success', $msg);
            return redirect()->route('transporterShowActiveShipment');
          }
          else
          {
              return back()->with('alert-warning', 'Shipment Not Found');
          }
  }

  public function transporter_update_pay_shipment_status(Request $request){

        $user_id = Auth::guard('transporter')->user()->id;

        $shipment = Shipment::find($request->shipment_id);
        
        if($shipment != null){

          $shipment->payment_status = '1';
          $shipment->payment_received_by = $user_id;
        
          $shipment->save();

            $msg = 'Payment Status Updated Successfully';
            Helper::logs($_POST,json_encode(['success' => 1, 'msg' =>  $msg,'result' => [] ]));
            return json_encode(['success' => 1, 'msg' =>  $msg,'result' => [] ]);
        
        }else{
            
            $msg = 'Shipment Not Found';
            Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
            return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
            
        }
    }

  public function update_shipment_status(Request $request)
  {
        $user_id = Auth::guard('transporter')->user()->id;

        $shipment = Shipment::find($request->shipment_id);   
        
        if($shipment != null && ($shipment->status == '1' || $shipment->status == '2'  || $shipment->status == '4'  || $shipment->status == '5' || $shipment->status == '8') ){

            $msg = '';  

            $shipment->status = $request->shipment_status;
            
            $msg = 'Shipment Status Updated Successfully';

            $shipment->updated_by = $user_id;
          
            $shipment->save();

            $track_shipment = new Track_shipment;
              $track_shipment->shipment_id = $shipment->id;
              $track_shipment->status = $request->shipment_status;
              $track_shipment->payment_status = $shipment->payment_status;
              $track_shipment->save();

            session()->flash('alert-success', $msg);
            return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
          }
          else
          {
            return redirect()->route('transporterShowPastShipment');
          }
  }


  public function show_active_shipment_detail($id)
  { 
      $this->timezone = Auth::guard('transporter')->user()->timezone;

        $user_id = Auth::guard('transporter')->user()->id; 
        
        $details = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,users.profile_pic as shipper_profile_pic,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.email as shipper_email,users.mobile_no as shipper_mobile,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,driver.email as driver_email,truck.truck_img, truck.truck_name, info.sender_first_name, info.sender_last_name, info.sender_mobile, info.receiver_first_name, info.receiver_last_name, info.receiver_mobile
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.user_id
              left join users as driver on driver.id=shipment.driver_id
              left join truck on truck.id=shipment.vehicle_id
             WHERE shipment.id = '.$id.' ');
            
          if($select_shipment != null && $select_shipment[0]->status != '3' && $select_shipment[0]->status != '7' && $select_shipment[0]->status != '6'){

              foreach ($select_shipment as $key => $value) {
                
                  $data1 = array();

                  $total_amount = $value->amount;

                  if($value->discount_amount != '0'){
                    $total_amount = $total_amount - $value->discount_amount;
                  }

                  $goods = array();

                   if($value->goods_type != '' && $value->goods_type != null){

                      $goods = explode (",", $value->goods_type);
                   }

                   $goods_type_name = '';

                  foreach($goods as $key => $goods_value){
                      
                       if($key != '0'){
                          $goods_type_name .= ', ';
                       } 

                        $good = Goods_type::find($goods_value);
                      
                        if($good != null){
                            $goods_type_name .= $good->goods_type_name;
                        }
                  }
                  
                  $data1['truck'] = [];

                  $data1['shipment_id'] = $value->id;
                  $data1['ship_id'] = $value->unique_id;
                  $data1['shipper_id'] = $value->user_id;
                  $data1['driver_id'] = $value->driver_id;
                  $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                  $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                  $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
                  $data1['shipper_email'] = is_null($value->shipper_email)?'':$value->shipper_email;
                  $data1['shipper_mobile'] = is_null($value->shipper_mobile)?'':$value->shipper_mobile;
                  $data1['driver_first_name'] = is_null($value->driver_first_name)?'':$value->driver_first_name;
                  $data1['driver_last_name'] = is_null($value->driver_last_name)?'':$value->driver_last_name;
                  $data1['driver_profile_pic'] = is_null($value->driver_profile_pic)?'':$value->driver_profile_pic;
                  $data1['driver_email'] = is_null($value->driver_email)?'':$value->driver_email;
                  $data1['driver_mobile'] = is_null($value->driver_mobile)?'':$value->driver_mobile;
	                $data1['sender_first_name'] = is_null($value->sender_first_name)?'':$value->sender_first_name;
                  $data1['sender_last_name'] = is_null($value->sender_last_name)?'':$value->sender_last_name;
                  $data1['sender_mobile'] = is_null($value->sender_mobile)?'':$value->sender_mobile;
            
                  $data1['receiver_first_name'] = is_null($value->receiver_first_name)?'':$value->receiver_first_name;
                  $data1['receiver_last_name'] = is_null($value->receiver_last_name)?'':$value->receiver_last_name;
                  $data1['receiver_mobile'] = is_null($value->receiver_mobile)?'':$value->receiver_mobile;
                  $data1['quotation_type'] = $value->quotation_type;
	                $data1['quotation_amount'] = is_null($value->quotation_amount)?0:$value->quotation_amount;
	                $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
	                $data1['pickup_date'] = date('jS F Y H:i',strtotime($value->pickup_date));
	                $data1['drop'] = is_null($value->drop)?'':$value->drop;
	                $data1['service_type'] = '';
	                $data1['goods_type_name'] = $goods_type_name;
	                $data1['weight'] = is_null($value->weight)?0:$value->weight;
	                $data1['weight_type'] = is_null($value->weight_type)?0:$value->weight_type;
	                $data1['no_of_vehicle'] = is_null($value->total_vehicle)?0:$value->total_vehicle;
	                $data1['info'] = is_null($value->info)?'':$value->info;
	                $data1['amount'] = is_null($value->amount)?'':$value->amount;
                  $data1['base_fare'] = is_null($value->amount)?'0':$value->amount;
                  $data1['total_amount'] = $total_amount;
                  $data1['tax_per'] = is_null($value->tax_per)?'0':$value->tax_per;
                  $data1['tax_amount'] = is_null($value->tax_amount)?'0':$value->tax_amount;
                  $data1['discount_per'] = is_null($value->discount_per)?'0':$value->discount_per;
                  $data1['discount_amount'] = is_null($value->discount_amount)?'0':$value->discount_amount;

                  $get_kmiou_charges = Payment_info::where('shipment_id',$value->id)->first();

                  if($get_kmiou_charges != null){

                    $data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent)?'0 %':$get_kmiou_charges->percent.'(%)';
                    $data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion)?'0 DA':$get_kmiou_charges->admin_portion.' DA';

                    $base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
                    $data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0')?'':$base_fare;

                  }else{

                    $data1['kmiou_charges_per'] = '0 %';
                    $data1['kmiou_charges_amount'] = '0 DA';
                  }


	                $data1['document'] = is_null($value->document)?'':$value->document;
	                $data1['status'] = is_null($value->status)?'':$value->status;
                  $data1['truck_name'] = is_null($value->truck_name)?'': $value->truck_name;
                  $data1['truck_img'] = is_null($value->truck_img)?'': $value->truck_img;
	                $data1['payment_type'] = is_null($value->payment_type)?0:$value->payment_type;
	                $data1['payment_status'] = is_null($value->payment_status)?'':$value->payment_status;
	                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);

                  $truck_arr = array();

                    if($value->total_vehicle > '1'){

                      $get_total_truck_of_shipment = DB::select(' select * from shipment where unique_id = '.$value->unique_id.' AND shipment.status IN ("1","4","5","2","8") AND shipment.transporter_id = '.$user_id.' ');
                    
                      foreach ($get_total_truck_of_shipment as $key => $value) {
                        
                        //get truck number
                        $get_truck_number = Shipment_info::where('shipment_id',$value->id)->first();

                        $data2 = array();
                        $data2['title'] = 'Truck No : '.$get_truck_number->no_of_vehicle;
                        $data2['shipment_id'] = $value->id;
        
                        array_push($truck_arr,$data2);
                    
                      }
                  }
                  
                  $data1['truck'] = $truck_arr;
	                
	                array_push($details,$data1);
              }
          
          }else{

            if($select_shipment[0]->status == '3'){
            
              return redirect()->route('transporterShowCancelShipmentDetails',['id'=>$id]);
            
            }else if($select_shipment[0]->status == '7'){
              
              return redirect()->route('transporterShowReportShipmentDetails',['id'=>$id]);
            
            }else if($select_shipment[0]->status == '6'){
              
              return redirect()->route('transporterShowPastShipmentDetails',['id'=>$id]);
            
            }else{
      	      return redirect()->route('transporterShowActiveShipment');
            }
          }

          $doc = array();

         if($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null){

            $str = $select_shipment[0]->document;

            $doc = explode ("#####", $str);
         }

    return view('transporter.active_shipment_details',compact('details','doc'));  

  }

    public function show_past_shipment(Request $req)
    { 
        $filter_type = is_null($req->filter_type)?'0':$req->filter_type;
        $city_list = DB::select("select * from city WHERE status ='1'");
        return view('transporter.past_shipment',compact('past_list','city_list','filter_type')); 
    }

    public function past_shipment_filter(Request $request)
    {
        $this->timezone = Auth::guard('transporter')->user()->timezone;

        $user_id = Auth::guard('transporter')->user()->id; 

          $past_list = array(); 

          $query = ''; 
          $filter_query = 'shipment.status IN ("3","6") '; 

          if($request->filter_type == '1' ){
              
              $filter_query = 'shipment.status = "6" '; 
              $status_color = '#12D612';
              $status_string = 'Delivered';

          }else if($request->filter_type == '2' ){
          
              $filter_query = 'shipment.status = "3" '; 
              $status_color = '#ed3709';
              $status_string = 'Cancelled';

          }else if($request->filter_type == '3' ){
          
              $filter_query = 'shipment.status = "7" ';
              $status_color = '#EF5163';
              $status_string = 'Reported'; 
          
          }else{
          
              $filter_query = 'shipment.status IN ("3","6") '; 
              $status_color = '#EF5163';
              $status_string = ''; 
          }

          if(!empty($request->departing_city))
          {

            $query .= ' AND (LOWER(CAST(shipment.pickup AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.trim(substr($request->departing_city, strpos($request->departing_city, '-') + 1)).'%")';    
          }

          if(!empty($request->arriving_city))
          {
            $query .= ' AND (LOWER(CAST(shipment.drop AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.trim(substr($request->arriving_city, strpos($request->arriving_city, '-') + 1)).'%")';     
          }               

          if(!empty($request->from_date))
          {
            $query .= " AND DATE(info.pickup_date) >= '".$request->from_date."'";     
          }

          if(!empty($request->to_date))
          {
            $query .= " AND DATE(info.pickup_date) <= '".$request->to_date."'";     
          }
          
          $select_shipment = DB::select('SELECT shipment.*,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,info.pickup_date
            FROM shipment
            left join shipment_info as info on info.shipment_id=shipment.id
            left join users on users.id=shipment.user_id
           WHERE '.$filter_query.' AND shipment.transporter_id = '.$user_id.' '.$query.' order by shipment.created_at desc');
            
            if($select_shipment != null){

              foreach ($select_shipment as $key => $value) {
                
                $data1 = array();

                $data1['shipment_id'] = $value->id;
                $data1['ship_id'] = $value->unique_id;
                $data1['shipper_id'] = $value->user_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
                $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                $data1['drop'] = is_null($value->drop)?'':$value->drop;
                $data1['status_color'] = $status_color;
                $data1['status_string'] = $status_string;
                $data1['status'] = is_null($value->status)?'':$value->status;
                $data1['amount'] = is_null($value->amount)?'':$value->amount;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->pickup_date,'Y-m-d H:i:s', $this->timezone);
                
                array_push($past_list,$data1);
              }
            }

        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $past_list ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' => $past_list ]);
    }

    public function show_past_shipment_details($id)
    {   
        $this->timezone = Auth::guard('transporter')->user()->timezone;

        $user_id = Auth::guard('transporter')->user()->id; 
        
        $details = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,info.person_name,info.id_proof_image,info.signature_image,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.mobile_no as shipper_mobile,users.email as shipper_email,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,driver.email as driver_email,truck.truck_img, truck.truck_name, info.sender_first_name, info.sender_last_name, info.sender_mobile, info.receiver_first_name, info.receiver_last_name, info.receiver_mobile
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.user_id
              left join users as driver on driver.id=shipment.driver_id
              left join truck on truck.id=shipment.vehicle_id
             WHERE shipment.id = '.$id.' AND shipment.status = "6" ');
            
          if($select_shipment != null){

              foreach ($select_shipment as $key => $value) {
                
                  $data1 = array();
                  
                  $total_amount = $value->amount;

                  if($value->discount_amount != '0'){
                    $total_amount = $total_amount - $value->discount_amount;
                  }

                  $goods = array();

                   if($value->goods_type != '' && $value->goods_type != null){

                      $goods = explode (",", $value->goods_type);
                   }

                   $goods_type_name = '';

                  foreach($goods as $key => $goods_value){
                      
                       if($key != '0'){
                          $goods_type_name .= ', ';
                       } 

                        $goods = Goods_type::find($goods_value);
                      
                        if($goods != null){
                            $goods_type_name .= $goods->goods_type_name;
                        }
                  }

                  $data1['truck'] = [];
                  
                  $data1['shipment_id'] = $value->id;
                  $data1['ship_id'] = $value->unique_id;
                  $data1['shipper_id'] = $value->user_id;
                  $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                  $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                  $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
                  $data1['shipper_email'] = is_null($value->shipper_email)?'':$value->shipper_email;
                  $data1['shipper_mobile'] = is_null($value->shipper_mobile)?'':$value->shipper_mobile;
                  $data1['driver_first_name'] = is_null($value->driver_first_name)?'':$value->driver_first_name;
                  $data1['driver_last_name'] = is_null($value->driver_last_name)?'':$value->driver_last_name;
                  $data1['driver_profile_pic'] = is_null($value->driver_profile_pic)?'':$value->driver_profile_pic;
                  $data1['driver_email'] = is_null($value->driver_email)?'':$value->driver_email;
                  $data1['driver_mobile'] = is_null($value->driver_mobile)?'':$value->driver_mobile;
                  $data1['sender_first_name'] = is_null($value->sender_first_name)?'':$value->sender_first_name;
                  $data1['sender_last_name'] = is_null($value->sender_last_name)?'':$value->sender_last_name;
                  $data1['sender_mobile'] = is_null($value->sender_mobile)?'':$value->sender_mobile;
            
                  $data1['receiver_first_name'] = is_null($value->receiver_first_name)?'':$value->receiver_first_name;
                  $data1['receiver_last_name'] = is_null($value->receiver_last_name)?'':$value->receiver_last_name;
                  $data1['receiver_mobile'] = is_null($value->receiver_mobile)?'':$value->receiver_mobile;
	                $data1['quotation_type'] = $value->quotation_type;
	                $data1['quotation_amount'] = is_null($value->quotation_amount)?0:$value->quotation_amount;
	                $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
	                $data1['pickup_date'] = date('jS F Y H:i',strtotime($value->pickup_date));
	                $data1['drop'] = is_null($value->drop)?'':$value->drop;
	                $data1['service_type'] = '';
	                $data1['goods_type_name'] = $goods_type_name;
	                $data1['weight'] = is_null($value->weight)?0:$value->weight;
	                $data1['weight_type'] = is_null($value->weight_type)?0:$value->weight_type;
	                $data1['no_of_vehicle'] = is_null($value->total_vehicle)?0:$value->total_vehicle;
	                $data1['info'] = is_null($value->info)?'':$value->info;
	                $data1['amount'] = is_null($value->amount)?'':$value->amount;
                  $data1['base_fare'] = is_null($value->amount)?'0':$value->amount;
                  $data1['total_amount'] = $total_amount;
                  $data1['tax_per'] = is_null($value->tax_per)?'0':$value->tax_per;
                  $data1['tax_amount'] = is_null($value->tax_amount)?'0':$value->tax_amount;
                  $data1['discount_per'] = is_null($value->discount_per)?'0':$value->discount_per;
                  $data1['discount_amount'] = is_null($value->discount_amount)?'0':$value->discount_amount;

                  $get_kmiou_charges = Payment_info::where('shipment_id',$value->id)->first();

                  if($get_kmiou_charges != null){

                      $data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent)?'0 %':$get_kmiou_charges->percent.'(%)';
                      $data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion)?'0 DA':$get_kmiou_charges->admin_portion.' DA';

                      $base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
                      $data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0')?'':$base_fare;

                    }else{

                      $data1['kmiou_charges_per'] = '0 %';
                      $data1['kmiou_charges_amount'] = '0 DA';
                    }


	                $data1['document'] = is_null($value->document)?'':$value->document;
                  $data1['person_name'] = is_null($value->person_name)?'':$value->person_name;
                  $data1['id_proof_image'] = is_null($value->id_proof_image)?'':$value->id_proof_image;
                  $data1['signature_image'] = is_null($value->signature_image)?'':$value->signature_image;
	                $data1['status'] = is_null($value->status)?'':$value->status;
                  $data1['truck_name'] = is_null($value->truck_name)?'': $value->truck_name;
                  $data1['truck_img'] = is_null($value->truck_img)?'': $value->truck_img;
	                $data1['payment_type'] = is_null($value->payment_type)?0:$value->payment_type;
	                $data1['payment_status'] = is_null($value->payment_status)?'':$value->payment_status;
	                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);
	                
                  $truck_arr = array();

                    if($value->total_vehicle > '1'){

                      $get_total_truck_of_shipment = DB::select(' select * from shipment where unique_id = '.$value->unique_id.' AND shipment.status = "6" AND shipment.transporter_id = '.$user_id.' ');
                    
                      foreach ($get_total_truck_of_shipment as $key => $value) {
                        
                        //get truck number
                        $get_truck_number = Shipment_info::where('shipment_id',$value->id)->first();

                        $data2 = array();
                        $data2['title'] = 'Truck No : '.$get_truck_number->no_of_vehicle;
                        $data2['shipment_id'] = $value->id;
        
                        array_push($truck_arr,$data2);
                    
                      }
                  }
                  
                  $data1['truck'] = $truck_arr;

	                array_push($details,$data1);
              }
          
          }else{

          	return redirect()->route('transporterShowPastShipment');
          }

          $doc = array();

         if($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null){

            $str = $select_shipment[0]->document;

            $doc = explode ("#####", $str);
         }

      return view('transporter.past_shipment_details',compact('details','doc'));  

    }

    public function show_cancel_shipment()
    {
        $this->timezone = Auth::guard('transporter')->user()->timezone;

        $user_id = Auth::guard('transporter')->user()->id; 

          $cancel_list = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name
            FROM shipment
            left join shipment_info as info on info.shipment_id=shipment.id
            left join users on users.id=shipment.user_id
           WHERE shipment.status = "3" AND cancel_by != "0" AND shipment.transporter_id = '.$user_id.' order by shipment.created_at desc');
            
            if($select_shipment != null){

              foreach ($select_shipment as $key => $value) {
                
                $data1 = array();

                $data1['shipment_id'] = $value->id;
                $data1['ship_id'] = $value->unique_id;
                $data1['shipper_id'] = $value->user_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
                $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                $data1['drop'] = is_null($value->drop)?'':$value->drop;
                $data1['amount'] = is_null($value->amount)?'':$value->amount;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);
                
                array_push($cancel_list,$data1);
              }
            }

          return view('transporter.cancel_shipment',compact('cancel_list'));  
    }

    public function show_cancel_shipment_detail($id)
    {   
        $this->timezone = Auth::guard('transporter')->user()->timezone;

        $user_id = Auth::guard('transporter')->user()->id; 
        
        $details = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.mobile_no as shipper_mobile,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,truck.truck_img, truck.truck_name, info.sender_first_name, info.sender_last_name, info.sender_mobile, info.receiver_first_name, info.receiver_last_name, info.receiver_mobile
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.user_id
              left join users as driver on driver.id=shipment.driver_id
              left join truck on truck.id=shipment.vehicle_id
             WHERE shipment.id = '.$id.' ');
            
          if($select_shipment != null){

              foreach ($select_shipment as $key => $value) {
                
                  $data1 = array();

                  $total_amount = $value->amount;

                  if($value->discount_amount != '0'){
                    $total_amount = $total_amount - $value->discount_amount;
                  }

                  $goods = array();

                   if($value->goods_type != '' && $value->goods_type != null){

                      $goods = explode (",", $value->goods_type);
                   }

                   $goods_type_name = '';

                  foreach($goods as $key => $goods_value){
                      
                       if($key != '0'){
                          $goods_type_name .= ', ';
                       } 

                        $goods = Goods_type::find($goods_value);
                      
                        if($goods != null){
                            $goods_type_name .= $goods->goods_type_name;
                        }
                  }

                  $data1['truck'] = [];
                  
                  $data1['shipment_id'] = $value->id;
                  $data1['ship_id'] = $value->unique_id;
                  $data1['shipper_id'] = $value->user_id;
                  $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                  $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                  $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
                  $data1['shipper_mobile'] = is_null($value->shipper_mobile)?'':$value->shipper_mobile;
                  $data1['driver_first_name'] = is_null($value->driver_first_name)?'':$value->driver_first_name;
                  $data1['driver_last_name'] = is_null($value->driver_last_name)?'':$value->driver_last_name;
                  $data1['driver_profile_pic'] = is_null($value->driver_profile_pic)?'':$value->driver_profile_pic;
                  $data1['driver_mobile'] = is_null($value->driver_mobile)?'':$value->driver_mobile;
                  $data1['sender_first_name'] = is_null($value->sender_first_name)?'':$value->sender_first_name;
                  $data1['sender_last_name'] = is_null($value->sender_last_name)?'':$value->sender_last_name;
                  $data1['sender_mobile'] = is_null($value->sender_mobile)?'':$value->sender_mobile;
            
                  $data1['receiver_first_name'] = is_null($value->receiver_first_name)?'':$value->receiver_first_name;
                  $data1['receiver_last_name'] = is_null($value->receiver_last_name)?'':$value->receiver_last_name;
                  $data1['receiver_mobile'] = is_null($value->receiver_mobile)?'':$value->receiver_mobile;
                  $data1['quotation_type'] = $value->quotation_type;
                  $data1['quotation_amount'] = is_null($value->quotation_amount)?0:$value->quotation_amount;
                  $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                  $data1['pickup_date'] = date('jS F Y H:i',strtotime($value->pickup_date));
                  $data1['drop'] = is_null($value->drop)?'':$value->drop;
                  $data1['service_type'] = '';
                  $data1['goods_type_name'] = $goods_type_name;
                  $data1['weight'] = is_null($value->weight)?0:$value->weight;
                  $data1['weight_type'] = is_null($value->weight_type)?0:$value->weight_type;
                  $data1['no_of_vehicle'] = is_null($value->total_vehicle)?0:$value->total_vehicle;
                  $data1['info'] = is_null($value->info)?'':$value->info;
                  $data1['amount'] = is_null($value->amount)?'':$value->amount;
                  $data1['base_fare'] = is_null($value->amount)?'0':$value->amount;
                  $data1['total_amount'] = $total_amount;
                  $data1['tax_per'] = is_null($value->tax_per)?'0':$value->tax_per;
                  $data1['tax_amount'] = is_null($value->tax_amount)?'0':$value->tax_amount;
                  $data1['discount_per'] = is_null($value->discount_per)?'0':$value->discount_per;
                  $data1['discount_amount'] = is_null($value->discount_amount)?'0':$value->discount_amount;

                  $get_kmiou_charges = Payment_info::where('shipment_id',$value->id)->first();

                  if($get_kmiou_charges != null){

                      $data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent)?'0 %':$get_kmiou_charges->percent.'(%)';
                      $data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion)?'0 DA':$get_kmiou_charges->admin_portion.' DA';

                      $base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
                      $data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0')?'':$base_fare;

                    }else{

                      $data1['kmiou_charges_per'] = '0 %';
                      $data1['kmiou_charges_amount'] = '0 DA';
                    }


                  $data1['document'] = is_null($value->document)?'':$value->document;
                  $data1['status'] = is_null($value->status)?'':$value->status;
                  $data1['truck_name'] = is_null($value->truck_name)?'': $value->truck_name;
                  $data1['truck_img'] = is_null($value->truck_img)?'': $value->truck_img;
                  $data1['cancel_reason'] = is_null($value->cancel_reason)?'0':$value->cancel_reason;
                  $data1['cancel_comment'] = is_null($value->cancel_comment)?'':$value->cancel_comment;
                  $data1['payment_type'] = is_null($value->payment_type)?0:$value->payment_type;
                  $data1['payment_status'] = is_null($value->payment_status)?'':$value->payment_status;
                  $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);

                  $truck_arr = array();

                    if($value->total_vehicle > '1'){

                      $get_total_truck_of_shipment = DB::select(' select * from shipment where unique_id = '.$value->unique_id.' AND shipment.status = "3" AND shipment.transporter_id = '.$user_id.' ');
                    
                      foreach ($get_total_truck_of_shipment as $key => $value) {
                        
                        //get truck number
                        $get_truck_number = Shipment_info::where('shipment_id',$value->id)->first();

                        $data2 = array();
                        $data2['title'] = 'Truck No : '.$get_truck_number->no_of_vehicle;
                        $data2['shipment_id'] = $value->id;
        
                        array_push($truck_arr,$data2);
                    
                      }
                  }
                  
                  $data1['truck'] = $truck_arr;
                  
                  array_push($details,$data1);
              }
          
          }else{

            return redirect()->route('transporterShowCancelShipment');
          }

          $doc = array();

         if($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null){

            $str = $select_shipment[0]->document;

            $doc = explode ("#####", $str);
         }

      return view('transporter.cancel_shipment_details',compact('details','doc'));  

    }
  

    public function show_report_shipment()
      {
          $this->timezone = Auth::guard('transporter')->user()->timezone;

          $user_id = Auth::guard('transporter')->user()->id; 

            $report_list = array();                
          
            $select_shipment = DB::select('SELECT shipment.*,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.user_id
             WHERE shipment.status = "7" AND shipment.transporter_id = '.$user_id.' order by shipment.created_at desc');
              
              if($select_shipment != null){

                foreach ($select_shipment as $key => $value) {
                  
                  $data1 = array();

                  $data1['shipment_id'] = $value->id;
                  $data1['ship_id'] = $value->unique_id;
                  $data1['shipper_id'] = $value->user_id;
                  $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                  $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                  $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
                  $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                  $data1['drop'] = is_null($value->drop)?'':$value->drop;
                  $data1['amount'] = is_null($value->amount)?'':$value->amount;
                  $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);
                  
                  array_push($report_list,$data1);
                }
              }

            return view('transporter.report_shipment',compact('report_list'));  
      }

      public function show_report_shipment_detail($id)
      {   
          $this->timezone = Auth::guard('transporter')->user()->timezone;

          $user_id = Auth::guard('transporter')->user()->id; 
          
          $details = array();                
          
            $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.mobile_no as shipper_mobile,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,truck.truck_img, truck.truck_name, info.sender_first_name, info.sender_last_name, info.sender_mobile, info.receiver_first_name, info.receiver_last_name, info.receiver_mobile
                FROM shipment
                left join shipment_info as info on info.shipment_id=shipment.id
                left join users on users.id=shipment.user_id
                left join users as driver on driver.id=shipment.driver_id
                left join truck on truck.id=shipment.vehicle_id
               WHERE shipment.id = '.$id.' ');
              
            if($select_shipment != null){

                foreach ($select_shipment as $key => $value) {
                  
                    $data1 = array();

                    $total_amount = $value->amount;

                    if($value->discount_amount != '0'){
                      $total_amount = $total_amount - $value->discount_amount;
                    }

                    $goods = array();

                     if($value->goods_type != '' && $value->goods_type != null){

                        $goods = explode (",", $value->goods_type);
                     }

                     $goods_type_name = '';

                    foreach($goods as $key => $goods_value){
                        
                         if($key != '0'){
                            $goods_type_name .= ', ';
                         } 

                          $goods = Goods_type::find($goods_value);
                        
                          if($goods != null){
                              $goods_type_name .= $goods->goods_type_name;
                          }
                    }

                    $data1['truck'] = [];
                    
                    $data1['shipment_id'] = $value->id;
                    $data1['ship_id'] = $value->unique_id;
                    $data1['shipper_id'] = $value->user_id;
                    $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                    $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                    $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
                    $data1['shipper_mobile'] = is_null($value->shipper_mobile)?'':$value->shipper_mobile;
                    $data1['driver_first_name'] = is_null($value->driver_first_name)?'':$value->driver_first_name;
                    $data1['driver_last_name'] = is_null($value->driver_last_name)?'':$value->driver_last_name;
                    $data1['driver_profile_pic'] = is_null($value->driver_profile_pic)?'':$value->driver_profile_pic;
                    $data1['driver_mobile'] = is_null($value->driver_mobile)?'':$value->driver_mobile;
                    $data1['sender_first_name'] = is_null($value->sender_first_name)?'':$value->sender_first_name;
                    $data1['sender_last_name'] = is_null($value->sender_last_name)?'':$value->sender_last_name;
                    $data1['sender_mobile'] = is_null($value->sender_mobile)?'':$value->sender_mobile;
              
                    $data1['receiver_first_name'] = is_null($value->receiver_first_name)?'':$value->receiver_first_name;
                    $data1['receiver_last_name'] = is_null($value->receiver_last_name)?'':$value->receiver_last_name;
                    $data1['receiver_mobile'] = is_null($value->receiver_mobile)?'':$value->receiver_mobile;
                    $data1['quotation_type'] = $value->quotation_type;
                    $data1['quotation_amount'] = is_null($value->quotation_amount)?0:$value->quotation_amount;
                    $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                    $data1['pickup_date'] = date('jS F Y H:i',strtotime($value->pickup_date));
                    $data1['drop'] = is_null($value->drop)?'':$value->drop;
                    $data1['service_type'] = '';
                    $data1['goods_type_name'] = $goods_type_name;
                    $data1['weight'] = is_null($value->weight)?0:$value->weight;
                    $data1['weight_type'] = is_null($value->weight_type)?0:$value->weight_type;
                    $data1['no_of_vehicle'] = is_null($value->total_vehicle)?0:$value->total_vehicle;
                    $data1['info'] = is_null($value->info)?'':$value->info;
                    $data1['amount'] = is_null($value->amount)?'':$value->amount;
                    $data1['base_fare'] = is_null($value->amount)?'0':$value->amount;
                    $data1['total_amount'] = $total_amount;
                    $data1['tax_per'] = is_null($value->tax_per)?'0':$value->tax_per;
                    $data1['tax_amount'] = is_null($value->tax_amount)?'0':$value->tax_amount;
                    $data1['discount_per'] = is_null($value->discount_per)?'0':$value->discount_per;
                    $data1['discount_amount'] = is_null($value->discount_amount)?'0':$value->discount_amount;

                    $get_kmiou_charges = Payment_info::where('shipment_id',$value->id)->first();

                    if($get_kmiou_charges != null){

                        $data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent)?'0 %':$get_kmiou_charges->percent.'(%)';
                        $data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion)?'0 DA':$get_kmiou_charges->admin_portion.' DA';

                        $base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
                        $data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0')?'':$base_fare;

                      }else{

                        $data1['kmiou_charges_per'] = '0 %';
                        $data1['kmiou_charges_amount'] = '0 DA';
                      }


                    $data1['document'] = is_null($value->document)?'':$value->document;
                    $data1['status'] = is_null($value->status)?'':$value->status;
                    $data1['truck_name'] = is_null($value->truck_name)?'': $value->truck_name;
                    $data1['truck_img'] = is_null($value->truck_img)?'': $value->truck_img;
                    $data1['report_emergency'] = is_null($value->report_emergency)?'0':$value->report_emergency;
                    $data1['report_comment'] = is_null($value->report_comment)?'':$value->report_comment;
                    $data1['payment_type'] = is_null($value->payment_type)?0:$value->payment_type;
                    $data1['payment_status'] = is_null($value->payment_status)?'':$value->payment_status;
                    $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);
                      
                    $truck_arr = array();

                      if($value->total_vehicle > '1'){

                        $get_total_truck_of_shipment = DB::select(' select * from shipment where unique_id = '.$value->unique_id.' AND shipment.status = "7" AND shipment.transporter_id = '.$user_id.' ');
                      
                        foreach ($get_total_truck_of_shipment as $key => $value) {
                          
                          //get truck number
                          $get_truck_number = Shipment_info::where('shipment_id',$value->id)->first();

                          $data2 = array();
                          $data2['title'] = 'Truck No : '.$get_truck_number->no_of_vehicle;
                          $data2['shipment_id'] = $value->id;
          
                          array_push($truck_arr,$data2);
                      
                        }
                    }
                    
                    $data1['truck'] = $truck_arr;

                    array_push($details,$data1);
                }
            
            }else{

              return redirect()->route('transporterShowReportShipment');
            }

            $doc = array();

           if($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null){

              $str = $select_shipment[0]->document;

              $doc = explode ("#####", $str);
           }

           $comment = array();

           if($select_shipment && $select_shipment[0]->report_comment != '' && $select_shipment[0]->report_comment != null){

              $str = $select_shipment[0]->report_comment;

              $comment = explode ("#####", $str);
           }

        return view('transporter.report_shipment_details',compact('details','doc','comment'));  

      }

    public function track_shipment(Request $request){

    try{  
          $this->timezone = Auth::guard('transporter')->user()->timezone;

          $user_id = Auth::guard('transporter')->user()->id; 

            $shipment = Shipment::find($request->shipment_id);

            $response = array();

            if($shipment != null && $shipment->status != "3" && $shipment->status != "7"){

                $track  = Track_shipment::where('shipment_id',$request->shipment_id)->get();

                $count = 1;

                if($track != null && $track != '[]'){

                  foreach ($track as $key => $value) {
                    
                    $data1['step'] = $count;
                    $data1['date'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i', $this->timezone);
                    $data1['status'] = '1';
              
                    array_push($response,$data1);

                      $count++;
                  }

                }

                if($count < 8){
                 
                   for ($i=$count; $i < 8 ; $i++) { 
                      
                      $data1['step'] = $i;
                      $data1['date'] = '';
                      $data1['status'] = '0';
                        
                      array_push($response,$data1);
                   }

                }


                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]));
                return json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]);

            }
            else
            {
                if($shipment == null){
                
                  $msg=trans('Shipment not Found');          
                
                }else if($shipment->status == "3"){

                    $msg=trans('This Shipment Cancelled By Driver');          


                }else if($shipment->status == "7"){
  
                      $msg=trans('Your Shipment Driver Reported Emergency');          
                }
                           
                session()->flash('alert-warning', $msg);
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
            }
            
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage() ]);
        }
    }


    public function assign_driver(Request $request){
    try{

        $this->timezone = Auth::guard('transporter')->user()->timezone;

        $user_id = Auth::guard('transporter')->user()->id; 

        $shipment = Shipment::find($request->shipment_id);   
        if($shipment != null && ($shipment->status == '0' || $shipment->status == '1' || $shipment->status == '4' ) ){
             
              if($shipment->driver_id == '0'){

                $shipment->driver_id = $request->driver_id;
                $shipment->save();            
                
                $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

                // send notification
                Helper::send_push_notification($user_id,$shipment->driver_id,'Assign Driver','assign you shipment order Truck No. '.$get_truck_no->no_of_vehicle.' Order No. #'.$shipment->unique_id,'4',$shipment->id);
              
              }else{

                $shipment->driver_id = $request->driver_id;
                $shipment->status = '1';
                $shipment->save();    

                DB::table('notification')->where('from_user_id',$user_id)->where('noti_type',"4")->where('ref_id',$request->shipment_id)->delete();

                $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

                // send notification
                Helper::send_push_notification($user_id,$shipment->driver_id,'Assign Driver','assign you shipment order Truck No. '.$get_truck_no->no_of_vehicle.' Order No. #'.$shipment->unique_id,'4',$shipment->id);   
              }
            
              Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Driver Assigned Successfully','result' => [] ]));
              return json_encode(['success' => 1, 'msg' => 'Driver Assigned Successfully','result' => [] ]);
      
          }else{

            $msg = 'Shipment Not Found';
            Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
              return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
          }

        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage() ]);
        }
    }

// end controller function	
}