<?php
namespace App\Http\Controllers;
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use JWTFactory;
use JWTAuth;
use Hash;
use DB;
use Mail;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Helper;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\User;
use App\Card;
use App\Coupon;
use App\Review;
use App\Shipment;
use App\Goods_type;
use App\Shipment_bid;
use App\Payment_info;
use App\Shipment_info;
use App\Track_shipment;
use App\Truck;
use App\Transporter_truck;
use App\Driver;
use Carbon\Carbon;

class ApiShipmentController extends Controller
{
	

    public function book_new_shipment(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));

	        $user = User::find($request->user_id);
	        $sender_info = json_decode($request->sender_info);

	        $unique_id = rand(111111,999999);

	        for ($i=0; $i < $request->no_of_vehicle; $i++) { 	        	

		        $discount_per = '0';

		        $shipment = new Shipment;

		        $shipment->user_id = $request->user_id;
		        $shipment->vehicle_id = $request->vehicle_id;
		        $shipment->card_id = $request->card_id;
		        $shipment->promo_id = isset($request->promo_id)?$request->promo_id:'0';

		        if(isset($request->promo_id) && $request->promo_id != '0' && $request->promo_id != ''){
		        	
		        	$get_coupon = Coupon::find($request->promo_id);
		        	$discount_per = $get_coupon->discount;
		        }

		        $shipment->pickup = $request->pickup;
		        $shipment->drop = $request->drop;
		        $shipment->amount = is_null($request->amount)?'0':$request->amount;
		        $shipment->basic_amount = is_null($request->amount)?'0':$request->amount;
		        $shipment->payment_type = ($user->payment_type == '1')?'2':$request->payment_type;
		        /*$shipment->tax_per = $request->tax_per;
		        $shipment->tax_amount = $request->tax_amount;
		        $shipment->discount_amount = $request->discount_amount;*/
		        $shipment->discount_per = $discount_per;
		        $shipment->receipt = $request->receipt;
		        $shipment->status = '0';
		        $shipment->payment_status = '0';
		        
		        // update discount in shipment
				if($shipment->discount_per != '0' && $request->quotation_type != '0'){
					$shipment->discount_amount = ( $shipment->amount * $shipment->discount_per) / 100;
				}

		        $shipment->save();
	        	
	        	if($i == '0'){

	        		$update_shipment = Shipment::find($shipment->id);

	        		$update_shipment->unique_id = $shipment->id;
	        		
	        		$unique_id = $shipment->id;
	
			        $update_shipment->save();

	        	}else{

	        		$update_shipment = Shipment::find($shipment->id);

	        		$update_shipment->unique_id = $unique_id;
	
			        $update_shipment->save();
	        	}

		        $shipment_info = new Shipment_info;

		        $shipment_info->shipment_id = $shipment->id;
		        $shipment_info->pickup_date = $request->pickup_date;
		        $shipment_info->pickup_lat = $request->pickup_lat;
		        $shipment_info->pickup_long = $request->pickup_long;
		        $shipment_info->drop_lat = $request->drop_lat;
		        $shipment_info->drop_long = $request->drop_long;
		        //$shipment_info->no_of_vehicle = $request->no_of_vehicle;
		        $shipment_info->no_of_vehicle = ($i+1);
		        $shipment_info->total_vehicle = $request->no_of_vehicle;
		        $shipment_info->sender_first_name = $sender_info[0]->sender_first_name;
		        $shipment_info->sender_last_name = $sender_info[0]->sender_last_name;
		        $shipment_info->sender_email = ""; //$sender_info[0]->sender_email;
		        $shipment_info->sender_mobile = $sender_info[0]->sender_mobile;
		        $shipment_info->receiver_first_name = $sender_info[0]->receiver_first_name;
		        $shipment_info->receiver_last_name = $sender_info[0]->receiver_last_name;
		        $shipment_info->receiver_email = ""; //$sender_info[0]->receiver_email;
		        $shipment_info->receiver_mobile = $sender_info[0]->receiver_mobile;
		        $shipment_info->quotation_type = $request->quotation_type;
		        $shipment_info->quotation_amount = is_null($request->quotation_amount)?'0':$request->quotation_amount;
		        $shipment_info->goods_type = $request->goods_type;
		        $shipment_info->weight_type = $request->weight_type;
		        $shipment_info->weight = $request->weight;
		        $shipment_info->document = $request->document;
		        $shipment_info->info = $request->info;
		       
		        $shipment_info->save();

		        $track_shipment = new Track_shipment;
		        $track_shipment->shipment_id = $shipment->id;
		        $track_shipment->status = '0';
		        $track_shipment->payment_status = '0';
		        $track_shipment->save();
		          
		        $order_type = '';
	       
		        if($request->quotation_type != '2'){

		            if($request->quotation_type == '0'){
		              $order_type = trans('word.Bid Shipment Truck No.').' '.($i+1).' '.trans('word.Order');
		            }else if ($request->quotation_type == '1'){
		              $order_type = trans('word.Fixed Shipment Truck No.').' '.($i+1).' '.trans('word.Order');
		            }

		            $check_transporter = User::where('user_type',"3")->where('approve',"1")->where('is_verify','1')->where('status','1')->orderBy('created_at','desc')->get(); 
		          
                    foreach ($check_transporter as $key => $value) {

						$getAccossiatDriverTruck = DB::select('select COUNT(1) AS truckcount from transporter_truck where truck_id = '.$request->vehicle_id.' AND status = "1" AND user_id in (SELECT id FROM users WHERE ref_id = '.$value->id.' ) ');

                    	$check_truck = Transporter_truck::where('truck_id',$request->vehicle_id)->where('user_id',$value->id)->where('status',"1")->orderBy('created_at','desc')->first();

						//if( (!empty($getAccossiatDriverTruck) && $getAccossiatDriverTruck[0]->truckcount > 0) || $check_truck != null) { // Comment By Mehul
						if( !empty($getAccossiatDriverTruck) && $getAccossiatDriverTruck[0]->truckcount > 0 ) { //Added By Mehul

				          // send notification

  						  app()->setLocale('en');	
                  		  $msg_en = trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$unique_id;
  						  
  						  app()->setLocale('fr');	
                  		  $msg_fr = trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$unique_id;
  						  
  						  app()->setLocale('ar');	
				          $msg_ar = trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$unique_id;

				          Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment',$msg_en,'1',$shipment->id,$msg_fr,$msg_ar);

				          // read request count
				          DB::table('shipment_request_count')->insert(['user_id' => $value->id,'shipment_id' => $shipment->id,'is_read' => '0']);
				        }
		            }

		            $check_driver = User::where('user_type',"4")->where('approve',"1")->where('is_verify','1')->where('status','1')->where('ref_id','0')->orderBy('created_at','desc')->get(); 

				    foreach ($check_driver as $key => $value) {
		          		
		          		$check_truck = Transporter_truck::where('truck_id',$request->vehicle_id)->where('user_id',$value->id)->where('status',"1")->orderBy('created_at','desc')->first();

                  		if($check_truck != null){

                		  app()->setLocale('en');	
                  		  $msg_en = trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$unique_id;
  						  
  						  app()->setLocale('fr');	
                  		  $msg_fr = trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$unique_id;
  						  
  						  app()->setLocale('ar');	
				          $msg_ar = trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$unique_id;

				          // send notification
				          Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment',$msg_en,'1',$shipment->id,$msg_fr,$msg_ar);

				          // read request count
				          DB::table('shipment_request_count')->insert(['user_id' => $value->id,'shipment_id' => $shipment->id,'is_read' => '0']);
				        }
		            }

		        //end if condition
	       		}
	       		
		   	//end for loop
	        }

	        	$user_name = is_null($user->first_name)?'':$user->first_name.' '.(is_null($user->last_name)?'':$user->last_name);

                // new user mail to admin
                $user_detail2 =array();
                $user_detail2['email'] = $user->email;
                $user_detail2['shipment_id'] = $unique_id;
                $user_detail2['user_name'] = $user_name;
                $user_detail2['date'] = date("Y-m-d H:i", strtotime('+60 minutes'));


                /*Mail::send('emails.new_instant_quote', ['user' => (object)$user_detail2], function($message) use ($user) {
                    $message->from(env('MAIL_USERNAME'), 'KMIOU');
                    $message->to(env('MAIL_ADMIN'));
                    $message->subject('KMIOU NEW INSTANT QUOTE ORDER');
                });*/

				app()->setLocale(strtolower($request->language));
	        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Booking Added Successfully','result' => [] ]));
	        return json_encode(['success' => 1, 'msg' => trans('word.Booking Added Successfully'),'result' => [] ]);
	    
		} catch(Exception $ex) {
            
            app()->setLocale(strtolower($request->language));
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }

	
	public function shipment_request_list(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));

    		$check_user = User::find($request->user_id);	 

	       	if($check_user != null){					
				
				$response = array();

				$query = '';

				// update request count
				DB::table('shipment_request_count')->where('user_id',$request->user_id)->where('is_read',"!=","2")->update(['is_read' => '1']);

				if($check_user->user_type == '3'){ //Transporter
					
					// filter type 0 // schedule for delivery
			          if($request->filter_type == "0"){
			             
			                $query = ' shipment.status = "0" AND shipment.transporter_id = "0" AND shipment.driver_id = "0" AND shipment.bid_status = "0" AND info.quotation_type != "2" ';
			            

			          // filter type 1 // bidded
			          }else if($request->filter_type == "1"){
			             
			                $query = ' shipment.status = "0" AND info.quotation_type = "0" AND bid.user_id = '.$request->user_id.' AND bid.status = "0" AND shipment.bid_status = "0" ';
			            

			          // filter type 2 // pending award acceptence
			          }else if($request->filter_type == "2"){
			             
			                $query = ' shipment.status = "0" AND info.quotation_type = "0" AND bid.status = "1" AND shipment.transporter_id = '.$request->user_id.' AND shipment.bid_status = "1" ';

			            
			          // filter type 3 // pending driver assignment
			          }else if($request->filter_type == '3'){

			                  $query = ' shipment.status = "1" AND shipment.transporter_id = '.$request->user_id.' AND shipment.driver_id = "0" ';
			              
			          // filter type 4 // All
			          }else{

			              //$query = ' ( (shipment.status = "0" AND info.quotation_type != "2" ) OR (shipment.status = "1" AND shipment.driver_id = "0" AND shipment.transporter_id = '.$request->user_id.') ) '; //OLD

			              $query = ' (shipment.status = "0" AND info.quotation_type != "2") ';
			          }


				}else if($check_user->user_type == '4'){ //Driver
					
					if($check_user->ref_id != '0'){
						
						$query .= ' (shipment.driver_id = '.$request->user_id.' ) ';

					}else{

						// filter type 0 // schedule for delivery
			            if($request->filter_type == "0"){
			             
			                $query .= ' shipment.status = "0" AND shipment.transporter_id = "0" AND shipment.driver_id = "0" AND shipment.bid_status = "0" AND info.quotation_type != "2" ';
			            

			            // filter type 1 // bidded
			            }else if($request->filter_type == "1"){
			             
			                $query .= ' shipment.status = "0" AND info.quotation_type = "0" AND bid.user_id = '.$request->user_id.' AND bid.status = "0" AND shipment.bid_status = "0" ';
			            

			            // filter type 2 // pending award acceptence
			            }else if($request->filter_type == "2"){
			             
			                $query .= ' shipment.status = "0" AND info.quotation_type = "0" AND bid.status = "1" AND shipment.driver_id = '.$request->user_id.' AND shipment.bid_status = "1" ';

			            }else{

				            //$query .= ' ( (shipment.status = "0" AND info.quotation_type != "2") OR shipment.driver_id = '.$request->user_id.' ) '; //OLD
				            $query .= ' (shipment.status = "0" AND info.quotation_type != "2") ';
                        }  
					}
				}

				// for newly registered user
				if($check_user->email_verified_at != null){
					
					$query .= ($query != '')?' AND (shipment.created_at >= "'.$check_user->email_verified_at.'" ) ':' (shipment.created_at >= "'.$check_user->email_verified_at.'" ) ';

				}
				// hide rejected shipment
				$get_rejected_shipment = DB::select('select GROUP_CONCAT( shipment_id ) as shipment_ids from shipment_request_count where user_id = '.$request->user_id.' AND is_read="2" ');


				if($get_rejected_shipment[0]->shipment_ids != null){
					
					$query .= ' AND shipment.id not in ('.$get_rejected_shipment[0]->shipment_ids.') ';	
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
				/*else {
					$query .= " AND DATE(info.pickup_date) >= '".Helper::getCurrentDateWithTimezone('Y-m-d', $request->timezone)."'";   
				}*/

				if(!empty($request->to_date))
				{
					$query .= " AND DATE(info.pickup_date) <= '".$request->to_date."'";     
				}

				$select_shipment = DB::select('SELECT shipment.*,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,info.quotation_type, info.pickup_date
		       			FROM shipment
		       			left join shipment_info as info on info.shipment_id=shipment.id
		       			left join shipment_bid as bid on bid.shipment_id = shipment.id
		       			left join users on users.id=shipment.user_id
		       		 	WHERE '.$query.' group by shipment.id order by info.pickup_date');//desc

	       		if($select_shipment != null){

					foreach ($select_shipment as $key => $value) {

						$select_request_count_shipment = DB::select('select * from shipment_request_count where user_id = '.$request->user_id.' AND shipment_id = '.$value->id.' limit 1 ');
		                
		                if(($select_request_count_shipment == [] || $select_request_count_shipment == null) && $value->quotation_type != '2' ){
		                    
		                    continue;
		                }

						$select_bidder = Shipment_bid::where('shipment_id',$value->id)->where('user_id',$request->user_id)->first();
						
						
						if($value->bid_status == '0' || ($value->bid_status == '1' && (isset($select_bidder->user_id) == $request->user_id) && $select_bidder->status == '1' ) ){

							$status_color = '';
			              	$status_string = '';
			              	
			              	if($value->status == '0'){
			              		
		              			$status_color = '#FFC70D';
			              		//$status_string = trans('word.Schedule For Delivery');//OLD
			              		$status_string = trans('word.Waiting For Acceptance');//OLD

			              		if($value->bid_status == '1'){

		              				$status_color = '#00874A';
				              		$status_string = trans('word.Pending Awards Acceptance');

			              		}else if($value->bid_status == '0' && $value->quotation_type == '0'){
			              		
				              		if($select_bidder != null){

				              			$status_color = '#00874A';
					              		$status_string = trans('word.Bidded');
	
						              	if($request->filter_type == "0"){
				              				continue;
					              		}
				              		}
				              	}
		              		
			              	}else{
			              		
			              		$status_color = '#00874A';
				              	$status_string = trans('word.Pending Driver Assignment');
			              	}


			        		$data1 = array();
							
		    				$data1['shipment_id'] = $value->id;
		    				$data1['ship_id'] = $value->unique_id;
		    				$data1['shipper_id'] = $value->user_id;
		    				$data1['shipper_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name.' '.(is_null($value->shipper_last_name)?'':$value->shipper_last_name);
		    				$data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
							// $data1['pickup'] = Helper::convertTimestampWithTimezone($value->pickup,'jS F Y H:i A', $request->timezone,$request->language);
			        		$data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
			        		$data1['drop'] = is_null($value->drop)?'':$value->drop;
			        		$data1['quotation_type'] = is_null($value->quotation_type)?'':$value->quotation_type;
			        		$data1['status_color'] = $status_color;
			        		$data1['status_string'] = $status_string;
			        		$data1['status'] = $value->status;
			        		$data1['desc'] = '';
			        		$data1['created_at'] = Helper::convertTimestampWithTimezone($value->pickup_date,'jS F Y H:i', $request->timezone,$request->language);
			        		// $data1['created_at'] = date('jS F Y H:i', strtotime($value->pickup_date));							
			        		

							$total_amount = $value->amount;
							if($value->discount_amount != '0') {
								$total_amount = $total_amount - $value->discount_amount;
							}
							$data1['amount'] = $total_amount.' DA';
							
							if($value->quotation_type == "0" && $value->status == '0') { //Auction
								$data1['amount'] = trans('word.Give Your Price');
							}


		        			array_push($response,$data1);
						}
					}
	       		}

	        	Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success' ,'result' => $response ]));
	            return json_encode(['success' => 1, 'msg' => 'Success' ,'result' => $response ]);

		    }
	        else
	        {
	        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'User Not Found','result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => 'User Not Found','result' => [] ]);
	            
	        }
	    
		} catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getLine(), 'result' => [] ]);
    	}
    }   

    public function shipment_list(Request $request)
    {

    	try{
            app()->setLocale(strtolower($request->language));

    		$check_user = User::find($request->user_id);	 

	       	if($check_user != null){					
				
				$response = array();	

				$query = '';

				/*
				type 1 = active, 2 = past, 3 = Upcoming

				filter type 
				0 = all
				1 = accepted
				2 = on the way
				3 = cancelled
				4 = arrived at pickup location
				5 = start shipment
				6 = delivered
				7 = reported
				8 = arrived at drop of location
				9 = schedule for delivery
				10 = awaiting bid
				11 = bid received
				12 = bidder awarded
				13 = fixed shipment
				14 = instant quote
				15 = auction shipment
				*/

				//STATUS :: 0=pending, 1=confirm, 2=on_the_way, 3=cancelled, 4=arrived, 5=strat_shipment, 6=reached, 7=report, 8=arrived at drop of loctions, 9=on_the_way_to_pickup
				
				if($request->type == '3'){				
					$query = ' shipment.status IN ("1") ';
				}
				else if($request->type == '2'){
				
					$query = ' shipment.status IN ("3","6","7") ';

				}else{

					$query = ' shipment.status IN ("2","4","5","8","9") ';
					/* OLD Comment By Mehul
					if($check_user->user_type == '3'){//Transporter
						$query = ' shipment.status IN ("2","4","5","8","9") ';
					}					
					else if($check_user->user_type == '2'){ //Shipper
						$query = ' shipment.status IN ("2","4","5","8","9") '; //"0","1",
					}
					else{ //Driver
						$associatedDriver =  Driver::where('driver_id', '=', $request->user_id)->first();
            			if(!empty($associatedDriver)) { //Associated Driver
							$query = ' shipment.status IN ("2","4","5","8","9") ';
						}
						else {
							$query = ' shipment.status IN ("2","4","5","8","9") '; //"1",
						}
					}
					*/
				}

				if(isset($request->filter_type) && $request->filter_type != "0" && $request->filter_type < "9" ){

					if($request->filter_type == "7") {
						$query = ' shipment.report_emergency != "-1" ';
					}
					else {
						$query = ' shipment.status = "'.$request->filter_type.'" ';
					}

				}else if(isset($request->filter_type) && $request->filter_type == "9"){

					$query = ' shipment.status = "0" AND shipment.transporter_id = "0" AND shipment.driver_id = "0" AND shipment.bid_status = "0" AND info.quotation_type = "1" ';
				
				}else if(isset($request->filter_type) && $request->filter_type == "10"){

					$query = ' shipment.status = "0" AND shipment.transporter_id = "0" AND shipment.driver_id = "0" AND shipment.bid_status = "0" AND info.quotation_type = "0" ';
				
				}else if(isset($request->filter_type) && $request->filter_type == "11"){

					$query = ' shipment.status = "0" AND shipment.transporter_id = "0" AND shipment.driver_id = "0" AND shipment.bid_status = "0" AND info.quotation_type = "0" ';
				
				}else if(isset($request->filter_type) && $request->filter_type == "12"){

					$query = ' shipment.status = "0" AND shipment.bid_status = "1" AND info.quotation_type = "0" ';
				}

				//fixed
				if(isset($request->filter_type) && $request->filter_type == "13")
				{
				 	$query = ' info.quotation_type ="1" AND shipment.status = "0" ';
				}

				//instant quote
				if(isset($request->filter_type) && $request->filter_type == "14")
				{
				 	$query = ' info.quotation_type ="2" AND shipment.status = "0" ';
				}


				//auction
				if(isset($request->filter_type) && $request->filter_type == "15")
				{
				 	$query = ' info.quotation_type = "0" AND shipment.status = "0" ';
				}
				

				if($check_user->user_type == '2'){ //Shipper
				
					$query .= ' AND shipment.user_id = '.$request->user_id.' ';

				}else if($check_user->user_type == '3'){ //Transporter
					if(isset($request->filter_type) && $request->filter_type == "3") {
						$query .= ' AND shipment.transporter_id = '.$request->user_id;
					}
					else {
						$query .= ' AND shipment.transporter_id = '.$request->user_id.' AND driver_id != "0" ';
					}

				}else if($check_user->user_type == '4'){ //Driver

					$query .= ' AND shipment.driver_id = '.$request->user_id.' ';
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
	       		 	WHERE '.$query.' order by info.pickup_date desc');

	       		if($select_shipment != null){
					   
					foreach ($select_shipment as $key => $value) {

						$status_color = '';
		              	$status_string = '';
		              	
		              	if($value->status == '0'){

		              		if($value->quotation_type == '0'){
		              				
		              			$select_bidder = Shipment_bid::where('shipment_id',$value->id)->first();

		              			if($select_bidder == null){

		              				$status_color = '#FFC70D';
					              	$status_string = trans('word.Awaiting Bid');

		              			}else{

			              			if($value->bid_status == '1'){

				              			$status_color = '#FFC70D';
					              		$status_string = trans('word.Bidder Awarded');

				              		}else if($value->bid_status == '0'){

				              			$status_color = '#FFC70D';
					              		$status_string = trans('word.Bid Received');
				              		}
		              			}

		              			if(isset($request->filter_type) && ($request->filter_type == "10" && $status_string != trans('word.Awaiting Bid')) || ($request->filter_type == "11" && $status_string != trans('word.Bid Received')) ){
		              			
		              				continue;
		              			
		              			}


		              		}else{

			              		$status_color = '#FFC70D';
			              		//$status_string = trans('word.Schedule For Delivery'); //OLD
			              		$status_string = trans('word.Waiting For Acceptance');
								  
		              		}
		              	
		              	}else if($value->report_emergency != '-1' && $value->status_when_report == $value->status){
		              	
							$status_color = '#FFFF00';// '#EF5163';
							$status_string = trans('word.Reported Emergency');
						
						}else if($value->status == '1'){
		              	
		              		$status_color = '#00874A';
		              		$status_string = trans('word.Accepted');
		              	
		              	}else if($value->status == '2'){
		              	
		              		$status_color = '#0063C6';
		              		$status_string = trans('word.On The Way');
		              	
		              	}else if($value->status == '3'){
		              	
		              		$status_color = '#FF0000';// '#EF5163';
		              		$status_string = trans('word.Cancelled');
		              	
		              	}else if($value->status == '4'){
		              		
		              		$status_color = '#00874A';
		              		$status_string = trans('word.Arrived at Pickup Location');

		              	}else if($value->status == '5'){
		              		
		              		$status_color = '#FFC70D';
		              		$status_string = trans('word.Start Shipment');

		              	}else if($value->status == '6'){
		              	
		              		$status_color = '#12D612';
		              		$status_string = trans('word.Delivered');
		              	
		              	}else if($value->status == '7'){
		              	
		              		$status_color = '#FFFF00';// '#EF5163';
		              		$status_string = trans('word.Reported Emergency');
		              	
		              	}else if($value->status == '8'){
		              	
		              		$status_color = '#00874A';
		              		$status_string = trans('word.Arrived at Drop off Location');

		              	}else if($value->status == '9'){
							
							$status_color = '#00874A';
							$status_string = trans('word.On The Way To PickUp');
						}
						
						$is_past = '0';

						if(strpos("367",$value->status) !== false)
						{
							$is_past = '1';
						}


		        		$data1 = array();

	    				$data1['shipment_id'] = $value->id;
	    				$data1['ship_id'] = $value->unique_id;
	    				$data1['shipper_id'] = $value->user_id;
	    				$data1['shipper_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name.' '.(is_null($value->shipper_last_name)?'':$value->shipper_last_name);
	    				$data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
						// $data1['pickup'] = Helper::convertTimestampWithTimezone($value->pickup,'jS F Y H:i A', $request->timezone,$request->language);
		        		$data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
		        		$data1['drop'] = is_null($value->drop)?'':$value->drop;
		        		$data1['status'] = is_null($value->status)?'':$value->status;
		        		$data1['quotation_type'] = is_null($value->quotation_type)?'':$value->quotation_type;
		        		$data1['status_color'] = $status_color;
		        		$data1['status_string'] = $status_string;
		        		$data1['desc'] = '';
		        		$data1['created_at'] = Helper::convertTimestampWithTimezone($value->pickup_date,'jS F Y H:i A', $request->timezone,$request->language);
						// Carbon::now();

						// Carbon::setLocale('fr');
		        		// $data1['created_at'] = translatedFormatdate('jS F Y H:i', strtotime($value->pickup_date));

						
						// echo $base_weight_category->created_at->translatedFormat('l jS F Y');


		        		$data1['is_past'] = $is_past;	

						$total_amount = $value->amount;
						if($value->discount_amount != '0') {
							$total_amount = $total_amount - $value->discount_amount;
						}
						$data1['amount'] = $total_amount.' DA';

	        			array_push($response,$data1);
					}	
	       		}

	        	Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success' ,'result' => $response ]));
	            return json_encode(['success' => 1, 'msg' => 'Success' ,'result' => $response ]);

		    }
	        else
	        {
	        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'User Not Found','result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => trans('word.User Not Found'),'result' => [] ]);
	            
	        }
	    
		} catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }


	public function shipment_details(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));

    		$check_user = User::find($request->user_id);

	       	if($check_user != null){					
				
				$response = array();			    			
				
	       		$select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.info,info.no_of_vehicle,info.total_vehicle,info.goods_type,info.pickup_date,info.person_name,info.id_proof_image,info.signature_image,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.mobile_no as shipper_mobile,users.current_lat as shipper_lat,users.current_lng as shipper_lng,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,driver.current_lat as driver_lat,driver.current_lng as driver_lng,transporter.profile_pic as transporter_profile_pic ,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,transporter.mobile_no as transporter_mobile,transporter.current_lat as transporter_lat,transporter.current_lng as transporter_lng,info.pickup_lat,info.pickup_long,info.drop_lat,info.drop_long, truck.truck_img, truck.truck_name, truck.truck_name_fr, truck.truck_name_ar, info.sender_first_name, info.sender_last_name, info.sender_mobile, info.receiver_first_name, info.receiver_last_name, info.receiver_mobile
	       			FROM shipment
	       			left join shipment_info as info on info.shipment_id=shipment.id
	       			left join users on users.id=shipment.user_id
	       			left join users as driver on driver.id=shipment.driver_id
	       			left join users as transporter on transporter.id=shipment.transporter_id
	       			left join truck on truck.id=shipment.vehicle_id
	       		 WHERE shipment.id IN ('.$request->shipment_id.')  ');
	       		
	       	if($select_shipment != null){

              	foreach ($select_shipment as $key => $value) {

              	$status_color = '';
              	$status_string = '';
              	$goods_type = '';
              	$payment_type = '';
              	$rate = '0';
 				$review = '';
              	
              	$goods = array();

				 if($value->goods_type != '' && $value->goods_type != null){

				    $goods = explode (",", $value->goods_type);
				 }

				 if($value->payment_type == '0'){

				    $payment_type = trans("word.Cash");
				 
				 }else if($value->payment_type == '1'){

				    $payment_type = trans("word.Card");

				 }else{

				    $payment_type = trans("word.ACH");
				 }
                       
	                foreach($goods as $key => $goods_value){
	                
		                if($key != '0'){
		                	$goods_type .= ', ';
		                } 

		                $goods = Goods_type::find($goods_value);
		                if($goods != null){
		                	$goods_type .= $goods->goods_type_name;
		                }
		                
	                }

              	if($value->status == '0' || ($value->status == '1' && $value->driver_id == "0") ){

              		$status_color = '#FFC70D';
		            //$status_string = trans('word.Schedule For Delivery');//OLD
		            $status_string = trans('word.Waiting For Acceptance');					
              	
              		$select_bidder = Shipment_bid::where('shipment_id',$value->id)->where('user_id',$request->user_id)->first();
						
					if($check_user->user_type == "3" && $value->bid_status == '0' || ($value->bid_status == '1' && (isset($select_bidder->user_id) == $request->user_id) && $select_bidder->status == '1' ) ){

		              	if($value->status == '0'){

		              		if($value->bid_status == '1'){

	              				$status_color = '#00874A';
			              		$status_string = trans('word.Pending Awards Acceptance');

		              		}else if($value->bid_status == '0' && $value->quotation_type == '0'){
		              		
			              		if($select_bidder != null){

			              			$status_color = '#00874A';
				              		$status_string = trans('word.Bidded');
			              		}
			              	}
	              		
		              	}else{
		              		
		              		$status_color = '#00874A';
			              	$status_string = trans('word.Pending Driver Assignment');
		              	}
		            
		            }else if($check_user->user_type == "2" && $value->quotation_type == '0' && $value->status == '0'){
		              				
              			$select_bidder = Shipment_bid::where('shipment_id',$value->id)->first();

              			if($select_bidder == null){

              				$status_color = '#FFC70D';
			              	$status_string = trans('word.Awaiting Bid');

              			}else{

	              			if($value->bid_status == '1'){

		              			$status_color = '#FFC70D';
			              		$status_string = trans('word.Bidder Awarded');

		              		}else if($value->bid_status == '0'){

		              			$status_color = '#FFC70D';
			              		$status_string = trans('word.Bid Received');
		              		}
              			}
		            }else if($check_user->user_type == "2" && $value->status == '1' && $value->driver_id == "0"){
		            	$status_color = '#00874A';
              			$status_string = trans('word.Accepted');
		            }
              	
              	}else if($value->report_emergency != '-1' && $value->status_when_report == $value->status){
              	
					$status_color = '#FFFF00';// '#EF5163';
					$status_string = trans('word.Reported Emergency');
				
				}else if($value->status == '1'){
              	
              		$status_color = '#00874A';
              		$status_string = trans('word.Accepted');
              	
              	}else if($value->status == '2'){
              	
              		$status_color = '#0063C6';
              		$status_string = trans('word.On The Way');
              	
              	}else if($value->status == '3'){
              	
              		$status_color = '#FF0000';// '#EF5163';
              		$status_string = trans('word.Cancelled');
              	
              	}else if($value->status == '4'){
              		
              		$status_color = '#00874A';
              		$status_string = trans('word.Arrived');

              	}else if($value->status == '5'){
              		
              		$status_color = '#FFC70D';
              		$status_string = trans('word.Start Shipment');

              	}else if($value->status == '6'){
              	
              		$status_color = '#12D612';
              		$status_string = trans('word.Delivered');

              		$review = Review::where('ref_id',$request->shipment_id)->where('user_id',$request->user_id)->first();

              		if($review != null){

	              		$rate = $review->rate;
	 					$review = $review->review_text;
              		}
              	
              	}else if($value->status == '7'){
              	
              		$status_color = '#FFFF00';// '#EF5163';
              		$status_string = trans('word.Reported Emergency');
              	
              	}else if($value->status == '8'){
		              	
              		$status_color = '#00874A';
              		$status_string = trans('word.Arrived at Drop off Location');
              	}
				else if($value->status == '9'){
              		
					$status_color = '#00874A';
					$status_string = trans('word.On The Way To PickUp');
				}

              	$bid_amount = 0;
              	$is_bid_selected = '0';
              	
              	$total_amount = $select_shipment[0]->amount;

              	if($select_shipment[0]->discount_amount != '0'){
              		$total_amount = $total_amount - $select_shipment[0]->discount_amount;
              	}

	            if( ($select_shipment[0]->status == '0' || $select_shipment[0]->status == '1') && $select_shipment[0]->quotation_type == '0' && $check_user->user_type == '3'){

                  $check_bid = Shipment_bid::where('shipment_id',$request->shipment_id)->where('user_id',$check_user->id)->first();

                  if($check_bid != null){

                    if($check_bid->status == '1'){
                    
                      $bid_amount = $check_bid->bid_amount;
                      $is_bid_selected = '1';
                    
                    }else if($check_bid->status == '0'){

                      $bid_amount = $check_bid->bid_amount;
                    
                    }else if($check_bid->status == '2'){

                        Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipper Rejected Your Bid','result' => [] ]));
	            		return json_encode(['success' => 0, 'msg' => trans('word.Shipper Rejected Your Bid'),'result' => [] ]);
                    }
                  }

              	}else if (($select_shipment[0]->status == '0' || $select_shipment[0]->status == '1') && $select_shipment[0]->quotation_type == '0' && $check_user->user_type == '2'){

              		$is_bid_selected = $select_shipment[0]->bid_status;
              	
              	}else if ( ($select_shipment[0]->status == '0' || $select_shipment[0]->status == '1') && $select_shipment[0]->quotation_type == '0' && $check_user->user_type == '4' && $check_user->ref_id == '0'){

              		$check_bid = Shipment_bid::where('shipment_id',$request->shipment_id)->where('user_id',$check_user->id)->first();

	                if($check_bid != null){

	                    if($check_bid->status == '1'){
	                    
	                      $bid_amount = $check_bid->bid_amount;
	                      $is_bid_selected = '1';
	                    
	                    }else if($check_bid->status == '0'){

	                      $bid_amount = $check_bid->bid_amount;
	                    
	                    }else if($check_bid->status == '2'){

	                        Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipper Rejected Your Bid','result' => [] ]));
		            		return json_encode(['success' => 0, 'msg' => trans('word.Shipper Rejected Your Bid'),'result' => [] ]);
	                    }
	                }
          		}

          		if($select_shipment[0]->transporter_id == $request->user_id){
	              	 $is_bid_selected = '1';
	              }
						
	        		$data1 = array();

	        		$data1['truck'] = [];

    				$data1['shipment_id'] = $value->id;
    				$data1['ship_id'] = $value->unique_id;
    				$data1['shipper_id'] = $value->user_id;
    				$data1['driver_id'] = $value->driver_id;
    				$data1['transporter_id'] = $value->transporter_id;
    				$data1['shipper_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name.' '.(is_null($value->shipper_last_name)?'':$value->shipper_last_name);
    				$data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
    				$data1['shipper_mobile'] = is_null($value->shipper_mobile)?'':$value->shipper_mobile;
    				$data1['shipper_lat'] = is_null($value->shipper_lat)?0:$value->shipper_lat;
    				$data1['shipper_lng'] = is_null($value->shipper_lng)?0:$value->shipper_lng;
    				$data1['driver_name'] = is_null($value->driver_first_name)?'':$value->driver_first_name.' '.(is_null($value->driver_last_name)?'':$value->driver_last_name);
    				$data1['driver_profile_pic'] = is_null($value->driver_profile_pic)?'':$value->driver_profile_pic;
    				$data1['driver_mobile'] = is_null($value->driver_mobile)?'':$value->driver_mobile;
    				$data1['driver_lat'] = is_null($value->driver_lat)?0:$value->driver_lat;
    				$data1['driver_lng'] = is_null($value->driver_lng)?0:$value->driver_lng;
    				$data1['transporter_name'] = is_null($value->transporter_first_name)?'':$value->transporter_first_name.' '.(is_null($value->transporter_last_name)?'':$value->transporter_last_name);
                    $data1['transporter_profile_pic'] = is_null($value->transporter_profile_pic)?'':$value->transporter_profile_pic;
                    $data1['transporter_mobile'] = is_null($value->transporter_mobile)?'':$value->transporter_mobile;
                    $data1['transporter_lat'] = is_null($value->transporter_lat)?0:$value->transporter_lat;
    				$data1['transporter_lng'] = is_null($value->transporter_lng)?0:$value->transporter_lng;
	        		$data1['quotation_type'] = is_null($value->quotation_type)?0:$value->quotation_type;
	        		$data1['quotation_amount'] = is_null($value->quotation_amount)?0:$value->quotation_amount;
	        		$data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
	        		$data1['pickup_lat'] = is_null($value->pickup_lat)?'':$value->pickup_lat;
	        		$data1['pickup_lng'] = is_null($value->pickup_long)?'':$value->pickup_long;
	        		$data1['pickup_date'] = Helper::convertTimestampWithTimezone($value->pickup_date,'jS F Y H:i', $request->timezone,$request->language);
	        		// $data1['pickup_date'] = date('jS F Y H:i', strtotime($value->pickup_date));
	        		//date('jS F Y H:i',strtotime($value->pickup_date));
	        		$data1['drop'] = is_null($value->drop)?'':$value->drop;
	        		$data1['drop_lat'] = is_null($value->drop_lat)?'':$value->drop_lat;
	        		$data1['drop_lng'] = is_null($value->drop_long)?'':$value->drop_long;
	        		$data1['service_type'] = trans('word.Semi Trailler Max');
	        		$data1['goods_type'] = $goods_type;
	        		$data1['weight'] = is_null($value->weight)?0:$value->weight.' '.(($value->weight_type == '0')?'Kg':'Ton');
	        		$data1['weight_type'] = is_null($value->weight_type)?0:$value->weight_type;
	        		$data1['no_of_vehicle'] = is_null($value->total_vehicle)?0:$value->total_vehicle;
	        		$data1['bid_amount'] = $bid_amount;
					$data1['is_bid_selected'] = $is_bid_selected;
	        		$data1['info'] = is_null($value->info)?'':$value->info;
	        		$data1['base_fare'] = is_null($value->amount)?'':$value->amount.' DA';
		        	$data1['insurance'] = '0 DA';
	        		$data1['amount'] = $total_amount.' DA';
	        		$data1['tax_per'] = is_null($value->tax_per)?'0 %':$value->tax_per.'(%)';
	        		$data1['tax_amount'] = is_null($value->tax_amount)?'0 DA':$value->tax_amount.' DA';

					$data1['truck_id'] = $value->vehicle_id;
					if(strtolower($request->language) == 'en') {
						$data1['truck_name'] = is_null($value->truck_name)?'': $value->truck_name;
					}
					else if(strtolower($request->language) == 'fr') {
						$data1['truck_name'] = is_null($value->truck_name_fr)?'': $value->truck_name_fr;
					} else {
						$data1['truck_name'] = is_null($value->truck_name_ar)?'': $value->truck_name_ar;
					}
					$data1['truck_img'] = is_null($value->truck_img)?'': $value->truck_img;

	        		$get_kmiou_charges = Payment_info::where('shipment_id',$value->id)->first();
	        		
	        		if($get_kmiou_charges != null){

		        		$data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent)?'0(%)':$get_kmiou_charges->percent.'(%)';
		        		$data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion)?'0 DA':$get_kmiou_charges->admin_portion.' DA';

		        		$base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
	        			$data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0')?'':$base_fare.' DA';
	        		}else{

	        			$data1['kmiou_charges_per'] = '';
		        		$data1['kmiou_charges_amount'] = '';
	        		}
		        		
	        		$data1['discount_per'] = is_null($value->discount_per)?'0 %':$value->discount_per.'(%)';
	        		$data1['discount_amount'] = is_null($value->discount_amount)?'0 DA':$value->discount_amount.' DA';
	        		$data1['document'] = is_null($value->document)?'':$value->document;
	        		$data1['person_name'] = is_null($value->person_name)?'':$value->person_name;
                    $data1['id_proof_image'] = is_null($value->id_proof_image)?'':$value->id_proof_image;
                    $data1['signature_image'] = is_null($value->signature_image)?'':$value->signature_image;

                    $get_shipper = User::find($value->user_id);
                    
                    $shipper_lat = '';
                    $shipper_lng = '';

                    if($get_shipper != null){
    
                    	$shipper_lat = ($get_shipper->current_lat == '0' || $get_shipper->current_lat == '' || $get_shipper->current_lat == null)?'':$get_shipper->current_lat;
	                    $shipper_lng = ($get_shipper->current_lng == '0' || $get_shipper->current_lng == '' || $get_shipper->current_lng == null)?'':','.$get_shipper->current_lng;
                    }

                    $get_transporter = User::find($value->transporter_id);
                    
                    $transporter_lat = '';
                    $transporter_lng = '';

                    if($get_transporter != null){
    
                    	$transporter_lat = ($get_transporter->current_lat == '0' || $get_transporter->current_lat == '' || $get_transporter->current_lat == null)?'':$get_transporter->current_lat;
	                    $transporter_lng = ($get_transporter->current_lng == '0' || $get_transporter->current_lng == '' || $get_transporter->current_lng == null)?'':','.$get_transporter->current_lng;
                    }

                    $get_driver = User::find($value->driver_id);
                    
                    $driver_lat = '';
                    $driver_lng = '';

                    if($get_driver != null){
    
                    	$driver_lat = ($get_driver->current_lat == '0' || $get_driver->current_lat == '' || $get_driver->current_lat == null)?'':$get_driver->current_lat;
	                    $driver_lng = ($get_driver->current_lng == '0' || $get_driver->current_lng == '' || $get_driver->current_lng == null)?'':','.$get_driver->current_lng;
                    }
                    
                    $data1['map_image_url'] = Helper::getStaticGmapURLForDirection($value->pickup_lat.','.$value->pickup_long,$value->drop_lat.','.$value->drop_long,$shipper_lat.''.$shipper_lng,$driver_lat.''.$driver_lng,$transporter_lat.''.$transporter_lng);

	        		$data1['status'] = is_null($value->status)?'':$value->status;
					$data1['status_color'] = $status_color;
		        	$data1['status_string'] = $status_string;

	        		if($value->status == '3'){

	        			$cancel_reason = '';

	        			if($value->cancel_reason == '0'){

	        				$cancel_reason = trans('word.Accident');

	        			}else if($value->cancel_reason == '1'){

	        				$cancel_reason = trans('word.Engine Problem');

	        			}else if($value->cancel_reason == '2'){

	        				$cancel_reason = trans('word.Fuel Over');

	        			}else if($value->cancel_reason == '3'){

	        				$cancel_reason = trans('word.Medical Emergency');

	        			}else if($value->cancel_reason == '4'){

	        				$cancel_reason = trans('word.Other');
	        			} 

	        			$data1['reason'] = is_null($value->cancel_reason)?'':$cancel_reason;
                  		$data1['comment'] = is_null($value->cancel_comment)?'':$value->cancel_comment;

	        		}else if($value->report_emergency != '-1'){ //$value->status == '7'

	        			$report_emergency = '';

	        			if($value->report_emergency == '0'){

	        				$report_emergency = trans('word.Security Emergency');

	        			}else if($value->report_emergency == '1'){

	        				$report_emergency = trans('word.Engine Problem');

	        			}else if($value->report_emergency == '2'){

	        				$report_emergency = 'Fuel Over';

	        			}else if($value->report_emergency == '3'){

	        				$report_emergency = trans('word.Truck Tire Flat');

	        			}else if($value->report_emergency == '4'){

	        				$report_emergency = trans('word.Other');
	        			}

                  		$data1['reason'] = is_null($value->report_emergency)?'':$report_emergency;
                    	$data1['comment'] = is_null($value->report_comment)?'':$value->report_comment;
	        		}else{

                  		$data1['reason'] = '';
                  		$data1['comment'] = '';
	        		}

	        		$data1['rate'] = is_null($rate)?'0':$rate;
	        		$data1['review'] = is_null($review)?'':$review;

	        		$data1['payment_type'] = $payment_type;
	        		$data1['payment_status'] = is_null($value->payment_status)?'':$value->payment_status;
	        		$data1['payment_desc'] = trans('word.Payment Made Successfully By').' '.($payment_type);
	        		$data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'jS F Y h:i A', $request->timezone,$request->language);


	        		$data1['sender_first_name'] = is_null($value->sender_first_name)?'':$value->sender_first_name;
	        		$data1['sender_last_name'] = is_null($value->sender_last_name)?'':$value->sender_last_name;
	        		$data1['sender_mobile'] = is_null($value->sender_mobile)?'':$value->sender_mobile;
				
	        		$data1['receiver_first_name'] = is_null($value->receiver_first_name)?'':$value->receiver_first_name;
	        		$data1['receiver_last_name'] = is_null($value->receiver_last_name)?'':$value->receiver_last_name;
	        		$data1['receiver_mobile'] = is_null($value->receiver_mobile)?'':$value->receiver_mobile;
					
					
					

	        		$truck_arr = array();
	        		// user type 4 = driver
	        		if($check_user->user_type != '4'){

		        		if($value->total_vehicle > '1'){

		        			$truck_query = '';
		        			
		        			if($value->status == '0' || ($value->status == '1' && $value->driver_id == '0') ){

		        				$truck_query = ' AND (shipment.status = "0" OR (shipment.status = "1" AND shipment.driver_id = "0") )  ';
		        			
		        			}else if($value->status == '1' || $value->status == '4' || $value->status == '5' || $value->status == '2' || $value->status == '8'){

		        				$truck_query = ' AND shipment.status IN ("1","4","5","2","8") ';
		        			
		        			}else if($value->status == '3'){

		        				$truck_query = ' AND shipment.status = "3" ';
		        			
		        			}else if($value->status == '7'){

		        				//$truck_query = ' AND shipment.status = "7" '; //OLD
		        				$truck_query = ' AND shipment.report_emergency != "-1" ';
								
		        			
		        			}else if($value->status == '6'){

		        				$truck_query = ' AND shipment.status = "6" ';
		        			}

		        			if($check_user->user_type == '3' && $value->status != '0'){

		        				$truck_query .= ' AND shipment.transporter_id = '.$check_user->id.' ';
		        			
		        			}else if ($check_user->user_type == '4' && $value->status != '0') {
		        				
		        				$truck_query .= ' AND shipment.driver_id = '.$check_user->id.' ';
		        			}

		        			$get_total_truck_of_shipment = DB::select(' select * from shipment where unique_id = '.$value->unique_id.' '.$truck_query.' AND bid_status="'.$value->bid_status.'"');
		        			
		        			if(count($get_total_truck_of_shipment) > 1 && $check_user->user_type != "2" && $value->status == '0'){
								
								$get_truck_shipment_ids = DB::select(' select GROUP_CONCAT(id) as shipment_ids from shipment where unique_id = '.$value->unique_id.' '.$truck_query.' AND bid_status="'.$value->bid_status.'"');

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
			        }

        			
        			array_push($response,$data1);
				}
       		}

	        	Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success' ,'result' => $response ]));
	            return json_encode(['success' => 1, 'msg' => 'Success' ,'result' => $response ]);

		    }
	        else
	        {
	        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'User Not Found','result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => trans('word.User Not Found'),'result' => [] ]);
	            
	        }
	    
		} catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }
   
    public function place_new_bid(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));


    		$shipment_ids = explode (",", $request->shipment_id);

	    	$msg = '';

			foreach ($shipment_ids as $key => $shipment_id) {


	    		$shipment = Shipment::find($shipment_id);	 

	    		if($shipment != null && $shipment->status == '0' && $shipment->bid_status == '0')
	    		{


	    			$check_bid = is_null(Shipment_bid::where('shipment_id',$shipment_id)->where('user_id',$request->user_id)->first())? new Shipment_bid : Shipment_bid::where('shipment_id',$shipment_id)->where('user_id',$request->user_id)->first();

	    			$check_bid->shipment_id = $shipment_id;
	    			$check_bid->user_id = $request->user_id;
	    			$check_bid->bid_amount = $request->bid_amount;
	    			
	    			$check_bid->save();

	    			$get_truck_no = Shipment_info::where('shipment_id',$shipment_id)->first();

	    			app()->setLocale('en');	
					$msg_en = trans('word.Placed New Bid to Your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

					app()->setLocale('fr');	
					$msg_fr = trans('word.Placed New Bid to Your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

					app()->setLocale('ar');	
					$msg_ar = trans('word.Placed New Bid to Your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

	    			// send notification
	          		Helper::send_push_notification($request->user_id,$shipment->user_id,'New Bid Placed',$msg_en,'2',$shipment->id,$msg_fr,$msg_ar);
	       			
		       		
		        }
		        else
		        {
					app()->setLocale(strtolower($request->language));
		        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
		            return json_encode(['success' => 0, 'msg' => trans('word.Shipment Not Found'),'result' => [] ]);
		            
		        }

		   
		    }

            app()->setLocale(strtolower($request->language));
			Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Bid Placed Successfully','result' => [] ]));
			return json_encode(['success' => 1, 'msg' => trans('word.Bid Placed Successfully'),'result' => [] ]);
	    
		} catch(Exception $ex) {
            
            app()->setLocale(strtolower($request->language));
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }

    public function edit_delete_bid(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));


    		$shipment_ids = explode (",", $request->shipment_id);

	    	$msg = '';

			foreach ($shipment_ids as $key => $shipment_id) {

	    		$shipment = Shipment::find($shipment_id);	 
	    		
	    		$check_bid = Shipment_bid::where('shipment_id',$shipment_id)->where('user_id',$request->user_id)->first();

		    	$msg = '';

	    		if($shipment != null ){

	    			if($check_bid != null)
	    			{
		    			if($request->type == '0'){

			    			$check_bid->shipment_id = $shipment_id;
			    			$check_bid->user_id = $request->user_id;
			    			$check_bid->bid_amount = $request->bid_amount;
			    			
			    			$check_bid->save();

			    			$msg = trans('word.Bid Updated Successfully');

		    			}else if($request->type == '1'){

			    			$check_bid->delete();
			    			
			    			$msg = trans('word.Bid Cancelled Successfully');

		    				DB::table('notification')->where('from_user_id',$request->user_id)->where('noti_type',"2")->where('ref_id',$shipment_id)->delete();
		    			}
		    		}
		    		else
		    		{	
		    				$check_bid = new Shipment_bid;
			    			$check_bid->shipment_id = $shipment_id;
			    			$check_bid->user_id = $request->user_id;
			    			$check_bid->bid_amount = $request->bid_amount;
			    			
			    			$check_bid->save();

			    			$msg = trans('word.Bid Updated Successfully');
		    		}
	    			
		        }
		        else
		        {
		        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
		            return json_encode(['success' => 0, 'msg' => trans('word.Shipment Not Found'),'result' => [] ]);
		            
		        }
	        }

    		Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
	        return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
       
	    
		} catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }

    

    public function bidder_list(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));

    		$shipment = Shipment::find($request->shipment_id);	 

	       	if($shipment != null){		
				
				$response = array();

    			$check_bid = DB::select('Select shipment_bid.*,users.first_name as user_first_name,users.last_name as user_last_name,users.profile_pic as user_profile_pic,users.total_rate_count as user_total_rate_count,users.avg_rating as user_avg_rating 
    				from shipment_bid left join users on users.id=shipment_bid.user_id where shipment_id = '.$request->shipment_id.' AND shipment_bid.status != "2" ');
				
					foreach ($check_bid as $key => $value) {
						
		        		$data1 = array();

	    				$data1['bid_id'] = $value->id;
	    				$data1['shipment_id'] = $value->shipment_id;
	    				$data1['user_id'] = $value->user_id;
	    				$data1['user_name'] = is_null($value->user_first_name)?'':$value->user_first_name.' '.(is_null($value->user_last_name)?'':$value->user_last_name);
	    				$data1['profile_pic'] = is_null($value->user_profile_pic)?'':$value->user_profile_pic;
	    				$data1['total_rate_count'] = is_null($value->user_total_rate_count)?'(0)':'('.$value->user_total_rate_count.')';
	    				$data1['avg_rating'] = is_null($value->user_avg_rating)?'':$value->user_avg_rating;
		        		$data1['bid_amount'] = is_null($value->bid_amount)?'':$value->bid_amount.' DA';
		        		$data1['is_selected'] = ($value->status == '1')?'1':'0';
						$data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'jS F Y h:i A', $request->timezone,$request->language);
		        		// $data1['created_at'] = $value->created_at;
		        		
	        			array_push($response,$data1);
					}
	       		

	        	Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success' ,'result' => $response ]));
	            return json_encode(['success' => 1, 'msg' => 'Success' ,'result' => $response ]);

		    }
	        else
	        {
	        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => trans('word.Shipment Not Found'),'result' => [] ]);
	            
	        }
    		
	    
		} catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }

	public function accept_reject_shipment_request(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));


			$shipment_ids = explode (",", $request->shipment_id);

	    	$msg = '';

			foreach ($shipment_ids as $key => $shipment_id) {

	    		$shipment = Shipment::find($shipment_id);	 

	    		$check_user = User::find($request->user_id);	 

	    		if($shipment != null && $shipment->status == '0'){

	    			$is_accept = $request->is_accept;

	    			if($is_accept == '0'){
	    				
		        		
	    				$msg = trans('word.Request Rejected Successfully');
	    				
	    				$shipment->transporter_id = '0';
	    				$shipment->driver_id = '0';


	    				if($shipment->bid_status == '1'){

	    					$bid = Shipment_bid::where('shipment_id',$shipment_id)->where('user_id',$request->user_id)->first();
	    					//$bid->status = '2';

	    					$bid->delete();
	    					
	    					$shipment->bid_status = '0';

	    					DB::table('shipment_request_count')->where('shipment_id',$shipment->id)->update(['is_read' => '0']);
								
							$shipment_info = Shipment_info::where('shipment_id',$shipment->id)->first();

						    if($shipment_info->quotation_type == '0'){
				              $order_type = trans('word.Bid Shipment Truck No.').' '.($shipment_info->no_of_vehicle).' '.trans('word.Order');
				            }else if ($shipment_info->quotation_type == '1'){
				              $order_type = trans('word.Fixed Shipment Truck No.').' '.($shipment_info->no_of_vehicle).' '.trans('word.Order');
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
					          Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment',$msg_en,'1',$shipment->id,$msg_fr,$msg_ar);
				            }

	    				}else{

	    					DB::table('shipment_request_count')->where('shipment_id',$shipment->id)->where('user_id',$request->user_id)->update(['is_read' => '2']);
	    				}

	    				// no need to send notification to shipper when transporter reject shipment before acceptance

	    				/*$get_truck_no = Shipment_info::where('shipment_id',$shipment_id)->first();

		   				//send notification to shipper 
	    				Helper::send_push_notification($request->user_id,$shipment->user_id,'Order Rejected','rejected your order Truck No. '.$get_truck_no->no_of_vehicle.' Order No. #'.$shipment->unique_id,'9',$shipment->id);*/

	    				
	    			}else{
						app()->setLocale(strtolower($request->language));

	    				$shipment->status = '1';
		    				
	    				$msg = trans('word.Request Accepted Successfully');

		    			if($check_user->user_type == '3'){

		    				$shipment->transporter_id = $request->user_id;
		    				//$shipment->driver_id = $request->assign_driver_id;
		    			
		    			}else if($check_user->user_type == '4'){

		    				$shipment->driver_id = $request->user_id;
		    			}

	    				// update discount in shipment
	    				if($shipment->discount_per != '0'){
	    					$shipment->discount_amount = ($shipment->amount * $shipment->discount_per) / 100;
	    				}

	    				if($request->assign_driver_id != '0'){

							$check_truck = Transporter_truck::where('truck_id',$shipment->vehicle_id)->where('user_id',$request->assign_driver_id)->where('status',"1")->first();

							if($check_truck != null) {

								$shipment->driver_id = $request->assign_driver_id;
	
								$get_truck_no = Shipment_info::where('shipment_id',$shipment_id)->first();
	
	
								app()->setLocale('en');	
								$msg_en = trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

								app()->setLocale('fr');	
								$msg_fr = trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

								app()->setLocale('ar');	
								$msg_ar = trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;
	
								// send notification
								Helper::send_push_notification($request->user_id,$shipment->driver_id,'Assign Driver',$msg_en,'4',$shipment->id,$msg_fr,$msg_ar);
							}
							else {

								app()->setLocale('en');
				
								$msg = trans('word.This Driver have different truck');
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

						// read request count 
						DB::table('shipment_request_count')->where('shipment_id',$shipment->id)->update(['is_read' => '1']);

			          	$get_truck_no = Shipment_info::where('shipment_id',$shipment_id)->first();

	  		            app()->setLocale('en');	
						$msg_en = trans('word.accepted your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

						app()->setLocale('fr');	
						$msg_fr = trans('word.accepted your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

						app()->setLocale('ar');	
						$msg_ar = trans('word.accepted your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;


						// send notification
						Helper::send_push_notification($request->user_id,$shipment->user_id,'Order Accepted',$msg_en,'5',$shipment->id,$msg_fr,$msg_ar);
		        	
						$track_shipment = new Track_shipment;
						$track_shipment->status = '1';
						$track_shipment->shipment_id = $shipment->id;
						$track_shipment->payment_status = $shipment->payment_status;
						$track_shipment->save();
	    			}
	    			
	    			$shipment->save();

		       		
		        }
		        else
		        {
					app()->setLocale(strtolower($request->language));
		        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
		            return json_encode(['success' => 0, 'msg' => trans('word.Shipment Not Found'),'result' => [] ]);
		            
		        }
		    }

            app()->setLocale(strtolower($request->language));
		    Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
			        return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
	    
		} catch(Exception $ex) {
            
            app()->setLocale(strtolower($request->language));
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }

    public function cancel_shipment(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));

    		$shipment = Shipment::find($request->shipment_id);	 
    		$user = User::find($request->user_id);	 

    		if($shipment != null /*&& ($shipment->status == '0' || $shipment->status == '1' || $shipment->status == '4')*/ )
    		{

    			$msg = '';

				$shipment->status = '3';
				$msg = trans('word.Shipment Cancelled Successfully');

				$shipment->cancel_reason = is_null($request->cancel_reason)?"4":$request->cancel_reason;
				$shipment->cancel_comment = $request->comment;
				$shipment->cancel_by = $request->user_id;
			
				$shipment->save();

				$track_shipment = new Track_shipment;
				$track_shipment->shipment_id = $shipment->id;
				$track_shipment->status = '3';
				$track_shipment->payment_status = $shipment->payment_status;
				$track_shipment->save();

				if($user->user_type != '2')
				{

					$message = '';

		              if($request->cancel_reason == '0'){
		                
		                $message = trans('word.Accident');

		              }else if($request->cancel_reason == '1'){
		                
		                $message = trans('word.Engine Problem');

		              }else if($request->cancel_reason == '2'){
		                
		                $message = trans('word.Fuel Over');

		              }else if($request->cancel_reason == '3'){
		                
		                $message = trans('word.Medical Emergency');

		              }else if($request->cancel_reason == '4'){
		                
		                $message = trans('word.Other Reason');

		              }


		                $user_type = '';

				        if($user->user_type == '4'){
				        	$user_type = trans("word.driver");
				        }
				        if($user->user_type == '3')
				        {
				        	$user_type = trans("word.transporter");
				        }
				        
						$shipment_info = Shipment_info::where('shipment_id',$request->shipment_id)->first();


	  		            app()->setLocale('en');	
						$msg_en = ' ('.$user_type.') '.trans('word.cancelled your order due to').' '.$message.' '.trans('word.Truck No.').' '.$shipment_info->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

						app()->setLocale('fr');	
						$msg_fr = ' ('.$user_type.') '.trans('word.cancelled your order due to').' '.$message.' '.trans('word.Truck No.').' '.$shipment_info->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

						app()->setLocale('ar');	
						$msg_ar = ' ('.$user_type.') '.trans('word.cancelled your order due to').' '.$message.' '.trans('word.Truck No.').' '.$shipment_info->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;


				        // send notification shipper
				        Helper::send_push_notification($request->user_id,$shipment->user_id,'Order Cancelled',$msg_en,'7',$shipment->id,$msg_fr,$msg_ar);

		
						if($user->user_type == '3'){


				          // send notification driver
				          if($shipment->driver_id != '0')
				          {

	 		          	        app()->setLocale('en');	
								$msg_en = trans('word.transporter').' '.trans('word.cancelled your order due to').$message.' '.trans('word.Truck No.').$shipment_info->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

								app()->setLocale('fr');	
								$msg_fr = trans('word.transporter').' '.trans('word.cancelled your order due to').$message.' '.trans('word.Truck No.').$shipment_info->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

								app()->setLocale('ar');	
								$msg_ar = trans('word.transporter').' '.trans('word.cancelled your order due to').$message.' '.trans('word.Truck No.').$shipment_info->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;



				            Helper::send_push_notification($request->user_id,$shipment->driver_id,'Order Cancelled',$msg_en,'7',$shipment->id,$msg_fr,$msg_ar);
				          }

				        }

				        else if($user->user_type == '4'){

				        	 // send notification transporter
				          if($shipment->transporter_id != '0')
				          {

			          	    app()->setLocale('en');	
							$msg_en =' ('.trans('word.driver').') '. trans('word.cancelled your order due to').' '.$message.' '.trans('word.Truck No.').' '.$shipment_info->no_of_vehicle.' '.('word.Order No.').' #'.$shipment->unique_id;

							app()->setLocale('fr');	
							$msg_fr = ' ('.trans('word.driver').') '. trans('word.cancelled your order due to').' '.$message.' '.trans('word.Truck No.').' '.$shipment_info->no_of_vehicle.' '.('word.Order No.').' #'.$shipment->unique_id;

							app()->setLocale('ar');	
							$msg_ar = ' ('.trans('word.driver').') '. trans('word.cancelled your order due to').' '.$message.' '.trans('word.Truck No.').' '.$shipment_info->no_of_vehicle.' '.('word.Order No.').' #'.$shipment->unique_id;

				            Helper::send_push_notification($request->user_id,$shipment->transporter_id,'Order Cancelled',$msg_en,'7',$shipment->id,$msg_fr,$msg_ar);
				          }
				        }


				          //recreate shipment---------------------------------------

				           
				            $discount_per = '0';


				            $new_shipment = new Shipment;
				            $new_shipment->unique_id = $shipment->unique_id;
				            $new_shipment->driver_id = '0';
							$new_shipment->transporter_id = '0';
				            $new_shipment->user_id = $shipment->user_id;
				            $new_shipment->vehicle_id = $shipment->vehicle_id;
				            $new_shipment->card_id = $shipment->card_id;
				            $new_shipment->promo_id = isset($shipment->promo_id)?$shipment->promo_id:'0';

							/*
							if($user->user_type == '4'){
				            	$new_shipment->transporter_id = $shipment->transporter_id;
				            	$new_shipment->status = '1';
							}
							*/


				            if(isset($shipment->promo_id) && $shipment->promo_id != '0' && $shipment->promo_id != '')
				            {
								$get_coupon = Coupon::find($shipment->promo_id);
								$discount_per = $get_coupon->discount;
				            }

					        $shipper_user = User::find($shipment->user_id);

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
				            if($new_shipment->discount_per != '0' && $shipment->quotation_type != '0')
				            {
				            	$new_shipment->discount_amount = ($new_shipment->amount * $new_shipment->discount_per) / 100;
				            }

				            $new_shipment->save();

							$new_shipment->unique_id = $new_shipment->id;
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


				            //if driver
							/*
							if($user->user_type == '4') {
					            $track_shipment = new Track_shipment;
					            $track_shipment->shipment_id = $new_shipment->id;
					            $track_shipment->status = '1';
					            $track_shipment->payment_status = '0';
					            $track_shipment->save();
				        	}
							*/

				            $order_type = '';
							app()->setLocale(strtolower($request->language));

				           if($shipment_info->quotation_type == '0'){
				              $order_type = trans('word.Bid Shipment Truck No.').' '.($shipment_info->no_of_vehicle).' '.trans('word.Order');
				            }
				            else if ($shipment_info->quotation_type == '1'){
				              $order_type = trans('Fixed Shipment Truck No.').' '.($shipment_info->no_of_vehicle).' '.trans('word.Order');
				            }

				            //$getAccossiatDriverTruck = DB::select('select COUNT(1) AS truckcount from transporter_truck where truck_id = '.$request->vehicle_id.' AND status = "1" AND user_id in (SELECT id FROM users WHERE ref_id = '.$value->id.' ) ');
							//!empty($getAccossiatDriverTruck)

				            $check_transporter = User::where('user_type',"3")->where('approve',"1")->where('is_verify','1')->where('status','1')->where('id','!=',$request->user_id)->orderBy('created_at','desc')->get();

				            foreach ($check_transporter as $key => $value) {

					            app()->setLocale('en');	
								$msg_en =trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$new_shipment->unique_id;

								app()->setLocale('fr');	
								$msg_fr = trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$new_shipment->unique_id;

								app()->setLocale('ar');	
								$msg_ar = trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$new_shipment->unique_id;

					            // send notification
					            Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment',$msg_en,'1',$new_shipment->id,$msg_fr,$msg_ar);

								// read request count
								DB::table('shipment_request_count')->insert(['user_id' => $value->id,'shipment_id' => $new_shipment->id,'is_read' => '0']);
				            }

				            $check_driver = User::where('user_type',"4")->where('approve',"1")->where('is_verify','1')->where('status','1')->where('id','!=',$request->user_id)->where('ref_id','0')->orderBy('created_at','desc')->get();

				            foreach ($check_driver as $key => $value) {


			            	    app()->setLocale('en');	
								$msg_en =' '.trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$new_shipment->unique_id;

								app()->setLocale('fr');	
								$msg_fr = ' '.trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$new_shipment->unique_id;

								app()->setLocale('ar');	
								$msg_ar = ' '.trans('word.Book New').' '.$order_type.' '.trans('word.No').' #'.$new_shipment->unique_id;

				          	   // send notification
				         	   Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment',$msg_en,'1',$new_shipment->id,$msg_fr,$msg_ar);

								// read request count
								DB::table('shipment_request_count')->insert(['user_id' => $value->id,'shipment_id' => $new_shipment->id,'is_read' => '0']);
				            }
            
         
				}
				else if($user->user_type == '2'){

					DB::table('notification')->where('noti_type',"1")->where('ref_id',$request->shipment_id)->delete();

		            if($shipment->driver_id != '0'){
						
		            	$get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();


		            	app()->setLocale('en');	
						$msg_en =' ('.trans('word.shipper').') '.trans('word.cancelled your order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

						app()->setLocale('fr');	
						$msg_fr = ' ('.trans('word.shipper').') '.trans('word.cancelled your order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

						app()->setLocale('ar');	
						$msg_ar =' ('.trans('word.shipper').') '.trans('word.cancelled your order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;


						// send notification to driver
			            Helper::send_push_notification($request->user_id,$shipment->driver_id,'Order Cancelled',$msg_en,'7',$shipment->id,$msg_fr,$msg_ar);
		        	}
		            
		            if($shipment->transporter_id != '0'){

		            	$get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

		            	app()->setLocale('en');	
						$msg_en =' ('.trans('word.shipper').') '.trans('word.cancelled your order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

						app()->setLocale('fr');	
						$msg_fr = ' ('.trans('word.shipper').') '.trans('word.cancelled your order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

						app()->setLocale('ar');	
						$msg_ar =' ('.trans('word.shipper').') '.trans('word.cancelled your order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;


		            	// send notification to transporter
		           		Helper::send_push_notification($request->user_id,$shipment->transporter_id,'Order Cancelled',$msg_en,'7',$shipment->id,$msg_fr,$msg_ar);
		            }

				}

				app()->setLocale(strtolower($request->language));
       			
	       		Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
		        return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
	        }
	        else
	        {
				app()->setLocale(strtolower($request->language));
	        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => trans('word.Shipment Not Found'),'result' => [] ]);
	            
	        }
	    
		} catch(Exception $ex) {
            
            app()->setLocale(strtolower($request->language));
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }

    public function report_emergency(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));

    		$shipment = Shipment::find($request->shipment_id);	 

    		if($shipment != null && ($shipment->status == '1' || $shipment->status == '2'|| $shipment->status == '4' || $shipment->status == '5' || $shipment->status == '8' || $shipment->status == '9') ){

    			$msg = '';

				//$shipment->status = '7'; //Comment By Mehul
				$msg = trans('word.Shipment Reported Successfully');

				$shipment->report_emergency = $request->report_reason;
				$shipment->status_when_report = $shipment->status;
				$shipment->report_comment = $request->comment;
				$shipment->updated_by = $request->user_id;
			
				$shipment->save();

				$track_shipment = new Track_shipment;
				$track_shipment->shipment_id = $shipment->id;
				$track_shipment->status = '7';
				$track_shipment->payment_status = $shipment->payment_status;
				$track_shipment->save();

			    $message = '';
              if($request->report_reason == '0'){
                
                $message = trans('word.Security Emergency');

              }else if($request->report_reason == '1'){
                
                $message = trans('word.Engine Problem');

              }else if($request->report_reason == '2'){
                
                $message = trans('word.Fuel Over');

              }else if($request->report_reason == '3'){
                
                $message = trans('word.Truck Tire Flat');

              }else if($request->report_reason == '4'){
                
                $message = trans('word.Other Reason');

              }

            $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

             	app()->setLocale('en');	
				$msg_en =' ('.trans('word.driver').' '.trans('word.Reported Emergency on your order due to').''.$message.' '.trans('word.Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

				app()->setLocale('fr');	
				$msg_fr = ' ('.trans('word.driver').' '.trans('word.Reported Emergency on your order due to').''.$message.' '.trans('word.Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

				app()->setLocale('ar');	
				$msg_ar =' ('.trans('word.driver').' '.trans('word.Reported Emergency on your order due to').''.$message.' '.trans('word.Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

            // send notification
            Helper::send_push_notification($request->user_id,$shipment->user_id,'Order Reported Emergency',$msg_en,'8',$shipment->id,$msg_fr,$msg_ar);

       			
            app()->setLocale(strtolower($request->language));
	       		Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
		        return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
	        }
	        else
	        {
				app()->setLocale(strtolower($request->language));
	        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => trans('word.Shipment Not Found'),'result' => [] ]);
	            
	        }
	    
		} catch(Exception $ex) {
            app()->setLocale(strtolower($request->language));
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }

    public function update_shipment_status(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));

    		$shipment = Shipment::find($request->shipment_id);	 
    		
			//STATUS :: 0=pending, 1=confirm, 2=on_the_way, 3=cancelled, 4=arrived, 5=strat_shipment, 6=reached, 7=report, 8=arrived at drop of loctions, 9=on_the_way_to_pickup

    		$check_user = User::find($request->user_id);

			if($request->shipment_status == '9') { // && $check_user->ref_id != "0" Associate Driver

				// hide rejected shipment
				$getAlreadyActiveShipment = DB::select('SELECT COUNT(1) AS already_active FROM shipment WHERE driver_id = '.$request->user_id.' AND status IN ("2","4","5","8","9") ');

				if(!empty($getAlreadyActiveShipment) && $getAlreadyActiveShipment[0]->already_active > 0) {
					app()->setLocale(strtolower($request->language));
					Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'You have active shipment please first finish it before start new shipment','result' => [] ]));
					return json_encode(['success' => 0, 'msg' => trans('word.You have active shipment please first finish it before start new shipment'),'result' => [] ]);
					die;
				}
			}

    		if($shipment != null && ($shipment->status == '1' || $shipment->status == '2'  || $shipment->status == '4'  || $shipment->status == '5' || $shipment->status == '8' || $shipment->status == '9'  ) ){

				$msg = '';	

				$shipment->status = $request->shipment_status;
				
				$msg = trans('word.Shipment Status Updated Successfully');

				$shipment->updated_by = $request->user_id;
			
				$shipment->save();

				$track_shipment = new Track_shipment;
				$track_shipment->shipment_id = $shipment->id;
				$track_shipment->status = $request->shipment_status;
				$track_shipment->payment_status = $shipment->payment_status;
				$track_shipment->save();

			    if($request->shipment_status == '6'){
			    	
			    	$shipment_info = Shipment_info::where('shipment_id',$request->shipment_id)->first();
			    	$shipment_info->person_name = is_null($request->person_name)?'':$request->person_name;
			    	$shipment_info->id_proof_image = is_null($request->id_proof_image)?'':$request->id_proof_image;
			    	$shipment_info->signature_image = is_null($request->signature_image)?'':$request->signature_image;
			    		
			    	if($shipment->payment_type == '0' && $request->is_cash_received == '1'){

				    	$shipment->payment_status = '1';
						$shipment->payment_received_by = $shipment->driver_id;
			    	}
				
			    	$shipment_info->save();
			    }

			    $message = '';
	            if($request->shipment_status == '2'){
	            
	                $message = ' is on the way ';

	            }else if($request->shipment_status == '4'){
	            
			    	$shipment_info = Shipment_info::where('shipment_id',$request->shipment_id)->first();
			    	$shipment_info->arrive_pickup_date = date('Y-m-d H:i:s');
			    	$shipment_info->save();

	                $message = trans('word.has arrived Your package is ready for shipment');
	            
	            }else if($request->shipment_status == '5'){

	                $message = trans('word.has started your shipment');
	            
	            }else if($request->shipment_status == '6'){

	                $message = trans('word.has delivered your parcel');
	            
	            }else if($request->shipment_status == '8'){

	            	$shipment_info = Shipment_info::where('shipment_id',$request->shipment_id)->first();
			    	$shipment_info->arrive_drop_date = date('Y-m-d H:i:s');
			    	$shipment_info->save();

	                $message = trans('word.has reached near your delivery address');
	            
	            }else if($request->shipment_status == '9'){

	                $message = trans('word.is on the way to pickup your parcel');
	            }

	            $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

	            app()->setLocale('en');	
				$msg_en =' ('.trans('word.driver').') '.$message.' '.trans('word.Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

				app()->setLocale('fr');	
				$msg_fr = ' ('.trans('word.driver').') '.$message.' '.trans('word.Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

				app()->setLocale('ar');	
				$msg_ar =' ('.trans('word.driver').') '.$message.' '.trans('word.Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

	            // send notification to the shipper
	            Helper::send_push_notification($request->user_id,$shipment->user_id,'Order Status Update',$msg_en,'6',$shipment->id,$msg_fr,$msg_ar);

	            if($shipment->transporter_id != '0'){

	            $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

                app()->setLocale('en');	
				$msg_en =' ('.trans('word.driver').') '.' '.$message.' '.trans('word.Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

				app()->setLocale('fr');	
				$msg_fr = ' ('.trans('word.driver').') '.' '.$message.' '.trans('word.Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

				app()->setLocale('ar');	
				$msg_ar =' ('.trans('word.driver').') '.' '.$message.' '.trans('word.Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

	            // send notification to the transporter
	            Helper::send_push_notification($request->user_id,$shipment->transporter_id,'Order Status Update',$msg_en,'6',$shipment->id,$msg_fr,$msg_ar);
	            }

	            $user = User::find($shipment->user_id);
	            $driver = User::find($shipment->driver_id);

	            // send mail
	            $user_detail =array();
                $user_detail['name'] = is_null($user->first_name)?'':$user->first_name.' '.(is_null($user->last_name)?'':$user->last_name);
                $user_detail['message'] = (is_null($driver->first_name)?'':$driver->first_name).' '.(is_null($driver->last_name)?'':$driver->last_name).' (driver) '.$message.' order no. #'.$shipment->id;


                Mail::send('emails.update_status', ['user' => (object)$user_detail], function($message) use ($user) {
                    $message->from(env('MAIL_USERNAME'), 'KMIOU');
                    $message->to($user->email);
                    $message->subject('KMIOU Status Update');
                });
       			
				app()->setLocale(strtolower($request->language));
	       		Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
		        return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
	        }
	        else
	        {
				app()->setLocale(strtolower($request->language));
	        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => trans('word.Shipment Not Found'),'result' => [] ]);
	            
	        }
	    
		} catch(Exception $ex) {
            app()->setLocale(strtolower($request->language));
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }


    public function select_bidder(Request $request)
    {
    	try{
            app()->setLocale(strtolower($request->language));

			$shipment_id = $request->shipment_id;
    		$shipment = Shipment::find($shipment_id);	 
    		
    		if($shipment != null && $shipment->status == '0'){

				$msg = '';	

    			$bid = Shipment_bid::find($request->bid_id);
    			$bid->status = '1';
    			$bid->save();

				$check_user = User::find($bid->user_id);

	            if($check_user->user_type == '3'){
	            
	              $shipment->transporter_id = $bid->user_id;
	            
	            }else if($check_user->user_type == '4'){
	              
	              $shipment->driver_id = $bid->user_id;

	            }
	            
				$shipment->amount = $bid->bid_amount;
        		$shipment->basic_amount = $bid->bid_amount;
        		
        		$shipment->bid_status = '1';

        		$shipment->card_id = is_null($request->card_id)?0:$request->card_id;
				
				$msg = trans('word.Bidder Selected Successfully');

				$shipment->updated_by = $request->user_id;
			
    			$shipment->save();


    			/*DB::table('shipment_bid')->where('shipment_id',$request->shipment_id)->where('id','!=',$request->bid_id)->update(['status' => "2"]);*/

    			$get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

    			app()->setLocale('en');	
				$msg_en =trans('word.selected your bid Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

				app()->setLocale('fr');	
				$msg_fr = trans('word.selected your bid Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

				app()->setLocale('ar');	
				$msg_ar =trans('word.selected your bid Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

	    		// send notification
            	Helper::send_push_notification($request->user_id,$bid->user_id,'Bidder Selected',$msg_en,'3',$shipment->id,$msg_fr,$msg_ar);


				//ADDED BY MEHUL BASE ON CLIENT COMMENT 
				app()->setLocale(strtolower($request->language));

				$shipment = Shipment::find($shipment_id);	 
				$shipment->status = '1';
					
				$msg = trans('word.Request Accepted Successfully');

				// update discount in shipment
				if($shipment->discount_per != '0'){
					$shipment->discount_amount = ($shipment->amount * $shipment->discount_per) / 100;
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

				// read request count 
				DB::table('shipment_request_count')->where('shipment_id',$shipment->id)->update(['is_read' => '1']);

				$get_truck_no = Shipment_info::where('shipment_id',$shipment_id)->first();

				app()->setLocale('en');	
				$msg_en = trans('word.accepted your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

				app()->setLocale('fr');	
				$msg_fr = trans('word.accepted your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

				app()->setLocale('ar');	
				$msg_ar = trans('word.accepted your Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;


				// send notification
				Helper::send_push_notification($request->user_id,$shipment->user_id,'Order Accepted',$msg_en,'5',$shipment->id,$msg_fr,$msg_ar);
			
				$track_shipment = new Track_shipment;
				$track_shipment->status = '1';
				$track_shipment->shipment_id = $shipment->id;
				$track_shipment->payment_status = $shipment->payment_status;
				$track_shipment->save();


	    			
				$shipment->save();



       			
				app()->setLocale(strtolower($request->language));
	       		Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
		        return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
	        }
	        else
	        {
				app()->setLocale(strtolower($request->language));
	        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => trans('word.Shipment Not Found'),'result' => [] ]);
	            
	        }
	    
		} catch(Exception $ex) {
            app()->setLocale(strtolower($request->language));
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
    	}
    }
    
    public function rate_shipment(Request $request){

    	try{  
			app()->setLocale(strtolower($request->language));
            $check_user = User::where('id', '=', $request->user_id)->first();

            if($check_user != null){

            	$shipment = Shipment::find($request->ref_id);

                $review  = new Review;
                
                $review->user_id = $request->user_id;
                
                if($shipment->transporter_id != '0'){

                    $review->ref_user_id = $shipment->transporter_id;

                }else{

                   $review->ref_user_id = $shipment->driver_id;
                }

                $review->ref_id = $request->ref_id;
                
                $review->rate = $request->rate;
                $review->review_text = $request->review_text;
                //$review->type = $request->type; //0=gasguy
                $review->status = '1'; // 1=active ,0= inactive
                $review->save();

       			app()->setLocale('en');	
				$msg_en =' '.trans('word.added Review to your shipment Order No.').' #'.$shipment->unique_id;

				app()->setLocale('fr');	
				$msg_fr = ' '.trans('word.added Review to your shipment Order No.').' #'.$shipment->unique_id;

				app()->setLocale('ar');	
				$msg_ar =' '.trans('word.added Review to your shipment Order No.').' #'.$shipment->unique_id;


                // send notification
                Helper::send_push_notification($request->user_id,$shipment->driver_id,'Rate Shipment',$msg_en,'11',$shipment->id,$msg_fr,$msg_ar);

				app()->setLocale(strtolower($request->language));
                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Shipment Rating Submitted Successfully','result' => [] ]));
                return json_encode(['success' => 1, 'msg' => trans('word.Shipment Rating Submitted Successfully'),'result' => []]);

            }
            else
            {
				app()->setLocale(strtolower($request->language));
                $msg=trans('word.User not Found');          
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
            }
            
        }catch(Exception $ex) {
            app()->setLocale(strtolower($request->language));
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    public function track_shipment(Request $request){

    	try{  
			app()->setLocale(strtolower($request->language));
            $shipment = Shipment::find($request->shipment_id);

            $response = array();

            if($shipment != null && $shipment->status != "3" && $shipment->report_emergency != "-1"){ //$shipment->status != "7"

                $track  = Track_shipment::where('shipment_id',$request->shipment_id)->get();

                $count = 1;

                if($track != null && $track != '[]'){

	                foreach ($track as $key => $value) {
	                	
	                	$data1['step'] = $count;
	    				$data1['date'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i', $request->timezone);
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
                
                	$msg=trans('word.Shipment not Found');          
            	
            	}else if($shipment->status == "3"){

                	$msg=trans('word.Shipment Cancelled By Driver');          

            	}else if($shipment->streport_emergencyatus != "-1"){ //$shipment->status == "7"
                	$msg=trans('word.Shipment Driver Reported Emergency');          
                }
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
            }
            
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    public function assign_driver(Request $request){
    try{
		app()->setLocale(strtolower($request->language));

        $shipment = Shipment::find($request->shipment_id);   
        if($shipment != null && ($shipment->status == '0' || $shipment->status == '1' || $shipment->status == '4' ) ){
            
			$check_truck = Transporter_truck::where('truck_id',$shipment->vehicle_id)->where('user_id',$request->driver_id)->where('status',"1")->first();

			if($check_truck != null) {

				if($shipment->driver_id == '0'){

					$shipment->driver_id = $request->driver_id;
					$shipment->save();            
						 
					 $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();
	
					 app()->setLocale('en');	
					$msg_en =trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;
	
					app()->setLocale('fr');	
					$msg_fr = trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;
	
					app()->setLocale('ar');	
					$msg_ar =trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;
	
	
					// send notification
					Helper::send_push_notification($request->user_id,$shipment->driver_id,'Assign Driver',$msg_en,'4',$shipment->id,$msg_fr,$msg_ar);
				  
				  }else{
	
					$shipment->driver_id = $request->driver_id;
					$shipment->status = '1';
					$shipment->save();    
	
					DB::table('notification')->where('from_user_id',$request->user_id)->where('noti_type',"4")->where('ref_id',$request->shipment_id)->delete();
	
					 $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();
	
					 app()->setLocale('en');	
					$msg_en =trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;
	
					app()->setLocale('fr');	
					$msg_fr = trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;
	
					app()->setLocale('ar');	
					$msg_ar =trans('word.assign you shipment Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;
	
	
	
					// send notification
					Helper::send_push_notification($request->user_id,$shipment->driver_id,'Assign Driver',$msg_en,'4',$shipment->id,$msg_fr,$msg_ar);   
				  }
				  app()->setLocale(strtolower($request->language));
				
				  Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Driver Assigned Successfully','result' => [] ]));
				  return json_encode(['success' => 1, 'msg' => trans('word.Driver Assigned Successfully'),'result' => [] ]);
			} else {

				app()->setLocale(strtolower($request->language));

				$msg = trans('word.This Driver have different truck');
				Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
				return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
			}
      
          }else{
            app()->setLocale(strtolower($request->language));

            $msg = trans('word.Shipment Not Found');
            Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
			return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
          }

        }catch(Exception $ex) {
            app()->setLocale(strtolower($request->language));
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
			return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    public function apply_coupon(Request $request){
    	try{  
			app()->setLocale(strtolower($request->language));
    		$current_date = date('Y-m-d');

			$coupon_code = is_null($request->promo_code)?'0':$request->promo_code;

			$promotionInfo = DB::select('SELECT id,discount FROM coupon where coupon_code = "'.$coupon_code.'" AND start_date <= "'.$current_date.'" AND end_date >= date_add("'.$current_date.'", INTERVAL 0 DAY) AND status = "1" ');
			
            $response = array();

			if($promotionInfo != null && $promotionInfo[0]->discount != "" && $promotionInfo[0]->discount != "0") {

				$promotionPer = $promotionInfo[0]->discount;

				$response['promo_id'] = $promotionInfo[0]->id;
				$response['discount'] = $promotionPer;

				Helper::logs($_POST,json_encode(['success' => 1, 'msg' =>  'Success','result' => array($response) ]));
                return json_encode(['success' => 1, 'msg' =>  'Success','result' => array($response) ]);

			}else{

				$msg = trans('word.Promo Code Invalid');
				Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
			}
	            
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }


    public function pay_shipment(Request $request){
		try{  
			app()->setLocale(strtolower($request->language));
			$shipment = Shipment::find($request->shipment_id);
	    	
	    	if($shipment != null){

		    	$shipment->payment_status = '1';
				$shipment->payment_received_by = $request->user_id;
	    	
	    		$shipment->save();

	    		$msg = trans('word.Success');
				Helper::logs($_POST,json_encode(['success' => 1, 'msg' =>  $msg,'result' => [] ]));
		        return json_encode(['success' => 1, 'msg' =>  $msg,'result' => [] ]);
	    	
	    	}else{
	    		$msg = trans('word.Shipment Not Found');
				Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
	    	}

        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

//end controller function
}
