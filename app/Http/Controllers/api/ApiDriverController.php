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
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Helper;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\User;
use App\Card;
use App\Driver;
use App\Review;
use App\Shipment;
use App\Shipment_bid;
use App\Shipment_info;
use App\Track_shipment;
use App\Shipment_surge_price;
use App\Truck;
use App\Transporter_truck;

class ApiDriverController extends Controller
{
    public function add_driver(Request $request){

        try{    
            app()->setLocale(strtolower($request->language));
                
            $first_name = $request->first_name;
            $last_name = $request->last_name;
            //$email = $request->email; //OLD Comment By Mehul
            $email = $request->mobile_no.'@mobile.com';
            $user_type = '4';
            $password =  Hash::make($request->password);    
            $verification_code = rand(1111,9999);
            
            //Check Email Is Exist Or Not
            $check_participate = User::where('email', '=', $email)->where('status', '!=',"2")->first();

            if(is_null($check_participate)){ 
                    
                $user  = new User;
                
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->email = $email;
                $user->user_type = $user_type;
                $user->password = $password;
                $user->profile_pic = is_null($request->profile_pic)?null:$request->profile_pic;
                $user->mobile_no = is_null($request->mobile_no)?null:$request->mobile_no;
                $user->country_code = '+213';
                
                $user->ref_id = is_null($request->user_id)?0:$request->user_id;
                $user->carrier_number = is_null($request->carrier_number)?0:$request->carrier_number;
                $user->language = is_null($request->language)?1:$request->language;
                $user->doc = is_null($request->doc)?null:$request->doc;
                $user->is_verify = '1';
                $user->status = '1';
                $user->approve = '0'; //'1'
                
                $user->save();

                $driver = new Driver;
                $driver->transporter_id = $request->user_id;
                $driver->driver_id = $user->id;
                $driver->status = '1';
                $driver->save();


                $truck_type = new Transporter_truck;                        
                $truck_type->user_id = $user->id; 
                $truck_type->truck_id = $request->truck_type; 
                $truck_type->status = '1';                 
                $truck_type->save(); 


                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => [] ]));
                return json_encode(['success' => 1, 'msg' => 'Success','result' => [] ]);            
            }
            else
            {
                $msg=trans('word.Email Already Exists');          
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
            }
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

    public function driver_list(Request $request){
    try{
        app()->setLocale(strtolower($request->language));
        
            $query = '';

            if($request->search_string != null && $request->search_string != ''){

	          $query .= '  AND ( (LOWER(CAST(users.first_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.$request->search_string.'%")  OR (LOWER(CAST(users.last_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.$request->search_string.'%") )';  
	        }

            $check_driver = DB::select('select driver.*,users.first_name as first_name,users.last_name as last_name, (SELECT truck_img FROM truck WHERE id = (SELECT truck_id FROM transporter_truck WHERE user_id = driver.driver_id) ) AS truck_image
             from driver 
            left join users on users.id = driver.driver_id 
            where driver.transporter_id='.$request->user_id.'  AND driver.status = "1" AND users.status = "1" '.$query.' order by driver.created_at desc ');
           
            $response = array();

            if($check_driver != null){
                
                    foreach ($check_driver as $key => $value) {
                        
                        $data1 = array();
                        
                        $select_user = User::find($value->driver_id);

                        if($select_user != null){

                            $data1['user_id'] = $select_user->id;
                            $data1['user_name'] = ($select_user->first_name == null)?'':$select_user->first_name.' '.(($select_user->last_name == null)?'':$select_user->last_name);
                            $data1['profile_pic'] = ($select_user->profile_pic == null)?'':$select_user->profile_pic; //OLD
                            $data1['truck_img'] = ($value->truck_image == null)?'':$value->truck_image;
                            $data1['email'] = ($select_user->email == null)?'':$select_user->email;
                            $data1['mobile'] = ($select_user->mobile_no == null)?'':$select_user->mobile_no;
                            $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $request->timezone);
                            
                            array_push($response,$data1);   
                        }
                    }
                }
                
                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]));
                return json_encode(['success' => 1, 'msg' => 'Success','result' => $response ]);
            
        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }
    

    public function delete_driver(Request $request){
    try{
        app()->setLocale(strtolower($request->language));
            $driver = Driver::where('transporter_id',$request->user_id)->where('driver_id',$request->driver_id)->where('status','!=','2')->orderBy('created_at','desc')->first(); 

            if($driver && $driver->status == '1'){
	           
	            $driver->status = '2';
	           
	            $driver->save();

                $user = User::find($request->driver_id);
                $user->status = '2';
                $user->save();
	            
	            Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Driver Removed Successfully','result' => [] ]));
	            return json_encode(['success' => 1, 'msg' => trans('word.Driver Removed Successfully'),'result' => [] ]);
	    
	        }else{

	        	$msg = 'Driver Already Removed';
	        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
	        }

        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }


    public function truck_list(Request $request){
    try{
        app()->setLocale(strtolower($request->language));
        
            $query = '';

            $get_truck = DB::select('select truck.*
             from truck
            where truck.status = "1" order by truck.created_at desc ');
           
            $response = array();

            if($get_truck != null){
                
                    foreach ($get_truck as $key => $value) {
                        
                        $data1 = array();

                        $data1['truck_id'] = $value->id;
                        $data1['truck_name'] = ($value->truck_name == null)?'':$value->truck_name;
                        $data1['truck_img'] = ($value->truck_img == null)?'':$value->truck_img;
                        $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $request->timezone);
                        
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
       

    public function send_detention_request(Request $request){
        try{
            app()->setLocale(strtolower($request->language));
            
            $check_request = Shipment_surge_price::where('shipment_id',$request->shipment_id)->first();

            if($check_request == null){

                $shipment = Shipment::find($request->shipment_id);

                /*-------------------------------------------------------------------------------------*/

                    $get_arrived_pickup_time = Track_shipment::where('shipment_id',$request->shipment_id)->where('status',"4")->first();
                    $get_pickup_time = Track_shipment::where('shipment_id',$request->shipment_id)->where('status',"5")->first();

                    $get_pickup_time_with_format = (date('Y-m-d H:i:s',strtotime($get_pickup_time->created_at)));
                    $get_arrived_pickup_time_with_format = (date('Y-m-d H:i:s',strtotime($get_arrived_pickup_time->created_at)));
                    

                    $get_arrived_drop_time = Track_shipment::where('shipment_id',$request->shipment_id)->where('status',"8")->first();
                
                    $get_drop_time = Track_shipment::where('shipment_id',$request->shipment_id)->where('status',"6")->first();

                    $get_drop_time_with_format = (date('Y-m-d H:i:s',strtotime($get_drop_time->created_at)));

                    $get_arrived_drop_time_with_format = (date('Y-m-d H:i:s',strtotime($get_arrived_drop_time->created_at)));

                /*-------------------------------------------------------------------------------------*/

                $pickup_amount = 0;

                if($request->surge_price_for_pickup == '1'){

                    $pick_diff_hours = date('H',strtotime($request->pick_time_diff));
                    $pick_diff_minutes = date('i',strtotime($request->pick_time_diff));

                    $get_pickup_surge_price_per_hour = DB::select('select * from surge_price where type = "0" AND status = "1" AND total_diff_hours between "0" AND "'.$pick_diff_hours.'" order by total_diff_hours desc limit 1  ');
                
                    if($get_pickup_surge_price_per_hour != '[]' && $get_pickup_surge_price_per_hour != null){

                        $pickup_amount = ($pick_diff_hours * $get_pickup_surge_price_per_hour[0]->price_per_hour);

                        if($pick_diff_minutes >= '30'){
                            $pickup_amount = $pickup_amount + ($get_pickup_surge_price_per_hour[0]->price_per_hour / 2 );
                        }   
                    }
                }

                $drop_amount = 0;

                if($request->surge_price_for_drop == '1'){

                    $drop_diff_hours = date('H',strtotime($request->drop_time_diff));
                    $drop_diff_minutes = date('i',strtotime($request->drop_time_diff));

                    $get_drop_surge_price_per_hour = DB::select('select * from surge_price where type = "1" AND status = "1" AND total_diff_hours between "0" AND "'.$pick_diff_hours.'" order by total_diff_hours desc limit 1  ');
                
                    if($get_drop_surge_price_per_hour != '[]' && $get_drop_surge_price_per_hour != null){

                        $drop_amount = ($drop_diff_hours * $get_drop_surge_price_per_hour[0]->price_per_hour);

                        if($drop_diff_minutes >= '30'){
                            $drop_amount = $drop_amount + ($get_drop_surge_price_per_hour[0]->price_per_hour / 2 );
                        }
                    
                    }
                }

                $send_request = new Shipment_surge_price;
                
                
                $send_request->shipment_id = $request->shipment_id;
                $send_request->surge_price_for_pickup = $request->surge_price_for_pickup;
                $send_request->surge_price_for_drop = $request->surge_price_for_drop;
                $send_request->arrived_pickup_time = $get_arrived_pickup_time_with_format;
                $send_request->start_shipment_time = $get_pickup_time_with_format;
                $send_request->arrived_drop_time = $get_arrived_drop_time_with_format;
                $send_request->delivered_time = $get_drop_time_with_format;
                $send_request->pick_time_diff = $request->pick_time_diff;
                $send_request->drop_time_diff = $request->drop_time_diff;
                $send_request->pickup_amount = $pickup_amount;
                $send_request->drop_amount = $drop_amount;
                $send_request->comment = $request->comment;
                $send_request->doc = $request->pod_images;
                $send_request->status = '0';
                   
                $send_request->save();

                $check_user = User::find($request->user_id);

                if($check_user->ref_id != '0'){

                    $get_truck_no = Shipment_info::where('shipment_id',$shipment->id)->first();

                    app()->setLocale('en'); 
                    $msg_en =trans('word.(driver) Sent Detention Request of Order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

                    app()->setLocale('fr'); 
                    $msg_fr = trans('word.(driver) Sent Detention Request of Order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

                    app()->setLocale('ar'); 
                    $msg_ar =trans('word.(driver) Sent Detention Request of Order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;


                    //send notification
                    Helper::send_push_notification($shipment->driver_id,$shipment->transporter_id,'Sent Detention Request',$msg_en,'13',$shipment->id,$msg_fr,$msg_ar);

                    $driver = User::find($shipment->driver_id);
                    $user = User::find($shipment->transporter_id);

                    // send mail
                    $user_detail =array();
                      $user_detail['name'] = is_null($user->first_name)?'':$user->first_name.' '.(is_null($user->last_name)?'':$user->last_name);
                      $user_detail['email'] = $user->email;
                      $user_detail['message'] = (is_null($driver->first_name)?'':$driver->first_name).' '.(is_null($driver->last_name)?'':$driver->last_name).' '.trans('word.(driver) Sent Detention Request of Order No.').' #'.$shipment->id;


                      Mail::send('emails.detention_info', ['user' => (object)$user_detail,'send_request' => (object)$send_request], function($message) use ($user) {
                          $message->from(env('MAIL_USERNAME'), 'KMIOU');
                          $message->to($user->email);
                          $message->subject('KMIOU Surge Price Info');
                      });
                      
                }
                
                $get_truck_no = Shipment_info::where('shipment_id',$shipment->id)->first();
                
                app()->setLocale('en'); 
                $msg_en =trans('word.(driver) Sent Detention Request of Your Order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

                app()->setLocale('fr'); 
                $msg_fr = trans('word.(driver) Sent Detention Request of Your Order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

                app()->setLocale('ar'); 
                $msg_ar =trans('word.(driver) Sent Detention Request of Your Order Truck No.').' '.$get_truck_no->no_of_vehicle.' '.trans('word.Order No.').' #'.$shipment->unique_id;

                //send notification
                Helper::send_push_notification($shipment->driver_id,$shipment->user_id,'Sent Detention Request',$msg_en,'13',$shipment->id,$msg_fr,$msg_ar);

                $driver = User::find($shipment->driver_id);
                $user = User::find($shipment->user_id);

                    // send mail
                    $user_detail =array();
                      $user_detail['name'] = is_null($user->first_name)?'':$user->first_name.' '.(is_null($user->last_name)?'':$user->last_name);
                      $user_detail['email'] = $user->email;
                      $user_detail['message'] = (is_null($driver->first_name)?'':$driver->first_name).' '.(is_null($driver->last_name)?'':$driver->last_name).' '.trans('word.(driver) Sent Detention Request of Order No.').' #'.$shipment->id;


                      Mail::send('emails.detention_info', ['user' => (object)$user_detail,'send_request' => (object)$send_request], function($message) use ($user) {
                          $message->from(env('MAIL_USERNAME'), 'KMIOU');
                          $message->to($user->email);
                          $message->subject('KMIOU Surge Price Info');
                      });


                      app()->setLocale(strtolower($request->language));
                
                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Request Sent Successfully','result' => [] ]));
                return json_encode(['success' => 1, 'msg' => trans('word.Request Sent Successfully'),'result' => [] ]);
        
            }else{

                app()->setLocale(strtolower($request->language));
                $msg = trans('word.Request Already Sent');
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
                return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
            }

        }catch(Exception $ex) {
                
            app()->setLocale(strtolower($request->language));
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }


    public function shipment_detention_info(Request $request){
    try{
        app()->setLocale(strtolower($request->language));
            
            $check_shipment = Shipment::find($request->shipment_id)->first();

            if($check_shipment != null && $check_shipment->status == '6'){

                $get_arrived_pickup_time = Track_shipment::where('shipment_id',$request->shipment_id)->where('status',"4")->first();
                $get_pickup_time = Track_shipment::where('shipment_id',$request->shipment_id)->where('status',"5")->first();
            
                $get_arrived_drop_time = Track_shipment::where('shipment_id',$request->shipment_id)->where('status',"8")->first();
                
                $get_drop_time = Track_shipment::where('shipment_id',$request->shipment_id)->where('status',"6")->first();


               
                // get pickup time diffrence

                /*---------------------------------------------------------------------------------------*/
               
                $get_pickup_time_with_format = (date('Y-m-d H:i:s',strtotime($get_pickup_time->created_at)));
                $get_arrived_pickup_time_with_format = (date('Y-m-d H:i:s',strtotime($get_arrived_pickup_time->created_at)));
                
                $start_pickup  = new Carbon($get_pickup_time_with_format);
                $end_pickup    = new Carbon($get_arrived_pickup_time_with_format);

                $start_pickup_hours = ($start_pickup->diffInHours($end_pickup) < '10')?'0'.$start_pickup->diffInHours($end_pickup):$start_pickup->diffInHours($end_pickup); 

                $pick_time_diff = $start_pickup_hours. ':' . $start_pickup->diff($end_pickup)->format('%I:%S');

                /*---------------------------------------------------------------------------------------*/


                // get drop time diffrence

                /*---------------------------------------------------------------------------------------*/
                
                $get_drop_time_with_format = (date('Y-m-d H:i:s',strtotime($get_drop_time->created_at)));
                $get_arrived_drop_time_with_format = (date('Y-m-d H:i:s',strtotime($get_arrived_drop_time->created_at)));
                
                $start_drop = new Carbon($get_drop_time_with_format);
                $end_drop = new Carbon($get_arrived_drop_time_with_format);

                $start_hours = ($start_drop->diffInHours($end_drop) < '10')?'0'.$start_drop->diffInHours($end_drop):$start_drop->diffInHours($end_drop); 

                $drop_time_diff = $start_hours. ':' . $start_drop->diff($end_drop)->format('%I:%S');

                /*---------------------------------------------------------------------------------------*/

                    
                $data = array();
                $data['shipment_id'] = $request->shipment_id;
                $data['arrived_at_pickup_time'] = $get_arrived_pickup_time_with_format;
                $data['start_shipment_time'] = $get_pickup_time_with_format;
                $data['arrived_at_drop_time'] = $get_arrived_drop_time_with_format;
                $data['delivered_time'] = $get_drop_time_with_format;
                $data['pick_time_diff'] = $pick_time_diff;
                $data['drop_time_diff'] = $drop_time_diff;


                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => array($data) ]));
                return json_encode(['success' => 1, 'msg' => 'Success','result' => array($data) ]);
        
            }else{

                $msg = trans('word.Shipment Not Found Or Not Delivered Yet');
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
                return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
            }

        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }


    public function shipment_detaintion_details(Request $request){
    try{ 
        app()->setLocale(strtolower($request->language));
        

        $data = DB::select('select shipment_surge_price.*
        from shipment_surge_price where shipment_id = '.$request->shipment_id.' limit 1 ');

        $response = array();

        if($data != null){
            
                foreach ($data as $key => $value) {
                    
                    $data1 = array();

                    $data1['shipment_id'] = $value->shipment_id;
                    $data1['surge_price_for_pickup'] = $value->surge_price_for_pickup;
                    $data1['surge_price_for_drop'] = $value->surge_price_for_drop;
                    $data1['arrived_pickup_time'] = $value->arrived_pickup_time;
                    $data1['start_shipment_time'] = ($value->start_shipment_time == null)?'':$value->start_shipment_time;
                    $data1['pick_time_diff'] = ($value->pick_time_diff == null)?'':$value->pick_time_diff;
                    $data1['arrived_drop_time'] = ($value->arrived_drop_time == null)?'':$value->arrived_drop_time;
                    $data1['delivered_time'] = ($value->delivered_time == null)?'':$value->delivered_time;
                    $data1['drop_time_diff'] = ($value->drop_time_diff == null)?'':$value->drop_time_diff;
                    $data1['pickup_amount'] = ($value->pickup_amount == null)?'':$value->pickup_amount;
                    $data1['drop_amount'] = ($value->drop_amount == null)?'':$value->drop_amount;
                    $data1['drop_amount'] = ($value->drop_amount == null)?'':$value->drop_amount;
                    $data1['pod_images'] = ($value->doc == null)?'':$value->doc;
                    $data1['comment'] = ($value->comment == null)?'':$value->comment;
                    $data1['status'] = ($value->status == null)?'':$value->status;
                    $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $request->timezone);
                    
                    array_push($response,$data1);   
                    
                }
            }

        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => trans('word.Success'),'result' => $response ]));
        return json_encode(['success' => 1, 'msg' => trans('word.Success'),'result' => $response ]);
        
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    }

   public function update_driver_doc(Request $request)
    { 
    try{     
        app()->setLocale(strtolower($request->language));   
          /* Update Driver Document */
          
          $user = User::find($request->driver_id);

          $user->doc = $request->doc;

          $user->save();
            
            Helper::logs($_POST,json_encode(['success' => 1, 'msg' => trans('word.Document Updated Successfully'),'result' => [] ]));
            return json_encode(['success' => 1, 'msg' => trans('word.Document Updated Successfully'),'result' => [] ]);
            
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }

    }


    /* users truck list*/

    public function users_truck_list(Request $request)
    {
    try{  
        app()->setLocale(strtolower($request->language));
            $user = User::find($request->user_id);

            $check_truck = Transporter_truck::where('user_id',$request->user_id)->where('status','!=','2')->orderBy('created_at','desc')->get();    
               
            $truck_list = array();

            if($check_truck != null){
                
                foreach ($check_truck as $key => $value) {
                    
                    $data1 = array();
                    
                    $select_truck = Truck::find($value->truck_id);

                    if($select_truck != null){

                        $data1['truck_id'] = $select_truck->id;
                        $data1['truck_name'] = ($select_truck->truck_name == null)?'':$select_truck->truck_name.' - '.(($select_truck->capacity == null)?'1':$select_truck->capacity).' '.(($select_truck->weight_type == '0')?'Kg':'Ton');
                        $data1['truck_img'] = ($select_truck->truck_img == null)?'':$select_truck->truck_img;
                        $data1['truck_type'] = ($select_truck->truck_type == null)?'':$select_truck->truck_type;
                        $data1['status'] = ($value->status == null)?'':$value->status;
                        $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $request->timezone);
                        
                        array_push($truck_list,$data1);   
                    }
                }
            }

            Helper::logs($_POST,json_encode(['success' => 1, 'msg' => trans('word.Success'),'result' => $truck_list ]));
            return json_encode(['success' => 1, 'msg' => trans('word.Success'),'result' => $truck_list ]);
            
        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }

    }

    public function users_truck_list_for_add(Request $request)
    {
    try{  
        app()->setLocale(strtolower($request->language));
            $user = User::find($request->user_id);

            $select_users_truck = DB::select(' Select GROUP_CONCAT(truck_id) as truck_id from transporter_truck where user_id = '.$request->user_id.' AND status != "2" order by created_at desc ');

            $query = '';

            if($select_users_truck[0]->truck_id != null ){
                $query = ' AND id not in ('.$select_users_truck[0]->truck_id.') ';
            }

            /*if($request->search_string != null && $request->search_string != ''){
         
                $query .= '  AND ( (LOWER(CAST(truck.truck_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.$request->search_string.'%")  OR (LOWER(CAST(truck.capacity AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.$request->search_string.'%") )';
            }*/

            $check_truck = DB::select(' Select * from truck where status = "1" '.$query.' order by created_at desc ');

            $truck_list = array();

            if($check_truck != null){
                
                foreach ($check_truck as $key => $value) {
                    
                    $data1 = array();

                    $data1['truck_id'] = $value->id;
                    $data1['truck_name'] = ($value->truck_name == null)?'':$value->truck_name.' - '.(($value->capacity == null)?'1':$value->capacity).' '.(($value->weight_type == '0')?'Kg':'Ton');
                    $data1['truck_img'] = ($value->truck_img == null)?'':$value->truck_img;
                    $data1['truck_type'] = ($value->truck_type == null)?'':$value->truck_type;
                    $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $request->timezone);
                    
                    array_push($truck_list,$data1);   
                
                }
            }
            
            Helper::logs($_POST,json_encode(['success' => 1, 'msg' => trans('Success'),'result' => $truck_list ]));
            return json_encode(['success' => 1, 'msg' => trans('word.Success'),'result' => $truck_list ]);
            
        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    } 

    public function add_new_truck(Request $request)
    {
     try{  
        app()->setLocale(strtolower($request->language));
            $user = User::find($request->user_id);

            $check_truck = Transporter_truck::where('user_id',$request->user_id)->where('truck_id',$request->truck_id)->where('status','!=',"2")->first();

            if($check_truck == null){

                if($user->user_type == '4'){
                    // remove other trucks
                    DB::table('transporter_truck')->where('user_id',$user->id)->where('truck_id','!=',$request->truck_id)->update(['status' => '2']);
                }

                $truck = new Transporter_truck;
                $truck->user_id = $request->user_id;
                $truck->truck_id = $request->truck_id;
                $truck->status = '1';
                $truck->save();

                $msg=trans('word.Truck Added Successfully');          
                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
                return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
            }
            else
            {
                $msg=trans('word.Truck Already Added');          
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
                return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
            }
            
        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }

    }


    public function change_truck_status(Request $request)
    {
    try{  
        app()->setLocale(strtolower($request->language));
            $user = User::find($request->user_id);

            $truck = Transporter_truck::where('user_id',$request->user_id)->where('truck_id',$request->truck_id)->where('status','!=','2')->orderBy('created_at','desc')->first(); 

              if($truck != null){

                $truck->status = $request->status;

                $truck->save();

              } 
            
            Helper::logs($_POST,json_encode(['success' => 1, 'msg' => trans('word.Truck Status Changed Successfully'),'result' => [] ]));
            return json_encode(['success' => 1, 'msg' => trans('word.Truck Status Changed Successfully'),'result' => [] ]);
            
        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }

    }


    public function remove_added_truck(Request $request)
    {
    try{  
        app()->setLocale(strtolower($request->language));
            $user = User::find($request->user_id);

            $truck = Transporter_truck::where('user_id',$request->user_id)->where('truck_id',$request->truck_id)->where('status','!=','2')->orderBy('created_at','desc')->first(); 

            if($truck != null){
               
                $truck->status = '2';
               
                $truck->delete();

                $msg = trans('word.Truck Removed Successfully');
                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
                return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
        
            }else{

                $msg = trans('word.Truck Already Removed');
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
                return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
            }
            
        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'),'err' => $ex->getMessage(), 'result' => [] ]);
        }
    } 


    /* transporter truck list*/


//end controller function
}
