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
use App\Review;
use App\Driver;
use App\Shipment;
use App\Shipment_bid;
use App\Goods_type;
use App\Notification;
use App\Transporter_truck;

// require './vendor/Twilio/autoload.php';

// use Twilio\Rest\Client;


class ApiUserController extends Controller
{
    
    public function refresh_token(Request $request)
    {   
        try{
            app()->setLocale(strtolower($request->language));

            $user = User::find($request->user_id);

            $filter_type = isset($request->filter_type)?$request->filter_type:'1';
        
           /* $token = JWTAuth::getToken();
            $refresh = JWTAuth::refresh($token);*/

            $update_token =  User::find($request->user_id);
            //$update_token->api_token = $refresh;  
            $update_token->current_lat = isset($request->current_lat)?$request->current_lat:'0';  
            $update_token->current_lng = isset($request->current_lng)?$request->current_lng:'0';  
            $update_token->device_token = $request->device_token; 
            $update_token->device_type = $request->device_type;
            
            // update filter type
            $update_token->filter_type = $filter_type;

            $update_token->save();

            $data1 = array();
         
            $data1['is_active'] = '0';
            $data1['request_count'] = '0';
            $data1['notification_count'] = '0';
            $data1['is_via_transfer'] = '0';
            //$data1['token'] = 'Bearer '.$refresh;
            $data1['token'] = "";
            $data1['truck_list'] = [];
            $data1['instant_quote_count'] = '0';
            $data1['fixed_shipment_count'] = '0';
            $data1['auction_shipment_count'] = '0';
            $data1['accepted_shipment_count'] = '0';
            $data1['cancelled_shipment_count'] = '0';
            $data1['total_reported_shipment_count'] = '0';
            $data1['received_offer_count'] = '0';
            $data1['total_driver'] = '0';
            $data1['pending_assign_driver_count'] = '0';
            $data1['pending_accepted_award_count'] = '0';
            $data1['total_bidded_trip_count'] = '0';
            $data1['active_shipment_list'] = [];
            
            if($user != null){

                $data1['is_active'] = $user->is_active;
                $data1['request_count'] = Helper::get_user_total_request_count($request->user_id,$filter_type,$request->timezone);
                $data1['notification_count'] = Helper::getNotificationCount($request->user_id);
                $data1['is_via_transfer'] = $update_token->payment_type;
            
            }

            if($update_token->user_type == '4'){ //Driver

                $data1['request_count'] = Helper::get_user_request_count($request->user_id,$request->timezone);

                $data1['accepted_shipment_count'] = Helper::get_user_accepted_shipment_count($request->user_id,$filter_type,$request->timezone);
                $data1['cancelled_shipment_count'] = Helper::get_user_cancelled_shipment_count($request->user_id,$filter_type,$request->timezone);
                $data1['total_reported_shipment_count'] = Helper::get_user_reported_shipment_count($request->user_id,$filter_type,$request->timezone);
                $data1['pending_accepted_award_count'] = Helper::get_pending_accepted_award_count($request->user_id,$filter_type,$request->timezone);
                $data1['total_bidded_trip_count'] = Helper::get_total_bidded_trip_count($request->user_id,$filter_type,$request->timezone);

                $filterShipmentStatus = 'shipment.status IN ("2","4","5","8","9")'; //"1",

                /*$associatedDriver =  Driver::where('driver_id', '=', $request->user_id)->first();
                if(!empty($associatedDriver)) {//Associated Driver
                    $filterShipmentStatus = 'shipment.status IN ("2","4","5","8","9")';
                }*/

                $get_active_shipment_list = DB::select(' select shipment.id as shipment_id,shipment.bid_status,shipment.status, shipment.report_emergency, shipment.status_when_report,users.first_name as user_first_name,users.last_name as user_last_name,users.profile_pic as user_profile_pic,users.mobile_no as user_mobile,shipment.id as shipment_id,users.id as user_id,shipment.updated_at,shipment.created_at,info.quotation_type, info.pickup_date
                    from shipment 
                    left join users on users.id = shipment.user_id 
                    left join shipment_info as info on info.shipment_id=shipment.id
                    left join shipment_bid as bid on bid.shipment_id = shipment.id
                    where ( '.$filterShipmentStatus.' AND shipment.driver_id = '.$request->user_id.') group by shipment.unique_id order by info.pickup_date desc limit 3 ');
                    //where ((shipment.status = "0" AND info.quotation_type = "0" AND bid.user_id = '.$request->user_id.' AND bid.status = "0" AND shipment.bid_status = "0") OR ( '.$filterShipmentStatus.' AND shipment.driver_id = '.$request->user_id.')) //Old Where Condition
                
                $response1 = array();
                  
                foreach ($get_active_shipment_list as $key => $value) {

                    $select_bidder = Shipment_bid::where('shipment_id',$value->shipment_id)->where('user_id',$request->user_id)->first();
                        
                        $data3 = array();

                        $status_color = '';
                        $status_string = '';

                        if($value->bid_status == '0' && $value->quotation_type == '0' && $value->status == "0"){
                            
                            $status_color = '#FFC70D';
                            //$status_string = trans('word.Schedule For Delivery');//OLD
                            $status_string = trans('word.Waiting For Acceptance');
                            

                            if($select_bidder != null){

                                $status_color = '#00874A';
                                $status_string = trans('word.Bidded');
                            }

                        }
                        else if($value->report_emergency != '-1' && $value->status_when_report == $value->status){
							$status_color = '#FFFF00';// '#EF5163';
							$status_string = trans('word.Reported Emergency');
						}
                        else if($value->status == '1'){
                        
                            $status_color = '#00874A';
                            $status_string = trans('word.Accepted');
                        
                        }else if($value->status == '2'){
                        
                            $status_color = '#0063C6';
                            $status_string = trans('word.On The Way');
                        
                        }else if($value->status == '4'){
                            
                            $status_color = '#00874A';
                            $status_string = trans('word.Arrived at Pickup Location');

                        }else if($value->status == '5'){
                            
                            $status_color = '#FFC70D';
                            $status_string = trans('word.Start Shipment');

                        }else if($value->status == '6'){
                        
                            $status_color = '#12D612';
                            $status_string = trans('word.Delivered');
                        
                        }else if($value->status == '8'){
                        
                            $status_color = '#00874A';
                            $status_string = trans('word.Arrived at Drop off Location');
                        }
                        else if($value->status == '9'){
                              
                            $status_color = '#00874A';
                            $status_string = trans('word.On The Way To PickUp');
                        }

                        $data3['shipment_id'] = $value->shipment_id;
                        $data3['shipper_id'] = $value->user_id;
                        $data3['shipper_name'] = is_null($value->user_first_name)?'':$value->user_first_name.' '.(is_null($value->user_last_name)?'':$value->user_last_name);
                        $data3['shipper_profile_pic'] = is_null($value->user_profile_pic)?'':$value->user_profile_pic;
                        $data3['shipper_mobile'] = is_null($value->user_mobile)?'':$value->user_mobile;
                        $data3['status_color'] = $status_color;
                        $data3['status_string'] = $status_string;
                        $data3['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $request->timezone);
                        
                        array_push($response1,$data3);   
                        
                    }

                $data1['active_shipment_list'] = $response1;

            }else if($update_token->user_type == '2'){ //Shipper

                $query = '';

                $get_truck = DB::select('select truck.*
                 from truck
                where truck.status = "1" order by truck.created_at desc ');
               
                $response = array();

                if($get_truck != null){
                    
                    foreach ($get_truck as $key => $value) {
                        
                        $data2 = array();

                        $data2['truck_id'] = $value->id;

                        if($request->language == 'fr' || $request->language == 'FR')
                        {
                            $data2['truck_name'] = ($value->truck_name_fr == null)?'':$value->truck_name_fr;

                        }
                        else if($request->language == 'ar'  || $request->language == 'AR')
                        {
                            $data2['truck_name'] = ($value->truck_name_fr == null)?'':$value->truck_name_fr;
                        }
                        else
                        {
                            $data2['truck_name'] = ($value->truck_name == null)?'':$value->truck_name;
                        }
                   
                        $data2['truck_img'] = ($value->truck_img == null)?'':$value->truck_img;
                        $data2['truck_capacity_list'] = [];
                        // dd($value);
                        $get_capacity = DB::select('select * from truck_capacity where truck_id= '.$value->id.' order by truck_capacity.created_at desc');
                        // dd($get_capacity);
                        $truck_cap = [];
                        if($get_capacity != []){
              
                          foreach($get_capacity as $gkey => $gvalue){
                            $cap = array();
                            $cap['id'] = $gvalue->id;
                            $cap['truck_capacity'] = $gvalue->truck_capacity;
                            $cap['weight_type'] = $gvalue->weight_type;   
                            array_push($truck_cap,$cap);   
                          }
                        }else{
                            $cap = array();
                            $cap['id'] = 0;
                            $cap['truck_capacity'] = $value->capacity;
                            $cap['weight_type'] = $value->weight_type;   
                            array_push($truck_cap,$cap); 
                        }

                        $data2['truck_capacity_list'] = $truck_cap;                        

                        $data2['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $request->timezone);
                        
                        
                        array_push($response,$data2);   
                    }
               }

                $data1['truck_list'] = $response;
                $data1['instant_quote_count'] = Helper::get_shipper_total_request_count($request->user_id,$filter_type,"2",$request->timezone);
                $data1['fixed_shipment_count'] = Helper::get_shipper_total_request_count($request->user_id,$filter_type,"1",$request->timezone);
                $data1['auction_shipment_count'] = Helper::get_shipper_total_request_count($request->user_id,$filter_type,"0",$request->timezone);
                $data1['accepted_shipment_count'] = Helper::get_user_accepted_shipment_count($request->user_id,$filter_type,$request->timezone);
                $data1['cancelled_shipment_count'] = Helper::get_user_cancelled_shipment_count($request->user_id,$filter_type,$request->timezone);
                $data1['total_reported_shipment_count'] = Helper::get_user_reported_shipment_count($request->user_id,$filter_type,$request->timezone);

                $data1['received_offer_count'] = Helper::get_user_received_offer_shipment_count($request->user_id,$filter_type,$request->timezone);
                

                $get_active_shipment_list = DB::select(' select shipment.status, shipment.report_emergency, shipment.status_when_report,users.first_name as user_first_name,users.last_name as user_last_name,users.profile_pic as user_profile_pic,users.mobile_no as user_mobile,shipment.id as shipment_id,users.id as user_id,shipment.updated_at,shipment.created_at
                    from shipment 
                    left join users on users.id = shipment.user_id 
                    where shipment.status IN ("2","4","5","8","9") AND shipment.user_id = '.$request->user_id.' group by shipment.unique_id order by shipment.updated_at desc limit 3 ');
                
                $response1 = array();
                  
                foreach ($get_active_shipment_list as $key => $value) {
                        
                        $data3 = array();

                        $status_color = '';
                        $status_string = '';

                        if($value->report_emergency != '-1' && $value->status_when_report == $value->status){
							$status_color = '#FFFF00';// '#EF5163';
							$status_string = trans('word.Reported Emergency');
						}
                        else if($value->status == '1'){
                        
                            $status_color = '#00874A';
                            $status_string = trans('word.Accepted');
                        
                        }else if($value->status == '2'){
                        
                            $status_color = '#0063C6';
                            $status_string = trans('word.On The Way');
                        
                        }else if($value->status == '4'){
                            
                            $status_color = '#00874A';
                            $status_string = trans('word.Arrived at Pickup Location');

                        }else if($value->status == '5'){
                            
                            $status_color = '#FFC70D';
                            $status_string = trans('word.Start Shipment');

                        }else if($value->status == '6'){
                        
                            $status_color = '#12D612';
                            $status_string = trans('word.Delivered');
                        
                        }else if($value->status == '8'){
                        
                            $status_color = '#00874A';
                            $status_string = trans('word.Arrived at Drop off Location');
                        }
                        else if($value->status == '9'){
                              
                            $status_color = '#00874A';
                            $status_string = trans('word.On The Way To PickUp');
                        }

                        $data3['shipment_id'] = $value->shipment_id;
                        $data3['shipper_id'] = $value->user_id;
                        $data3['shipper_name'] = is_null($value->user_first_name)?'':$value->user_first_name.' '.(is_null($value->user_last_name)?'':$value->user_last_name);
                        $data3['shipper_profile_pic'] = is_null($value->user_profile_pic)?'':$value->user_profile_pic;
                        $data3['shipper_mobile'] = is_null($value->user_mobile)?'':$value->user_mobile;
                        $data3['status_color'] = $status_color;
                        $data3['status_string'] = $status_string;
                        $data3['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $request->timezone);
                        
                        array_push($response1,$data3);   
                        
                    }

                $data1['active_shipment_list'] = $response1;
            
            }else if($update_token->user_type == '3'){ //Transporter

                $data1['accepted_shipment_count'] = Helper::get_user_accepted_shipment_count($request->user_id,$filter_type,$request->timezone);
                $data1['cancelled_shipment_count'] = Helper::get_user_cancelled_shipment_count($request->user_id,$filter_type,$request->timezone);
                $data1['total_reported_shipment_count'] = Helper::get_user_reported_shipment_count($request->user_id,$filter_type,$request->timezone);
                $data1['total_driver'] = Helper::get_user_total_driver($request->user_id);
                $data1['pending_assign_driver_count'] = Helper::get_pending_assign_driver_count($request->user_id,$filter_type,$request->timezone);
                $data1['pending_accepted_award_count'] = Helper::get_pending_accepted_award_count($request->user_id,$filter_type,$request->timezone);
                $data1['total_bidded_trip_count'] = Helper::get_total_bidded_trip_count($request->user_id,$filter_type,$request->timezone);

                $get_active_shipment_list = DB::select(' select shipment.id as shipment_id,shipment.bid_status,shipment.status, shipment.report_emergency, shipment.status_when_report,users.first_name as user_first_name,users.last_name as user_last_name,users.profile_pic as user_profile_pic,users.mobile_no as user_mobile,shipment.id as shipment_id,users.id as user_id,shipment.updated_at,shipment.created_at,info.quotation_type, info.pickup_date
                    from shipment 
                    left join users on users.id = shipment.user_id 
                    left join shipment_info as info on info.shipment_id=shipment.id
                    left join shipment_bid as bid on bid.shipment_id = shipment.id
                    where (shipment.status IN ("2","4","5","8","9") AND shipment.transporter_id = '.$request->user_id.') group by shipment.unique_id order by info.pickup_date desc limit 3 ');
                    //where ((shipment.status = "0" AND info.quotation_type = "0" AND bid.user_id = '.$request->user_id.' AND bid.status = "0" AND shipment.bid_status = "0") OR (shipment.status IN ("2","4","5","8","9") AND shipment.transporter_id = '.$request->user_id.')) //OLD Where Condition
                
                $response1 = array();
                  
                foreach ($get_active_shipment_list as $key => $value) {

                    $select_bidder = Shipment_bid::where('shipment_id',$value->shipment_id)->where('user_id',$request->user_id)->first();
                        
                        $data3 = array();

                        $status_color = '';
                        $status_string = '';

                        if($value->bid_status == '0' && $value->quotation_type == '0' && $value->status == "0"){
                            
                            $status_color = '#FFC70D';
                            //$status_string = trans('word.Schedule For Delivery');//OLD
                            $status_string = trans('word.Waiting For Acceptance');                            

                            if($select_bidder != null){

                                $status_color = '#00874A';
                                $status_string = trans('word.Bidded');
                            }

                        }
                        else if($value->report_emergency != '-1' && $value->status_when_report == $value->status){
							$status_color = '#FFFF00';// '#EF5163';
							$status_string = trans('word.Reported Emergency');
						}
                        else if($value->status == '1'){
                        
                            $status_color = '#00874A';
                            $status_string = trans('word.Accepted');
                        
                        }else if($value->status == '2'){
                        
                            $status_color = '#0063C6';
                            $status_string = trans('word.On The Way');
                        
                        }else if($value->status == '4'){
                            
                            $status_color = '#00874A';
                            $status_string = trans('word.Arrived at Pickup Location');

                        }else if($value->status == '5'){
                            
                            $status_color = '#FFC70D';
                            $status_string = trans('word.Start Shipment');

                        }else if($value->status == '6'){
                        
                            $status_color = '#12D612';
                            $status_string = trans('word.Delivered');
                        
                        }else if($value->status == '8'){
                        
                            $status_color = '#00874A';
                            $status_string = trans('word.Arrived at Drop off Location');
                        }
                        else if($value->status == '9'){
                              
                            $status_color = '#00874A';
                            $status_string = trans('word.On The Way To PickUp');
                        }

                        $data3['shipment_id'] = $value->shipment_id;
                        $data3['shipper_id'] = $value->user_id;
                        $data3['shipper_name'] = is_null($value->user_first_name)?'':$value->user_first_name.' '.(is_null($value->user_last_name)?'':$value->user_last_name);
                        $data3['shipper_profile_pic'] = is_null($value->user_profile_pic)?'':$value->user_profile_pic;
                        $data3['shipper_mobile'] = is_null($value->user_mobile)?'':$value->user_mobile;
                        $data3['status_color'] = $status_color;
                        $data3['status_string'] = $status_string;
                        $data3['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $request->timezone);
                        
                        array_push($response1,$data3);   
                        
                    }

                $data1['active_shipment_list'] = $response1;
                    
            }
            
            Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' =>  array($data1) ]));   
              return response()->json([
                    'success' => 1,
                    'msg' => 'success',
                    'result' => array($data1)
                ]);

            }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' =>  $ex->getLine().''.$ex->getMessage(), 'result' => [] ]);
            }
    }

    public function register(Request $request){

        $createdUserID = "0";

        try{    
            app()->setLocale(strtolower($request->language));
                
            $first_name = $request->first_name;
            $last_name = $request->last_name;
            $user_type = $request->user_type; //2=shipper,3=transporter,4=driver
            $email = $request->email;
            $password =  Hash::make($request->password);    
            $verification_code = rand(1111,9999);

            //Added By Mehul Base On Client Feedback
            if($user_type == "4" && $email == "") {//Driver
                $email = '0'.ltrim($request->mobile_no, "0").'@mobile.com';
            }
            
            //Check Email Is Exist Or Not
            $check_participate = User::where('email', '=', $email)->where('status', '!=',"2")->first();
            
            if(is_null($check_participate)) {

                $checkMobileExist = User::where('mobile_no', '=', ("0".ltrim($request->mobile_no, "0")) )->where('status', '!=',"2")->first();

                if(!is_null($checkMobileExist)) {
                    $msg=trans('word.Mobile number Already Exists');          
                    
                    $_POST['password'] = "********";
                    Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                    return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
                }
            }

            if(is_null($check_participate)){
                  
                /*
                try{

                    $sid = env('ACCOUNT_SID');
                    $token = env('AUTH_TOKEN');  
                    $twilio = new Client($sid, $token);
                    
                    $message = $twilio->messages->create($request->country_code.$request->mobile_no, // to
                        [
                                "body" => "KMIOU Verification Code :: ".$verification_code,
                                "from" => "+14086693128"
                        ]
                    );

                }catch(Exception $ex){
                    Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                    return json_encode(['success' => 0, 'msg' => trans('word.Please enter valid mobile number'),'err' => $ex->getMessage(), 'result' => [] ]);
                }
                */
                
                //Remove OPT Future
                //Helper::sendSMS($request->country_code.(ltrim($request->mobile_no, "0")), "KMIOU Verification Code:".$verification_code);

                $user  = new User;

                $get_city = DB::table('city')->where('id',$request->city)->first();
                
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->email = $email;
                $user->user_type = $user_type;
                $user->password = $password;
                $user->city = isset($get_city)?trim(substr($get_city->city_name, strpos($get_city->city_name, '-') + 1)):null;
                $user->mobile_no = is_null($request->mobile_no)?null:("0".ltrim($request->mobile_no, "0"));
                $user->verification_code = $verification_code;
                $user->equipment_use = is_null($request->equipment_use)?0:$request->equipment_use;
                $user->company_name = isset($request->company_name)?$request->company_name:null;
                $user->operated_equipment_type = is_null($request->operated_equipment_type)?0:$request->operated_equipment_type;
                $user->truck_count = is_null($request->truck_count)?0:$request->truck_count;
                $user->shipment_per_month = is_null($request->shipment_per_month)?0:$request->shipment_per_month;
                $user->shipping_city = is_null($request->shipping_city)?0:$request->shipping_city;
                $user->headquarters_city = is_null($request->headquarters_city)?0:$request->headquarters_city;
                $user->language = is_null($request->language)?0:$request->language;
                $user->ref_id = is_null($request->user_id)?0:$request->user_id;
                $user->carrier_number = is_null($request->carrier_number)?null:$request->carrier_number;
                $user->doc = is_null($request->doc)?null:$request->doc;
                $user->owner_id_doc = is_null($request->owner_id_doc)?null:$request->owner_id_doc;             
                $user->shipper_type = $request->register_as; // 0: individual , 1: Company (this is only for shipper so other case it will be  by default 0)
                $user->country_code = $request->country_code;
                $user->is_verify = $user_type == "4" ? '1' : '0'; //Driver Autometically Verified

                if($user_type == "2" && $request->register_as == "0") {
                    $user->status = '1'; 
                    $user->approve = '1'; 
                } else {
                    $user->status = '0'; 
                    $user->approve = '0'; 
                }

                if($user_type == '2'){
                    
                    $user->truck_count = 0;
                    $user->shipment_per_month = is_null($request->truck_count)?0:$request->truck_count;
                }

                //Remove OPT Future
                // $user->is_verify = '1';
                // $user->approve = '1'; 
                $user->email_verified_at = date('Y-m-d H:i:s');
                if($user_type == '2'){
                    $user->status = '1';
                }

                $user->save();

                $createdUserID = $user->id;
                

                if($request->user_type == '3'){

                    if(isset($request->truck_type) && $request->truck_type != ''){
                    
                        $truck_type = explode(',', $request->truck_type);
                    
                        foreach ($truck_type as $key => $value) {
                            
                            $truck_type = new Transporter_truck;
                            
                            $truck_type->user_id = $user->id; 
                            $truck_type->truck_id = $value; 
                            $truck_type->status = '1'; 
                            
                            $truck_type->save(); 
                        }
                    }
                }else if($request->user_type == '4'){

                    $truck_type = new Transporter_truck;
                            
                    $truck_type->user_id = $user->id; 
                    $truck_type->truck_id = $request->truck_type; 
                    $truck_type->status = '1'; 
                    
                    $truck_type->save(); 
                }

                $credentials = array('email' => $email,'password' => $request->password,'user_type' => $user_type);
                
                $api_token = JWTAuth::attempt($credentials);

                if (! $api_token = JWTAuth::attempt($credentials)) {

                    $_POST['password'] = "********";
                    Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Invalid Login','result' =>[] ]));    
                    return json_encode(['success' => 0, 'msg' =>  trans('word.Invalid Login'),'result' => []]);
                }
                
                //update user
                $update_user = User::find($user->id);
                $update_user->api_token = $api_token;
                $update_user->device_type = $request->device_type;
                $update_user->device_token = $request->device_token;
                $update_user->udid = $request->udid;
                $update_user->save();


                Mail::send('emails.verification_link', ['user' => $user], function($message) use ($user) {
                    $message->from(env('MAIL_USERNAME'), 'KMIOU');
                    $message->to($user->email);
                    $message->subject('KMIOU Verification Code');
                });

                $user_type = '';
                if($request->user_type = '2'){

                    $user_type = trans('word.Shipper');
                
                }else if($request->user_type = '3'){

                    $user_type = trans('word.transporter');
                
                }else if($request->user_type = '4'){

                    $user_type = trans('word.driver');
                }

                $user_name = is_null($user->first_name)?'':$user->first_name.' '.(is_null($user->last_name)?'':$user->last_name);

                // new user mail to admin
                $user_detail2 =array();
                $user_detail2['email'] = $user->email;
                $user_detail2['user_type'] = $user_type;
                $user_detail2['user_name'] = $user_name;
                $user_detail2['email'] = $user->email;
                $user_detail2['date'] = date("Y-m-d H:i", strtotime('+60 minutes'));


                Mail::send('emails.new_user_info', ['user' => (object)$user_detail2], function($message) use ($user) {
                    $message->from(env('MAIL_USERNAME'), 'KMIOU');
                    $message->to(env('MAIL_ADMIN'));
                    $message->subject('KMIOU NEW USER');
                });

                $response = array();
                $response['user_id'] = $user->id;
                $response['user_name'] = is_null($user->first_name)?'':$user->first_name.' '.(is_null($user->last_name)?'':$user->last_name);
                $response['email'] = is_null($user->email)?'':$user->email;
                $response['mobile_no'] = is_null($user->mobile_no)?'':$user->mobile_no;        
                $response['register_as'] = is_null($user->shipper_type)?'0':$user->shipper_type; // 0: individual , 1: Company (this is only for shipper so other case it will be  by default 0)
                $response['country_code'] = is_null($user->country_code)?'':$user->country_code; 
                $response['user_type'] = is_null($user->user_type)?'':$user->user_type;
                $response['token'] = is_null($api_token)?'':'Bearer '.$api_token;
                $response['is_approved'] = is_null($user->approve)?'0':$user->approve;
                if($response['user_type'] == "4") {
                    $response['is_need_otp_verification'] = "0";
                } else {
                    $response['is_need_otp_verification'] = is_null($user->email) || $user->email == "" ? "0" : "1";
                }


                $msg = ($response['is_approved'] == "0" && $response['is_approved'] == "0") ? trans('word.Thanks for registration we will contact you soon') : trans('word.Verification code sent in email');

                $_POST['password'] = "********";
                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => array($response) ]));
                return json_encode(['success' => 1, 'msg' => $msg,'result' => array($response) ]);
            }
            else
            {
                if($createdUserID != "0") {
                    $createdUser = User::find($createdUserID);
                    if(!empty($createdUser)) {
                        $createdUser->delete();
                    }
                }

                $msg=trans('word.Email Already Exists');          
                
                $_POST['password'] = "********";
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
            }
        }catch(Exception $ex) {
                
            if($createdUserID != "0") {
                $createdUser = User::find($createdUserID);
                if(!empty($createdUser)) {
                    $createdUser->delete();
                }
            }

            $_POST['password'] = "********";
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    public function activateuser(Request $request){
        try{
            app()->setLocale(strtolower($request->language));
            //Get User By verification code
            $user_data = User::where('verification_code',$request->verification_code)->first();
            if(!empty($user_data)){

                    //Update User Status As Active

                $user = User::find($user_data->id);

                $user->verification_code = null;
                $user->is_verify = '1';
                $user->email_verified_at = date('Y-m-d H:i:s');
             
                if($user->user_type == '2'){
                    $user->status = '1';
                }

                $user->save();

                $data = array();
                
                $data['user_id'] = is_null($user->id)?'':$user->id;
                $data['user_name'] = is_null($user->first_name)?'':$user->first_name.' '.(is_null($user->last_name)?'':$user->last_name);
                $data['profile_pic'] = is_null($user->profile_pic)?'':$user->profile_pic;
                $data['email'] = is_null($user->email)?'':$user->email;
                $data['is_approved'] = is_null($user->approve)?'':$user->approve;
                $data['user_type'] = is_null($user->user_type)?'':$user->user_type;

                $associatedDriver =  Driver::where('driver_id', '=', $user->id)->first();
                $data['is_associated_driver'] = !empty($associatedDriver) ? "1" : "0";

                $data['token'] =  'Bearer '.$user->api_token;
                
                $msg = (is_null($user->approve)?'0':$user->approve) == "1" ? trans('word.Verified Successfully') : trans('word.Thanks for registration we will contact you soon');
                 
                 Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg, 'result'=> array($data) ]));
                 return json_encode(['success' => 1, 'msg' => $msg, 'result'=> array($data) ]);
     
            }else{

             Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Invalid Verfication code', 'result'=>[]]));
              return json_encode(['success' => 0, 'msg' => trans('word.Invalid Verification code'),'result' =>[] ]);
            }
        }catch(Exception $ex) {
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }

    }

    public function resendcode(Request $request){
        
        try{
            app()->setLocale(strtolower($request->language));
            $user = User::find($request->user_id);
            
            if($user != null)
            {
                if($user->is_verify == '0')
                {
                    $verification_code = rand(1111,9999);

                    $user_update = User::find($request->user_id);

                    $user_update->verification_code = $verification_code;
                    $user_update->save();

                    $user_detail =array();
                    $user_detail['verification_code'] = $verification_code;
                    $user_detail['first_name'] = $user->first_name;
                    $user_detail['email'] = $user->email;
                    /*
                    try{
                        $sid = env('ACCOUNT_SID');
                        $token = env('AUTH_TOKEN');                    
                        $twilio = new Client($sid, $token);

                        $message = $twilio->messages
                            ->create($user->country_code.$user->mobile_no, // to
                            [
                                    "body" => "KMIOU Verification Code :: ".$verification_code,
                                    "from" => "+14086693128"
                            ]
                        );
                    }catch(Exception $ex){
                        Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                        return json_encode(['success' => 0, 'msg' => trans('word.Please enter mobile number'),'err' => $ex->getMessage(), 'result' => [] ]);
                    }
                    */
                    
                    //Remove OPT Future
                    //Helper::sendSMS($user->country_code.(ltrim($user->mobile_no, "0")), "KMIOU Verification Code:".$verification_code);
                    
                    Mail::send('emails.verification_link', ['user' => (object)$user_detail], function($message) use ($user) {
                        $message->from(env('MAIL_USERNAME'), 'KMIOU');
                        $message->to($user->email);
                        $message->subject('KMIOU Verification Code');
                    });
                     
                    Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Verification code sent in email : '.$verification_code,'result' => []]));
                    return json_encode(['success' => 1, 'msg' => trans('word.Verification code sent in email : '.$verification_code),'result' => []]);
                }
                else
                {    Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Account Already verified please login ! ','result' =>[] ]));
                    return json_encode(['success' => 0, 'msg' => trans('word.Account Already verified please login !'),'result' =>[] ]);

                }
            }
            else
            {    Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'User Not Found !! ','result' =>[] ]));
                return json_encode(['success' => 1, 'msg' => trans('word.User Not Found') ,'result' =>[] ]);
            }
        } catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

	public function login(Request $request)
    {
        try{
            app()->setLocale(strtolower($request->language));

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'password'=> 'required',
            ]);

            $request->request->add(['user_type' => ['2','3','4']]); //add request

            if ($validator->fails()) {
                // $request->request->add(['email' => ('0'.ltrim($request->email, "0").'@mobile.com')]); //add request //OLD
                //return json_encode(['success' =>0, 'msg' => trans('word.The email must be a valid email address'),'result' =>[] ]);

                $request->request->add(['email' => ('0'.ltrim($request->email, "0"))]); //add request
            }
            
            if(strpos($request->email, "@") > -1) {
                $check_user = DB::table('users')->where('email', '=', $request->email)->where('status','!=',"2")->orderBy('id','desc')->first();
            } 
            else {
                $check_user = DB::table('users')->where('mobile_no', '=', $request->email)->where('status','!=',"2")->orderBy('id','desc')->first();
            }

            if(is_null($check_user))
            {   
                $_POST['password'] = '*********';

                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'User Not Found','result' =>[] ]));
                return json_encode(['success' =>0, 'msg' => trans('word.User Not Found'),'result' =>[] ]);

            }else if($check_user != null && $check_user->approve == '0' && $check_user->is_verify == '1'){

                $_POST['password'] = '*********';

                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Your Account is not approved yet by admin','result' =>[] ]));
                return json_encode(['success' =>0, 'msg' => trans('word.Your Account is not approved yet by admin'),'result' =>[] ]);
            
            }else if($check_user != null && $check_user->approve == '2'){

                $_POST['password'] = '*********';

                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Account Rejected For Admin Approval','result' =>[] ]));
                return json_encode(['success' =>0, 'msg' => trans('word.Account Rejected For Admin Approval'),'result' =>[] ]);
            }else{
                if(strpos($request->email, "@") <= -1) {
                    $request->request->add(['email' => $check_user->email]);
                }

                $credentials = $request->only('email', 'password','user_type');
                if (! $token = JWTAuth::attempt($credentials)) {
                    $_POST['password'] = '*********';
                    Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Invalid Login','result' =>[] ]));    
                    return json_encode(['success' => 0, 'msg' =>  trans('word.Invalid password'),'result' => []]);
                }            

                //$user = Auth::user();
                $user = $check_user;
                $insert_token =  User::where('id', '=', $user->id)->first();
                $insert_token->api_token = $token; 
                $insert_token->device_token = $request->device_token; 
                $insert_token->device_type = $request->device_type;
                $insert_token->udid = $request->udid;

                if($insert_token->email_verified_at == null){

                    $insert_token->email_verified_at = date('Y-m-d H:m:s');
                }

                $insert_token->save();
        
                $response['user_id'] = $user->id;
                $response['user_name'] = is_null($user->first_name)?'':$user->first_name.' '.(is_null($user->last_name)?'':$user->last_name);
                $response['email'] = is_null($user->email)?'':$user->email;
                $response['mobile_no'] = is_null($user->mobile_no)?'':$user->mobile_no;                
                $response['register_as'] = is_null($user->shipper_type)?'0':$user->shipper_type; // 0: individual , 1: Company (this is only for shipper so other case it will be  by default 0)
                $response['country_code'] = is_null($user->country_code)?'':$user->country_code; 
                $response['profile_pic'] = is_null($user->profile_pic)?'':$user->profile_pic;
                $response['user_type'] = is_null($user->user_type)?'':$user->user_type;
                $response['is_verified'] = 1; //is_null($user->is_verify)?'0':$user->is_verify;
                $response['address'] = (is_null($user->address)?'':$user->address).''.(is_null($user->city)?'':', '.$user->city).''.(is_null($user->state)?'':', '.$user->state).''.(is_null($user->zipcode)?'':' - '.$user->zipcode);

                $associatedDriver =  Driver::where('driver_id', '=', $user->id)->first();
                $response['is_associated_driver'] = !empty($associatedDriver) ? "1" : "0";

                $response['token'] = 'Bearer '.$token;

                $msg = trans('word.Login successfully');
                
                if($user->is_verify == '0'){

                    $msg = trans('word.Verification code sent in Mail');

                    $verification_code = rand(1111,9999);

                    $user_update = User::find($user->id);

                    $user_update->verification_code = $verification_code;
                    $user_update->save();

                    /*
                    try{
                        $sid = env('ACCOUNT_SID');
                        $token = env('AUTH_TOKEN');                    
                        $twilio = new Client($sid, $token);

                        $message = $twilio->messages
                            ->create($user->country_code.$user->mobile_no, // to
                            [
                                    "body" => "KMIOU Verification Code :: ".$verification_code,
                                    "from" => "+14086693128"
                            ]
                        );
                    }catch(Exception $ex){
                        Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                    }
                    */

                    //Remove OPT Future
                    //Helper::sendSMS($user->country_code.(ltrim($user->mobile_no, "0")), "KMIOU Verification Code:".$verification_code);

                    $user_detail =array();
                    $user_detail['verification_code'] = $verification_code;
                    $user_detail['first_name'] = $user->first_name;
                    $user_detail['email'] = $user->email;


                    Mail::send('emails.verification_link', ['user' => (object)$user_detail], function($message) use ($user) {
                        $message->from(env('MAIL_USERNAME'), 'KMIOU');
                        $message->to($user->email);
                        $message->subject('KMIOU Verification Code');
                    });
                }

                $_POST['password'] = '*********';

                Helper::logs($_POST,json_encode(['success' => 1, 'msg' =>  $msg,'result' => [$response] ])); 
                return json_encode(['success' => 1, 'msg' =>  $msg,'result' => [$response] ]);
            
            }

        }catch(Exception $ex) {
                
                $_POST['password'] = '*********';

                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
       
    }

    public function logout(Request $request){
    try{    
        app()->setLocale(strtolower($request->language));

            $user_id=$request->user_id;     
            
            //Remove Token
            $user = User::find($user_id);

            $user->device_token = "";
            $user->save();

            if(JWTAuth::getToken())
            {
                //expire jwt token
                JWTAuth::invalidate(JWTAuth::getToken());
            }
        } 
        catch(Exception $ex) {        
        }

        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' =>[] ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' =>[] ]);
    }

    public function get_profile(Request $request){

        try {
            app()->setLocale(strtolower($request->language));
                $user_id = $request->user_id;
                
                $other_user_id = $request->other_user_id;
                
                if($request->other_user_id == '0'){

                    $user = User::find($user_id);
                }else{

                    $user = User::find($other_user_id);
                }
                echo 'ccc';
                if(!empty($user))
                {
                    $data =array();
                    $data['user_id'] = $user->id;
                    $data['first_name'] = is_null($user->first_name)?'':$user->first_name;
                    $data['last_name'] = is_null($user->last_name)?'':$user->last_name;
                    $data['email'] = is_null($user->email)?'':$user->email;
                    $data['profile_pic'] = is_null($user->profile_pic)?'':$user->profile_pic;
                    $data['user_type'] = is_null($user->user_type)?'':$user->user_type;
                    $data['mobile_no'] = is_null($user->mobile_no)?'':$user->mobile_no;                          
                    $data['register_as'] = is_null($user->shipper_type)?'0':$user->shipper_type; // 0: individual , 1: Company (this is only for shipper so other case it will be  by default 0)
                    $data['country_code'] = is_null($user->country_code)?'':$user->country_code; 
                    $data['company_name'] = is_null($user->company_name)?'':$user->company_name;
                    $data['carrier_number'] =is_null($user->carrier_number)?'':$user->carrier_number;
                    $data['no_of_vehicle'] =is_null($user->no_of_vehicle)?'0':$user->no_of_vehicle;
                    $data['doc'] = is_null($user->doc)?'':$user->doc;
                    $data['owner_id_doc'] = is_null($user->owner_id_doc)?'':$user->owner_id_doc;
                    $data['address'] = is_null($user->address)?'':$user->address;

                    $select_truck = DB::select(' select transporter_truck.status as truck_status,truck.id as truck_id,truck.* 
                        from transporter_truck
                        left join truck on truck.id=transporter_truck.truck_id
                        where transporter_truck.user_id = '.$user->id.' AND transporter_truck.status != "2" order by created_at desc limit 1'); 
                    if($select_truck != null){

                        $data['truck_id'] = $select_truck[0]->truck_id;
                        $data['truck_name'] = ($select_truck[0]->truck_name == null)?'':$select_truck[0]->truck_name.' - '.(($select_truck[0]->capacity == null)?'1':$select_truck[0]->capacity).' '.(($select_truck[0]->weight_type == '0')?'Kg':'Ton');
                        $data['truck_img'] = ($select_truck[0]->truck_img == null)?'':$select_truck[0]->truck_img;
                        $data['truck_status'] = ($select_truck[0]->truck_status == null)?'':$select_truck[0]->truck_status;
                    }else{

                        $data['truck_id'] = '';
                        $data['truck_name'] = '';
                        $data['truck_img'] = '';
                        $data['truck_status'] = '';                        
                    }
                 

                    Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' =>array($data) ]));  
                    return json_encode(['success' => 1, 'msg' => 'Success','result' =>array($data) ]);  
                }
                else
                {
                    Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'No User found','result' => []])); 
                    return json_encode(['success' => 0, 'msg' => trans('word.No User found'),'result' => []]); 
                }
            } catch(Exception $ex) {
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
            }
    }

    public function forgot_password(Request $req)
    {
            try{
                app()->setLocale(strtolower($req->language));
      $forgot = DB::table('users')->where('email','=',$req->email)->first();
                
             if($forgot)
             {
                 $forgot_token = Str::random(8);
                 $link = url('/').'/reset_password/'.$forgot->id.'/'.$forgot_token;
                 
                 $fo=User::find($forgot->id);
                 $fo->forgot_token=$forgot_token;
                 $fo->save();

                 $data = array();
                 $data['link'] = $link;
                 $data['first_name'] = $fo->first_name;
                 $data['email'] = $fo->email;


                  Mail::send('emails.reset_email', ['user' => (object)$data], function($message) use ($data) {

                            $message->from(env('MAIL_USERNAME'), 'KMIOU');
                            $message->to($data['email']);
                            $message->subject('KMIOU Reset Password');
                        });



                return json_encode(['success' => 1, 'msg' => trans('word.Reset password link sent in your mail'),'result' => [] ]);           
            }
            else{
                return json_encode(['success'=>0, 'msg' => trans('word.email not found'),'result'=>[]]);
            }
            }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
            }
    }

    
    public function edit_profile(Request $req)
    {   
    try{
        app()->setLocale(strtolower($req->language));
            $rules=[
                'first_name'=>'required',
                'last_name'=>'required',
                'mobile_no'=>'required'
            ];
            $validator=Validator::make($req->all(),$rules);
            
            if($validator->fails()){ 
                return json_encode(['success' => 0, 'msg' => $validator->errors(),'result' => []]);
            
            }else{

              $sel = DB::table('users')->where('email','=',$req->email)->where('id','!=',$req->user_id)->where('status','!=',"2")->first();   
              $sel2 = DB::table('users')->where('mobile_no','=',$req->mobile_no)->where('id','!=',$req->user_id)->where('status','!=',"2")->first();   
            
              /* Comment By Mehul
              if($sel){
                 return json_encode(['success' => 0, 'msg' => trans('word.email is already taken'),'result' => []]);

                }else if($sel2){
                 return json_encode(['success' => 0, 'msg' => trans('word.mobile number is already taken'),'result' => []]);
               }else{*/
                $edit_profile =User::find($req->user_id);
                
                $edit_profile->first_name=$req->first_name;
                $edit_profile->last_name=$req->last_name;
                $edit_profile->profile_pic=$req->profile_pic;
                $edit_profile->mobile_no=$req->mobile_no;
                $edit_profile->address=$req->address;
                $edit_profile->company_name= $req->company_name;
                $edit_profile->carrier_number= $req->carrier_number;
                $edit_profile->no_of_vehicle= $req->no_of_vehicle;
                $edit_profile->doc= $req->doc;
                $edit_profile->owner_id_doc= $req->owner_id_doc;                   
                //$user->shipper_type = $request->register_as; // 0: individual , 1: Company (this is only for shipper so other case it will be  by default 0)
                $edit_profile->country_code = $req->country_code;

                
                    if($edit_profile->save())
                    {
                        return json_encode(['success' => 1, 'msg' => trans('word.Profile Updated'),'result' => []]);
                    }
                    else{
                        return json_encode(['success'=>0, 'msg' => 'error','result'=>[]]);
                    }
                //}
            }
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    public function change_password(Request $req)
    {
    try{
        app()->setLocale(strtolower($req->language));
         $change = DB::table('users')->where('id','=',$req->user_id)->first();

            if($change)
            {

                if(Hash::check($req->old_password,$change->password))
                {
                      $change_password = User::find($req->user_id);
                     
                      $change_password->password=Hash::make($req->new_password); 
                      $change_password->save();
                        return json_encode(['success' => 1, 'msg' =>trans('word.password changed successfully'),'result' => []]);   
                }
                else{
                    return json_encode(['success'=> 0,'msg' => trans('word.old password is wrong'),'result' => []]);
                 }

             }
             else{
                return json_encode(['success'=> 0,'msg' => trans('word.User Not Found'),'result' => []]);
             }
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }

    }


    public function delete_review(Request $request){

    try{  
        app()->setLocale(strtolower($request->language));
            $review = Review::where('id','=',$request->review_id)->where('user_id','=',$request->user_id)->where('status','!=','2')->first();

            if($review != null){

                $review->delete();
              
                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Review Removed Successfully','result' => [] ]));
                return json_encode(['success' => 1, 'msg' => trans('word.Review Removed Successfully'),'result' => [] ]);

            }

            else
            {
                $msg=trans('word.Review not Found');          
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
            }
            
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }


    /* card apis */        

    public function add_new_card(Request $request){

        try{  
            app()->setLocale(strtolower($request->language));
            $check_user = User::where('id', '=', $request->user_id)->where('status', '=', '1')->first();

            if($check_user != null){

                $user_card  = ($request->card_id == '0')?new Card:Card::find($request->card_id);
                
                $user_card->user_id = $request->user_id;
                $user_card->card_no = $request->card_no;
                $user_card->holder_name = $request->holder_name;
                $user_card->expiry_month = $request->expiry_month; // (1-12 January-December)
                $user_card->expiry_year = $request->expiry_year;
                $user_card->cvv = $request->cvv;
                $user_card->save();

                $msg = ($request->card_id == '0')?trans('word.New Card Added Successfully'):trans('word.Card Updated Successfully');
              
                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
                return json_encode(['success' => 1, 'msg' => $msg,'result' => []]);

            }
            else
            {
                $msg=trans('word.User not Verified Yet');          
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
            }
            
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }


    public function user_card_list(Request $request){

        try{
            app()->setLocale(strtolower($request->language));
            $check_user = User::where('id', '=', $request->user_id)->where('status', '=', '1')->where('is_verify', '=', '1')->first();
        
            if(!empty($check_user))
            {
                $card = DB::select('SELECT users_card.* from users_card where user_id = '.$request->user_id.' AND status != "2" order by created_at desc ');
            
                if(count($card)>0)
                {
                    $response = array();
                    foreach ($card as $key => $value) {
                       
                       $data = array();
                       
                       $data['card_id'] = $value->id;
                       $data['user_id'] = $value->user_id;
                       $data['card_no'] = is_null($value->card_no)?'':$value->card_no;
                       $data['holder_name'] = is_null($value->holder_name)?'':$value->holder_name;
                       $data['expiry_month'] = is_null($value->expiry_month)?'':$value->expiry_month;
                       $data['expiry_year'] = is_null($value->expiry_year)?'':$value->expiry_year;
                       $data['cvv'] = is_null($value->cvv)?'':$value->cvv;
                       $data['created_at'] =  $value->created_at;
                       
                       array_push($response, $data);
                    }

                     Helper::logs($_POST,json_encode(['success' => 1, 'msg' => trans('word.Users Card listed Successfully !!'),'result' => $response ]));
                    return json_encode(['success' => 1, 'msg' => trans('word.Users Card listed Successfully !!'),'result' =>$response ]);
                }else{
                    Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Users Card List is Empty !!','result' =>[]]));
                    return json_encode(['success' => 1, 'msg' => trans('word.Users Card List is Empty !!'),'result' =>[]]);
                } 
            
            }else{
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'User not Found !!','result' =>[]]));
                return json_encode(['success' => 0, 'msg' => trans('word.User Not Found'),'result' =>[]]);
            }
        }catch(Exception $ex) {
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    public function delete_card(Request $request){

    try{  
        app()->setLocale(strtolower($request->language));
            $card = Card::where('id','=',$request->card_id)->where('user_id','=',$request->user_id)->first();

            if($card != null){

                $card->status = '2';
                $card->save();
                

                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Card Removed Successfully','result' => [] ]));
                return json_encode(['success' => 1, 'msg' => trans('word.Card Removed Successfully'),'result' => [] ]);

            }

            else
            {
                $msg=trans('Card not Found');          
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
            }
            
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    /* End card apis */  

    // change online active status
    public function update_online_status(Request $request)
    {   
    try{
        app()->setLocale(strtolower($request->language));
            $check_user = User::find($request->user_id);
            
            if($check_user != null)
            {   
                $check_user->is_active = $request->is_active;
                
                $check_user->save();

                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Status Updated Successfully !!','result' => [] ]));
                return json_encode(['success' => 1, 'msg' => trans('word.Status Updated Successfully !!'),'result' => [] ]);

            }else{

                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'User not found','result' => []]));
                return json_encode(['success' => 0, 'msg' => trans('word.User Not Found'),'result' => []]);
            }

        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }


    public function change_profile_pic(Request $req)
    {
        try{
            app()->setLocale(strtolower($req->language));
        $rules=[
        'profile_pic'=>'required',
        ];
        $validator=Validator::make($req->all(),$rules);

        if($validator->fails()){
            return json_encode(['success' => 0, 'msg' => $validator->errors(),'result' => []]);

        }else{

            $sel = DB::select('select * from users where id = "'.$req->user_id.'" order By created_at desc');

            if($sel){
                $edit_profile =User::find($req->user_id);

                $edit_profile->profile_pic=$req->profile_pic;

                if($edit_profile->save())
                {
                return json_encode(['success' => 1, 'msg' => trans('word.Profile Pic Updated'),'result' => []]);
                }
                else{
                return json_encode(['success'=>0, 'msg' => 'error','result'=>[]]);
                }
            }
            else
            {
                return json_encode(['success' => 0, 'msg' => trans('word.User Not Found'),'result' => []]);
            }
        }
        }catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }

    }

        
    public function notification_list(Request $request){

    try{
            app()->setLocale(strtolower($request->language));

            $user_exists = User::find($request->user_id);
            
            if(!empty($user_exists))
            {
                
                $notification = DB::select('SELECT notification.* from notification where notification.to_user_id = '.$request->user_id.' order by created_at desc ');
            
                //Read All Notification
                DB::statement("UPDATE notification SET is_read = '1' WHERE to_user_id = ".$request->user_id);

                if(count($notification)>0)
                {
                    $response = array();

                    foreach ($notification as $key => $value) {

                      $from_user = User::find($value->from_user_id);
                     

                       $data = array();
                       $data['id'] = $value->id;
                       $data['from_user_id'] = $value->from_user_id;
                       $data['to_user_id'] = $value->to_user_id;
                       $data['user_name'] = is_null($from_user->first_name)?'':$from_user->first_name.' '.(is_null($from_user->first_name)?'':$from_user->first_name);
                        

                       if(strtolower($request->language) == 'fr')
                       {
                         $data['message'] = $value->message_fr;
                         $data['title'] = $value->title_fr;
                       }                       
                       else if(strtolower($request->language) == 'ar')
                       {
                        $data['message'] = $value->message_ar;
                        $data['title'] = $value->title_ar;
                       }
                       else                       
                       {
                        $data['message'] = $value->message;
                        $data['title'] = $value->title;
                       }

                       $data['noti_type'] = $value->noti_type;  
                       $data['ref_id'] = $value->ref_id;
                     
                      if($value->ref_id != '' && $value->ref_id != null && $value->ref_id != '0'){
                        
                        $shipment = Shipment::find($value->ref_id);
                     
                        $data['status'] = $shipment->status;
                      
                      }else{

                        $data['status'] = '0';
                      }

                       $data['is_read'] = $value->is_read;
                       $data['created_at'] =  Helper::convertDateWithTimezone($value->created_at, 'j M Y h:i A', $request->timezone);
                       
                       array_push($response, $data);
                    }

                    Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Notification listed Successfully !!','result' => $response ]));
                    return json_encode(['success' => 1, 'msg' => trans('word.Notification listed Successfully !!'),'result' =>$response ]);
                
                }else{

                    Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Notification List is Empty !!','result' =>[]]));
                    return json_encode(['success' => 1, 'msg' => trans('word.Notification List is Empty !!'),'result' => []]);
                } 
            }else{
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'User not Found !!','result' =>[]]));
                return json_encode(['success' => 0, 'msg' => trans('word.User Not Found'),'result' =>[]]);
            }
        }catch(Exception $ex) {
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }


    public function get_faq_questions(Request $request)
    {
    try{
        app()->setLocale(strtolower($request->language));
        $faq = DB::table('faq')->get();

        $response = array();

        if($faq != null && $faq != '[]'){

            foreach ($faq as $key => $value) {
            
                $data1 = array();

                $data1['id'] = $value->id;
                $data1['question'] = is_null($value->question)?'':$value->question;
                $data1['answer'] = is_null($value->answer)?'':$value->answer;
                
                array_push($response,$data1);
            }
        }

        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]);
    
        }catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }
    
    public function get_setting(Request $request)
    {
        try{
            app()->setLocale(strtolower($request->language));
        $user = User::find($request->user_id);

        $response = array();

        $data1 = array();

        $data1['is_push_notification'] = '0';

        if($user != null){

            $data1['is_push_notification'] = $user->push_notification;

            array_push($response, $data1);
        
        }

        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]);
    
        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }
    
    public function set_setting(Request $request)
    {
        try{
            app()->setLocale(strtolower($request->language));
        $user = User::find($request->user_id);

        $response = array();

        if($user != null){

            $user->push_notification = $request->is_push_notification;

            $user->save();
        

            Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => [] ]));
            return json_encode(['success' => 1, 'msg' => 'Success','result' => [] ]);
        }
    
        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }
    
    public function goods_type_list(Request $request)
    {

        try{
            app()->setLocale(strtolower($request->language));
        $goods_type = Goods_type::where('status','1')->get();

        $response = array();

        if($goods_type != null){

            
            foreach ($goods_type as $key => $value) {
                
                $data1 = array();

                $data1['id'] = $value->id;
                $data1['name'] = $value->goods_type_name;

                array_push($response,$data1);
            }
        
        }

            Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]));
            return json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]);
        
        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }
    

    public function delete_notification(Request $request)
    {
        try{
            app()->setLocale(strtolower($request->language));
        $user = User::find($request->user_id);

	$notification = Notification::find($request->notification_id);

        if($user != null && $notification != null){
            
            $notification->delete();
        
        }

        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => [] ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' => [] ]);
        
        }catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    public function truck_type_list(Request $request)
    {
        try{
            app()->setLocale(strtolower($request->language));
        $truck = DB::select('select * from truck where status = "1" ');

        $response = array();

        if($truck != null){

            foreach ($truck as $key => $value) {
                
                $data1 = array();

                $capacity = ($value->capacity == null || $value->capacity == '')?'1':$value->capacity;
                
                $weight_type = ($value->weight_type == '1')?'Ton':'Kg';

                $data1['truck_id'] = $value->id;
                $data1['truck_img'] = ($value->truck_img == null)?'':$value->truck_img;
                $data1['title'] = $value->truck_name.'  '.($capacity).' - '.($weight_type);

                array_push($response,$data1);
            }
        }
        
        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]);
        
        }catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    public function update_position(Request $request)
    {
        try{
            app()->setLocale(strtolower($request->language));
            $user = User::find($request->user_id);

            if($user != null) {
                
                $user->current_lat = isset($request->current_lat)?$request->current_lat:'0';  
                $user->current_lng = isset($request->current_lng)?$request->current_lng:'0';  
                // $user->device_token = $request->device_token; 
                // $user->device_type = $request->device_type;
    
                $user->save();
            }

            //Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => [] ]));
            return json_encode(['success' => 1, 'msg' => 'Success','result' => [] ]);
        
        }catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    public function send_test_mail(Request $request)
    {
        try {
            
            $user = User::find(81);

            Mail::send('emails.verification_link', ['user' => $user], function($message) use ($user) {
                $message->from(env('MAIL_USERNAME'), 'KMIOU');
                $message->to($user->email);
                $message->subject('KMIOU Mail Testing');
            });
        }
        catch(Exception $ex) {
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }
    
//end controller function
}
