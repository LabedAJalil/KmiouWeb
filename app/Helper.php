<?php
namespace App;

use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Input;
use Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use DateTime;
use DateTimeZone;
//use Exception;
use Carbon\Carbon;

use App\User;
use App\Driver;
use App\GasGuy;
use App\Notification;
    
class Helper {
    
    public static function sendSMS($MobileNo, $Message) {
        
        try {
            //API Url
            $url = 'http://kyo-sms.com/api/send.php';
            //The JSON data.
            $jsonData = array(
                'api_token' => 'wzHnLSlekfPR2vx76zhVMIK9IEJHD2NaBvxmoTAI9pAbV8j1HAXtjID1mmJ0',
                'from' => 'KYO',
                'to' => $MobileNo,
                'message' => $Message,
            );
            //Encode the array into JSON.
            $jsonDataEncoded = json_encode($jsonData);

            //Initiate cURL.
            $ch = curl_init($url);
            //Tell cURL that we want to send a POST request.
            curl_setopt($ch, CURLOPT_POST, 1);
            //Attach our encoded JSON string to the POST fields.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
            //Set the content type to application/json
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            //Execute the request
            $result = curl_exec($ch);
            curl_close($ch);

            //Helper::logs("",json_encode($result));

        } catch(Exception $e) {
            //Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
        }
    }
    
    public static function send_push_notification($user_id,$friend_id,$title,$msg,$noti_type,$ref_id,$msg_fr = "",$msg_ar = "") {
        
        try {
                
                //SEND PUSH NOTIFICATION
                $User = User::find($user_id);
                $Friend = User::find($friend_id);
                
                $noti_message = is_null($User->first_name)?'':$User->first_name.' '.(is_null($User->last_name)?'':$User->last_name).' '.$msg;

                $noti_message_fr = is_null($User->first_name)?'':$User->first_name.' '.(is_null($User->last_name)?'':$User->last_name).' '.$msg_fr;

                $noti_message_ar = is_null($User->first_name)?'':$User->first_name.' '.(is_null($User->last_name)?'':$User->last_name).' '.$msg_ar;


                $title = $title;
                
                app()->setLocale('fr');   
                $title_fr = trans('word.'.$title);

                app()->setLocale('ar');   

                $title_ar = trans('word.'.$title);


                
                $notification = new Notification;
                $notification->from_user_id = is_null($User->id)?'0':$User->id;  
                $notification->to_user_id = is_null($Friend->id)?'0':$Friend->id; 
                $notification->title = is_null($title)?'':$title;
                $notification->title_fr = is_null($title_fr)?'':$title_fr;
                $notification->title_ar = is_null($title_ar)?'':$title_ar;
                $notification->message = is_null($noti_message)?'':$noti_message;
                $notification->message_fr = is_null($noti_message_fr)?'':$noti_message_fr;
                $notification->message_ar = is_null($noti_message_ar)?'':$noti_message_ar;
                $notification->noti_type = $noti_type;
                $notification->ref_id = is_null($ref_id)?'0':$ref_id;    
               
                $notification->save();

                /*noti_type 
                    1 : New Shipment (shipper -> transporter)
                    2 : New Bid Placed (transporter -> shipper)
                    3 : Bidder Selected (shipper -> transporter)
                    4 : Assign Driver (transporter -> driver)
                    5 : Order Accepted (transporter -> shipper)
                    6 : Order Status Update (driver -> shipper)
                    7 : Order Cancelled (driver -> shipper)
                    8 : Order Reported Emergency (driver -> shipper)
                    9 : Reject Shipment (transporter/driver -> shipper)
                    11: Rate Shipment (shipper -> driver)
                    12: Assign order to transporter (admin -> transporter)
                    13: Sent Detention Request (driver -> transporter , driver -> shipper)
                    14: Detention Request Accepted (admin -> driver)
                    15: Detention Request Rejected (admin -> driver)
                */

                $message = $noti_message;    
                if($Friend->language == 'fr' || $Friend->language == 'FR')
                {
                    $message = $noti_message_fr;
                }
                else if($Friend->language == 'ar' || $Friend->language == 'AR')
                {
                    $message = $noti_message_ar;
                }
                else
                {
                    $message = $noti_message;    
                }
                
                $msg = array('msg' => $message, 'tag'=>$notification->noti_type, 'user_id'=>$notification->to_user_id, 'ref_id'=>$notification->ref_id);
                
                if($Friend->device_type=="2" && $Friend->device_token!=""){
                    Helper::send_notification_ios($Friend->device_token,$msg);
                }
                if($Friend->device_type=="1" && $Friend->device_token!=""){
                    $tokenarray[]=$Friend->device_token;
                    if(count($tokenarray)!=0){
                        Helper::send_notification_android($tokenarray,$msg);
                    }
                }
            
        } catch(Exception $e) {
            
        }
    }

     public static function send_admin_push_notification($user_id,$friend_id,$title,$msg,$noti_type,$ref_id,$msg_fr = "",$msg_ar = "") {
        
        try {
                
                //SEND PUSH NOTIFICATION

                $User = User::find($user_id);
                $Friend = User::find($friend_id);
                
                $noti_message = $msg;

                $noti_message_fr = $msg_fr;

                $noti_message_ar = $msg_ar;

                $title = $title;
                
                app()->setLocale('fr');   
                $title_fr = trans('word.'.$title);

                app()->setLocale('ar');   

                $title_ar = trans('word.'.$title);

                $notification = new Notification;
                $notification->from_user_id = is_null($User->id)?'0':$User->id;  
                $notification->to_user_id = is_null($Friend->id)?'0':$Friend->id; 
                $notification->title = is_null($title)?'':$title;
                $notification->title_fr = is_null($title_fr)?'':$title_fr;
                $notification->title_ar = is_null($title_ar)?'':$title_ar;
                $notification->message = is_null($noti_message)?'':$noti_message;
                $notification->message_fr = is_null($noti_message_fr)?'':$noti_message_fr;
                $notification->message_ar = is_null($noti_message_ar)?'':$noti_message_ar;
                $notification->noti_type = $noti_type;
                $notification->ref_id = is_null($ref_id)?'0':$ref_id;    
               
                $notification->save();


                /*noti_type 
                    1 : New Shipment (shipper -> transporter)
                    2 : New Bid Placed (transporter -> shipper)
                    3 : Bidder Selected (shipper -> transporter)
                    4 : Assign Driver (transporter -> driver)
                    5 : Order Accepted (transporter -> shipper)
                    6 : Order Status Update (driver -> shipper)
                    7 : Order Cancelled (driver -> shipper)
                    8 : Order Reported Emergency (driver -> shipper)
                    9 : Reject Shipment (transporter/driver -> shipper)
                    11: Rate Shipment (shipper -> driver)
                    12: Assign order to transporter (admin -> transporter)
                    13: Sent Detention Request (driver -> transporter , driver -> shipper)
                    14: Detention Request Accepted (admin -> driver)
                    15: Detention Request Rejected (admin -> driver)
                    16: Notification from Admin (admin -> user)
                */

                $message = $noti_message;    
                if($Friend->language == 'fr' || $Friend->language == 'FR')
                {
                    $message = $noti_message_fr;
                }
                else if($Friend->language == 'ar' || $Friend->language == 'AR')
                {
                    $message = $noti_message_ar;
                }
                else
                {
                    $message = $noti_message;    
                }
                
                $msg = array('msg' => $message, 'tag'=>$notification->noti_type, 'user_id'=>$notification->to_user_id, 'ref_id'=>$notification->ref_id);
                
                if($Friend->device_type=="2" && $Friend->device_token!=""){
                    Helper::send_notification_ios($Friend->device_token,$msg);
                }
                if($Friend->device_type=="1" && $Friend->device_token!=""){
                    $tokenarray[]=$Friend->device_token;
                    if(count($tokenarray)!=0){
                        Helper::send_notification_android($tokenarray,$msg);
                    }
                }
            
        } catch(Exception $e) {
            
        }
    }

    //REF:: https://stackoverflow.com/questions/38302858/google-static-maps-with-directions
    public static function getStaticGmapURLForDirection($origin, $destination, $shipper, $driver, $transporter, $size = "500x500") {
        
        return "";

        /* Comment By Mehul No more used in application
        $GOOGLE_API_KEY = "key=AIzaSyDlGeSHx-FT_W9EK7FZsxsxeZDtgF3q8XU";
        
        $markers = array();

        if($shipper != "") {
            $markers[] = "markers=anchor:center" . urlencode("|") . "icon:https://tinyurl.com/y2dqgpnv" . urlencode("|") . urlencode($shipper);
        }
        if($driver != "") {
            $markers[] = "markers=anchor:center" . urlencode("|") . "icon:https://tinyurl.com/y4f4xuwj" . urlencode("|") . urlencode($driver);
        }
        if($transporter != "") {
            $markers[] = "markers=anchor:center" . urlencode("|") . "icon:https://tinyurl.com/yy9muxaw" . urlencode("|") . urlencode($transporter);
        }
        
        $markers[] = "markers=color:0x00874A" . urlencode("|") . "label:" . urlencode('P' . '|' . $origin);
        $markers[] = "markers=color:0xDF2F45" . urlencode("|") . "label:" . urlencode("D" . '|' . $destination);

        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$destination&$GOOGLE_API_KEY";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, false);
        $result = curl_exec($ch);
        curl_close($ch);
        $googleDirection = json_decode($result, true);
       
        if(count($googleDirection['routes']) > 0) {
            $polyline = urlencode($googleDirection['routes'][0]['overview_polyline']['points']);
            $markers[] = "path=weight:5" . urlencode("|") . "color:0x000000" . urlencode("|") . "enc:$polyline";
        }
        $markers = implode($markers, '&');
        //return "https://maps.googleapis.com/maps/api/staticmap?$GOOGLE_API_KEY&size=$size&maptype=roadmap&path=enc:$polyline&$markers"; //OLD Comment By Mehul

        //&size=$size
        //&scale=2
        return "https://maps.googleapis.com/maps/api/staticmap?$GOOGLE_API_KEY&maptype=roadmap&$markers"; 
        */
    }
    
    public static function sendEmail($toEmail, $toName, $subject, $htmlContent) {
        $ch = curl_init();
        
        //set up oauth_data request 
        $request_data = array(
            "sender" => array (
                    "name"=> 'KMIOU',
                    "email"=> 'Hamoud@kmiou.com'
                ),
            "to" => array (array (
                    "email" => $toEmail,
                    "name" => $toName
                )),
            "subject" => $subject,
            "htmlContent" => $htmlContent
        );

        $header[] = 'Content-type: application/json';
        $header[] = 'api-key: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';

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL,"https://api.sendinblue.com/v3/smtp/email");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // following SSL settings should be removed in production code. 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);

        //Execute the request
        $response = curl_exec($ch);
        curl_close($ch);

        //REF :: https://developers.sendinblue.com/docs/send-a-transactional-email
        //REF :: https://developers.sendinblue.com/reference#sendtransacemail
        //REF :: https://developers.sendinblue.com/docs/available-functions-in-api-clients
    }

    public static function get_user_total_request_count($user_id,$filter_type = '4', $timezone = 'Africa/Algiers') {
        
    try {   

            $today = date("Y-m-d H:i:s", strtotime("-1 days"));
            $last_week_date = date('Y-m-d H:i:s', strtotime('-7 days'));
            $last_month_date = date("Y-m-d H:i:s", strtotime("-1 month"));
            $last_year_date = date("Y-m-d H:i:s", strtotime("-1 year"));

            $query = ' AND info.pickup_date >= "'.Helper::getCurrentDateWithTimezone('Y-m-d', $timezone).'" ';

            $date = '';
            /*
             if($filter_type == '0'){
            
                $query = ' AND shipment.created_at >= "'.$today.'" ';
                $date = $today;
            
            }
            else if($filter_type == '1'){
            
                $query = ' AND shipment.created_at >= "'.$last_week_date.'" ';
                $date = $last_week_date;
            
            }else if($filter_type == '2'){
            
                $query = ' AND shipment.created_at >= "'.$last_month_date.'" ';
                $date = $last_month_date;
            
            }else if($filter_type == '3'){
            
                $query = ' AND shipment.created_at >= "'.$last_year_date.'" ';
                $date = $last_year_date;
            
            }  
            */ 
		
            //$User = User::find($user_id);         

            /*// for newly registered user
            if($User->email_verified_at != null && $User->email_verified_at >= $date){
            
                $query = ' AND (shipment.created_at >= "'.$User->email_verified_at.'" ) ';
            }

            $user_type_query = '';

            if($User->user_type == '2'){

                $user_type_query = 'user_id';
            }else{

                $user_type_query = 'transporter_id';
            }*/

            //Get Transporter Request Count
            /*$getCount = DB::select(' select count(shipment.id) as total_count
                    from shipment 
                    left join users on users.id = shipment.user_id 
                    where ( (shipment.status = "0" AND shipment.transporter_id = "0" AND shipment.driver_id = "0") OR (shipment.status = "0" AND shipment.user_id = '.$user_id.')  OR (shipment.status = "1" AND shipment.driver_id = "0" AND shipment.'.$user_type_query.' = '.$user_id.')) '.$query.' ');*/
            
            $getCount = DB::select(' select count(shipment.id) as total_count
                    from shipment 
                    left join shipment_request_count on shipment_request_count.shipment_id = shipment.id 
                    left join shipment_info as info on info.shipment_id = shipment.id 
                    left join users on users.id = shipment.user_id 
                    where ( (shipment.status = "0" AND info.quotation_type != "2" AND shipment_request_count.user_id = '.$user_id.' AND shipment_request_count.is_read != "2") OR (shipment.status = "1" AND shipment.driver_id="0" AND shipment.transporter_id = '.$user_id.' AND info.quotation_type = "2" ) ) '.$query.' ');
                
                // OR (shipment.status = "1" AND shipment.driver_id="0" AND shipment.transporter_id = '.$user_id.' AND info.quotation_type != "2" AND shipment_request_count.user_id = '.$user_id.' AND shipment_request_count.is_read != "2")

            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }   

            return 0;
            
        } catch(Exception $e) {
            
        }
    }

    public static function get_user_total_driver($user_id) {
        
        try {   

            $User = User::find($user_id);

            //Get total Driver Count
            $getCount = DB::select(' select count(driver.id) as total_count
                    from driver  
                    left join users on users.id=driver.driver_id  
                    where driver.status = "1" AND users.status = "1" AND driver.transporter_id = '.$user_id.' ');
        

            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }        

            return 0;
            
        } catch(Exception $e) {
            
        }
    }

    public static function get_pending_assign_driver_count($user_id,$filter_type = '4', $timezone = 'Africa/Algiers') {
        
        try {   

            $last_week_date = date('Y-m-d H:i:s', strtotime('-7 days'));
            $last_month_date = date("Y-m-d H:i:s", strtotime("-1 month"));
            $last_year_date = date("Y-m-d H:i:s", strtotime("-1 year"));
            $today = date("Y-m-d H:i:s", strtotime("-1 days"));

            $query = ' AND info.pickup_date >= "'.Helper::getCurrentDateWithTimezone('Y-m-d', $timezone).'" ';

            /*
            if($filter_type == '0'){
            
                $query = ' AND shipment.created_at >= "'.$today.'" ';
                
            }
            else if($filter_type == '1'){
            
                $query = ' AND shipment.created_at >= "'.$last_week_date.'" ';
            
            }else if($filter_type == '2'){
            
                $query = ' AND shipment.created_at >= "'.$last_month_date.'" ';
            
            }else if($filter_type == '3'){
            
                $query = ' AND shipment.created_at >= "'.$last_year_date.'" ';
            
            } 
            */  

            $User = User::find($user_id);

            //Get Request Count
            $getCount = DB::select(' select count(shipment.id) as total_count
                    from shipment 
                    left join shipment_info as info on info.shipment_id = shipment.id
                    where shipment.status = "1" AND shipment.transporter_id = '.$user_id.' AND shipment.driver_id = "0" '.$query.'  ');
        

            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }        

            return 0;
            
        } catch(Exception $e) {
            
        }
    }

    public static function get_pending_accepted_award_count($user_id,$filter_type = '4', $timezone = 'Africa/Algiers') {
        
        try {   

            $last_week_date = date('Y-m-d H:i:s', strtotime('-7 days'));
            $last_month_date = date("Y-m-d H:i:s", strtotime("-1 month"));
            $last_year_date = date("Y-m-d H:i:s", strtotime("-1 year"));
            $today = date("Y-m-d H:i:s", strtotime("-1 days"));

            $query = ' AND info.pickup_date >= "'.Helper::getCurrentDateWithTimezone('Y-m-d', $timezone).'" ';

           /*
            if($filter_type == '0'){
            
                $query = ' AND shipment.created_at >= "'.$today.'" ';
                
            }
            else if($filter_type == '1'){
            
                $query = ' AND shipment.created_at >= "'.$last_week_date.'" ';
            
            }else if($filter_type == '2'){
            
                $query = ' AND shipment.created_at >= "'.$last_month_date.'" ';
            
            }else if($filter_type == '3'){
            
                $query = ' AND shipment.created_at >= "'.$last_year_date.'" ';
            
            }   
            */

            $User = User::find($user_id);

            //Get Request Count
            $getCount = DB::select(' select count(shipment.id) as total_count
                    from shipment 
                    left join shipment_bid as bid on bid.shipment_id = shipment.id
                    left join shipment_info as info on info.shipment_id = shipment.id
                    where shipment.status = "0" AND bid.status = "1" AND shipment.transporter_id = '.$user_id.' AND shipment.bid_status = "1" '.$query.'  ');
        

            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }        

            return 0;
            
        } catch(Exception $e) {
            
        }
    }

    public static function get_total_bidded_trip_count($user_id,$filter_type = '4', $timezone = 'Africa/Algiers') {
        
        try {   

            $last_week_date = date('Y-m-d H:i:s', strtotime('-7 days'));
            $last_month_date = date("Y-m-d H:i:s", strtotime("-1 month"));
            $last_year_date = date("Y-m-d H:i:s", strtotime("-1 year"));
            $today = date("Y-m-d H:i:s", strtotime("-1 days"));

            $query = ' AND info.pickup_date >= "'.Helper::getCurrentDateWithTimezone('Y-m-d', $timezone).'" ';

           /*
            if($filter_type == '0'){
            
                $query = ' AND shipment.created_at >= "'.$today.'" ';
                
            }
            else if($filter_type == '1'){
            
                $query = ' AND shipment.created_at >= "'.$last_week_date.'" ';
            
            }else if($filter_type == '2'){
            
                $query = ' AND shipment.created_at >= "'.$last_month_date.'" ';
            
            }else if($filter_type == '3'){
            
                $query = ' AND shipment.created_at >= "'.$last_year_date.'" ';
            
            }   
            */

            $User = User::find($user_id);

            $get_rejected_shipment = DB::select('select GROUP_CONCAT( shipment_id ) as shipment_ids from shipment_request_count where user_id = '.$user_id.' AND is_read="2" ');

            if($get_rejected_shipment[0]->shipment_ids != null){
                    
                $query .= ' AND shipment.id not in ('.$get_rejected_shipment[0]->shipment_ids.') '; 
            }   

            //Get Request Count
            $getCount = DB::select(' select count(bid.id) as total_count
                    from shipment 
                    left join shipment_bid as bid on bid.shipment_id = shipment.id
                    left join shipment_info as info on info.shipment_id = shipment.id
                    where shipment.status = "0" AND info.quotation_type = "0" AND bid.user_id = '.$user_id.' AND bid.status = "0" AND shipment.bid_status = "0"
                     '.$query.' ');

        
            
            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }        

            return 0;
            
        } catch(Exception $e) {
            
        }
    }


    public static function get_user_request_count($user_id, $timezone = 'Africa/Algiers') {
        
        try {   

            $query = ' AND info.pickup_date >= "'.Helper::getCurrentDateWithTimezone('Y-m-d', $timezone).'" ';

            $User = User::find($user_id);

            $associatedDriver =  Driver::where('driver_id', '=', $user_id)->first();
            if(!empty($associatedDriver)) { //Associated Driver
                //STATUS :: 0=pending, 1=confirm, 2=on_the_way, 3=cancelled, 4=arrived, 5=strat_shipment, 6=reached, 7=report, 8=arrived at drop of loctions, 9=on_the_way_to_pickup
                
                //Get Upcoming Shipment Count
                $getCount = DB::select(' SELECT count(shipment.id) as total_count 
                                        FROM shipment 
                                        left join shipment_info as info on info.shipment_id=shipment.id
                                        WHERE shipment.driver_id = '.$user_id.' AND shipment.status IN ("1") '.$query.' ' );  //"2","4","5","8","9"
            }
            else {
                //Get Request Count
                $getCount = DB::select(" select count(shipment_request_count.id) as total_count
                        from shipment_request_count 
                        left join shipment on shipment.id=shipment_request_count.shipment_id
                        left join shipment_info as info on info.shipment_id=shipment.id
                        where 
                            shipment.status = '0' AND 
                            info.quotation_type != '2' AND 
                            shipment_request_count.is_read != '2' AND 
                            shipment_request_count.user_id = ".$user_id." ".$query." "); 
                            
                            //." AND DATE(info.pickup_date) >= '".$current_date."' "
                            //shipment_request_count.is_read = '0' AND
            }
            
            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }        

            return 0;
            
        } catch(Exception $e) {
            
        }
    }

    public static function get_shipper_total_request_count($user_id,$filter_type = '4',$quotation_type = '1', $timezone = 'Africa/Algiers') {
        
        try {   

            $last_week_date = date('Y-m-d H:i:s', strtotime('-7 days'));
            $last_month_date = date("Y-m-d H:i:s", strtotime("-1 month"));
            $last_year_date = date("Y-m-d H:i:s", strtotime("-1 year"));
            $today = date("Y-m-d H:i:s", strtotime("-1 days"));

            $query = ' AND info.pickup_date >= "'.Helper::getCurrentDateWithTimezone('Y-m-d', $timezone).'" ';
               
            /*
            if($filter_type == '0'){
            
                $query = ' AND shipment.created_at >= "'.$today.'" ';
                
            }
            else if($filter_type == '1'){
            
                $query = ' AND shipment.created_at >= "'.$last_week_date.'" ';
            
            }else if($filter_type == '2'){
            
                $query = ' AND shipment.created_at >= "'.$last_month_date.'" ';
            
            }else if($filter_type == '3'){
            
                $query = ' AND shipment.created_at >= "'.$last_year_date.'" ';            
            }   
            */

            $User = User::find($user_id);
          
            //Get Request Count
            $getCount = DB::select(' select count(shipment.id) as total_count
                    from shipment
                    left join shipment_info as info on info.shipment_id = shipment.id 
                    left join users on users.id = shipment.user_id 
                    where shipment.status = "0" AND info.quotation_type = "'.$quotation_type.'" AND shipment.user_id = '.$user_id.' '.$query.'  '); //shipment.status IN ("0","1","2","4","5","8")
            
            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }        

            return 0;
            
        } catch(Exception $e) {
            
        }
    }

    public static function get_user_accepted_shipment_count($user_id,$filter_type = '4', $timezone = 'Africa/Algiers') {
            
    try {   
            $User = User::find($user_id);

            $last_week_date = date('Y-m-d H:i:s', strtotime('-7 days'));
            $last_month_date = date("Y-m-d H:i:s", strtotime("-1 month"));
            $last_year_date = date("Y-m-d H:i:s", strtotime("-1 year"));
            $today = date("Y-m-d H:i:s", strtotime("-1 days"));

            $query = ' AND info.pickup_date >= "'.Helper::getCurrentDateWithTimezone('Y-m-d', $timezone).'" ';
            $user_type_query = '';


            /*   
            if($filter_type == '0'){
            
                $query = ' AND shipment.updated_at >= "'.$today.'" ';
                
            }
            else if($filter_type == '1'){
            
                $query = ' AND shipment.updated_at >= "'.$last_week_date.'" ';
            
            }else if($filter_type == '2'){
            
                $query = ' AND shipment.updated_at >= "'.$last_month_date.'" ';
            
            }else if($filter_type == '3'){
            
                $query = ' AND shipment.updated_at >= "'.$last_year_date.'" ';
            
            }   
            */

            $driver_query = '';

            if($User->user_type == '2'){
            
                $user_type_query = 'shipment.user_id';
            
            }else if($User->user_type == '3'){
                
                $user_type_query = 'shipment.transporter_id';
                $driver_query = ' AND shipment.driver_id != "0" ';
            
            }else if($User->user_type == '4'){
            
                    $user_type_query = 'shipment.driver_id';
                    $driver_query = ' AND shipment.driver_id != "0" ';
            }

            //Get Request Count
            $getCount = DB::select(' SELECT Count(shipment.id) AS total_count 
                                    FROM shipment 
                                    INNER JOIN shipment_info as info on info.shipment_id = shipment.id 
                                    WHERE shipment.status = "1" '.$driver_query.' AND  '.$user_type_query.' = '.$User->id.' '.$query.'  ');

            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }        

            return 0;
            
        } catch(Exception $e) {
            
        }
    }

    public static function get_user_cancelled_shipment_count($user_id,$filter_type = '4', $timezone = 'Africa/Algiers') {
            
    try {   
            $User = User::find($user_id);

            $last_week_date = date('Y-m-d', strtotime('-7 days'));
            $last_month_date = date("Y-m-d", strtotime("-1 month"));
            $last_year_date = date("Y-m-d", strtotime("-1 year"));
            $today = date("Y-m-d H:i:s", strtotime("-1 days"));

            $query = ' AND info.pickup_date >= "'.Helper::getCurrentDateWithTimezone('Y-m-d', $timezone).'" ';
            $user_type_query = '';

           /*
            if($filter_type == '0'){
            
                $query = ' AND shipment.updated_at >= "'.$today.'" ';
                
            }
            else if($filter_type == '1'){
            
                $query = ' AND shipment.updated_at >= "'.$last_week_date.'" ';
            
            }else if($filter_type == '2'){
            
                $query = ' AND shipment.updated_at >= "'.$last_month_date.'" ';
            
            }else if($filter_type == '3'){
            
                $query = ' AND shipment.updated_at >= "'.$last_year_date.'" ';
            }   
            */

            if($User->user_type == '2'){
            
                $user_type_query = 'shipment.user_id';
            
            }else if($User->user_type == '3'){
            
                $user_type_query = 'shipment.transporter_id';
            
            }else if($User->user_type == '4'){
            
                $user_type_query = 'shipment.driver_id';
            }

            //Get Request Count
            $getCount = DB::select(' SELECT Count(shipment.id) AS total_count 
                                    FROM shipment 
                                    INNER JOIN shipment_info as info on info.shipment_id = shipment.id 
                                    WHERE shipment.status = "3" AND  '.$user_type_query.' = '.$User->id.' '.$query.'  ');
            
            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }        

            return 0;
            
        } catch(Exception $e) {
            
        }
    }

    public static function get_user_reported_shipment_count($user_id,$filter_type = '4', $timezone = 'Africa/Algiers') {
            
    try {   
            $User = User::find($user_id);

            $last_week_date = date('Y-m-d', strtotime('-7 days'));
            $last_month_date = date("Y-m-d", strtotime("-1 month"));
            $last_year_date = date("Y-m-d", strtotime("-1 year"));
            $today = date("Y-m-d H:i:s", strtotime("-1 days"));

            $query = ' AND info.pickup_date >= "'.Helper::getCurrentDateWithTimezone('Y-m-d', $timezone).'" ';
            $user_type_query = '';

            /*
            if($filter_type == '0'){
            
                $query = ' AND shipment.updated_at >= "'.$today.'" ';
                
            }
            else if($filter_type == '1'){
            
                $query = ' AND shipment.updated_at >= "'.$last_week_date.'" ';
            
            }else if($filter_type == '2'){
            
                $query = ' AND shipment.updated_at >= "'.$last_month_date.'" ';
            
            }else if($filter_type == '3'){
            
                $query = ' AND shipment.updated_at >= "'.$last_year_date.'" ';
            
            }   
            */

            if($User->user_type == '2'){
            
                $user_type_query = 'shipment.user_id';
            
            }else if($User->user_type == '3'){
            
                $user_type_query = 'shipment.transporter_id';
            
            }else if($User->user_type == '4'){
            
                $user_type_query = 'shipment.driver_id';
            }
            

            //Get Request Count
            //$getCount = DB::select(' SELECT Count(id) AS total_count FROM shipment WHERE status = "7" AND  '.$user_type_query.' = '.$User->id.' '.$query.' '); //OLD
            $getCount = DB::select(' SELECT Count(shipment.id) AS total_count 
                                    FROM shipment 
                                    INNER JOIN shipment_info as info on info.shipment_id = shipment.id 
                                    WHERE shipment.report_emergency != "-1" AND  '.$user_type_query.' = '.$User->id.' '.$query.' ');
            
            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }        

            return 0;
            
        } catch(Exception $e) {
            
        }
    }

    public static function get_user_received_offer_shipment_count($user_id,$filter_type = '4', $timezone = 'Africa/Algiers') {
            
        try {   

            $query = ' AND info.pickup_date >= "'.Helper::getCurrentDateWithTimezone('Y-m-d', $timezone).'" ';
            $user_type_query = '';
            
            $getCount = DB::select(' SELECT Count(shipment.id) AS total_count 
                                    FROM shipment 
                                    INNER JOIN shipment_info as info on info.shipment_id = shipment.id 
                                    WHERE
                                    shipment.status = "0" AND shipment.transporter_id = "0" AND shipment.driver_id = "0" AND shipment.bid_status = "0" AND info.quotation_type = "0" AND shipment.user_id = '.$user_id.' '.$query.' AND (SELECT COUNT(id) FROM shipment_bid WHERE shipment_id = shipment.id) > 0 ');
            
            if(count($getCount) > 0) {
                
                return $getCount[0]->total_count;
            }        

            return 0;
            
        } catch(Exception $e) {
            
        }
    }
    

    public static function convertDateWithTimezone($strDateTime, $format, $timeZoneName = 'Asia/Kolkata') {
        try {
            $newDate = new DateTime(date('Y-m-d H:i:s', strtotime($strDateTime))); //, new DateTimeZone('GMT')
            $newDate->setTimezone(new DateTimeZone($timeZoneName));
            //dd(date_format($newDate, 'l, j F, Y, H:i'));
            
            return date_format($newDate, $format);
            
        } catch(Exception $e) {
            return date($format, strtotime($strDateTime));
        }
    }
    
    public static function getCurrentDateWithTimezone($format, $timeZoneName = 'Asia/Kolkata') {
        
        try {
            $newDate = new DateTime(date('Y-m-d H:i:s')); //, new DateTimeZone('GMT')
            $newDate->setTimezone(new DateTimeZone($timeZoneName));
            //dd(date_format($newDate, 'l, j F, Y, H:i'));
            
            return date_format($newDate, $format);
            
        } catch(Exception $e) {
            return date($format, strtotime($strDateTime));
        }
    }
    
    public static function convertTimestampWithTimezone($strTimestamp, $format, $timeZoneName = 'Asia/Kolkata',$language = "") {
        
        try {
            $newDate = new DateTime("$strTimestamp");
            $newDate->setTimezone(new DateTimeZone($timeZoneName));

            $month = $newDate->format('F');
            $time_mr = $newDate->format('A');

            
            if($language == "FR" || $language == "fr")
            {
                $format = 'j F Y H:i';
            }
            else
            {
                $format = 'jS F Y H:i';
            }

            $created_date = date_format($newDate, $format);
            
            if($language != "")
            {
                app()->setLocale(strtolower($language)); 
            }
            else
            {
                app()->setLocale('en'); 
            }


            $new_date = str_replace($month, trans('word.'.$month), $created_date);
            // $new_date = str_replace($time_mr, trans('word.'.$time_mr), $new_date);

            return $new_date;
            // return date_format($newDate, $format);
            
        } catch(Exception $e) {
            return date($format, strtotime($strDateTime));
        }
    }

    public static function get_diff_between_two_date_time($date) {
       
        $start_date = new DateTime($date);
        $since_start = $start_date->diff(new DateTime());        
        
        $string = '';
        
        if($since_start->y > 0){
            
            $string = ($since_start->y == '1')?$since_start->y.' year ago':$since_start->y.' years ago';
        }else{
            
            if($since_start->m > 0){
            
                $string = ($since_start->m == '1')?$since_start->m.' month ago':$since_start->m.' months ago';
            }else{
                
                if($since_start->d > 0){
            
                    $string = ($since_start->d == '1')?$since_start->d.' day ago':$since_start->d.' days ago';
                }else{
                    
                    if($since_start->d > 0){
            
                        $string = ($since_start->h == '1')?$since_start->h.' hour ago':$since_start->h.' hours ago';
                    }else{
                        
                        if($since_start->i > 0){
            
                            $string = ($since_start->i == '1')?$since_start->i.' minute ago':$since_start->i.' minutes ago';
                        }           
                    }              
                }       
            }
        }

        if($string != '') {
            
            return $string;
        }
        
        return 'just now';
    }

     public static function logs($request='',$response='')
    {
        $file = base_path()."/api_logs/log_".date("Y-m-d").".html";
       
        $data='';
        if (!file_exists($file)) {
            $data.='<table width="100%" border="1">
            <tr>
            <td><h3>Date & Time</h3></td>
            <td><h3>Request URL</h3></td>
            <td><h3>Parameter</h3></td>
            <td><h3>Response</h3></td>
            <td><h3>IP</h3></td>
            </tr>';
        }
        $posts = $request;
        if(!empty($posts) && $posts != ""){
            $val1='';

            foreach($posts as $key=>$val){
                $val1.= $key.' : '.$val.'<br>';
            }
            
            $segment = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $data.='<tr>
            <td>'.date("Y-m-d H:i:s").'</td>
            <td>'.$segment.'</td>
            <td>'.$val1.'</td>
            <td>'.$response.'</td>
            <td>'.$_SERVER['SERVER_ADDR'].'</td>
            </tr>';
        }
        $f=fopen($file, 'a');
        fwrite($f,$data."\r\r\n");
        chmod($file,0777);
        fclose($f);
    }
    
    public static function logError($request='', $FILE_NAME = '', $LINE_NO = '', $ERROR_LINE = '', $ERROR = '')
    {
        $file = base_path()."/api_logs/error_log_".date("Y-m-d").".html";
        $data='';
        if (!file_exists($file)) {
            $data.='<table width="100%" border="1" style="color: red;">
            <tr>
            <td><h3>Date & Time</h3></td>
            <td><h3>Request URL</h3></td>
            <td><h3>Parameter</h3></td>
            <td><h3>Error</h3></td>
            <td><h3>IP</h3></td>
            </tr>';
        }
        
        $parameter = "";
        $posts = $request;
        if(!empty($posts)) {
            
            $parameter = '';

            foreach($posts as $key=>$val) {
                $parameter .= $key.' : '.json_encode($val).'<br>';
            }
        }
        
        $errorMessage = "FILE NAME :: $FILE_NAME <br>ERROR THROW LINE NO :: $LINE_NO <br>ERROR LINE NO :: $ERROR_LINE <br>ERROR :: $ERROR";
        
        $segment = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        $data.='<tr>
        <td>'.date("Y-m-d H:i:s").'</td>
        <td>'.$segment.'</td>
        <td>'.$parameter.'</td>
        <td>'.$errorMessage.'</td>
        <td>127.0.0.1</td>
        </tr>';
        
        $f=fopen($file, 'a');
        fwrite($f,$data."\r\r\n");
        chmod($file,0777);
        fclose($f);
    }
    
    
    public static function generateRandomString($length = 10)
    {
        
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    public static function send_notification_ios($token, $message)
    {
        // Provide the Host Information.
        //$tHost = 'gateway.sandbox.push.apple.com';
        //$tHost = 'gateway.push.apple.com';
        $tPort = 2195;

        //$tHost = 'api.sandbox.push.apple.com';
        $tHost = 'api.push.apple.com';

        // Provide the Certificate and Key Data.
        $tCert = './iOS_Notification.pem';
        
        // Provide the Private Key Passphrase (alternatively you can keep this secrete
        // and enter the key manually on the terminal -> remove relevant line from code).
        // Replace XXXXX with your Passphrase
        $tPassphrase = '';
        // Provide the Device Identifier (Ensure that the Identifier does not have spaces in it).
        // Replace this token with the token of the iOS device that is to receive the notification.
        
        $tToken = $token;
        
        
        // The message that is to appear on the dialog.
        $tAlert = 'You have a LiveCode APNS Message';
        // The Badge Number for the Application Icon (integer >=0).
        $tBadge = 0;
        // Audible Notification Option.
        $tSound = 'default';
        // The content that is returned by the LiveCode "pushNotificationReceived" message.
        $tPayload = 'APNS Message Handled by LiveCode';
        // Create the message content that is to be sent to the device.
        $tBody['aps'] = array (
                               'alert' => $message['msg'],
                               'badge' => $tBadge,
                               'sound' => $tSound,
                               );
        $tBody ['payload'] = $tPayload;
        $tBody ['tag'] = @$message['tag'];
        $tBody ['user_id'] = @$message['user_id'];
        
        //Added By Mehul
        if (array_key_exists("conversation_id", $message)) {
            $tBody ['conversation_id'] = @$message['conversation_id'];
        }
        if (array_key_exists("is_block", $message)) {
            $tBody ['is_block'] = @$message['is_block'];
        }
        
        // Encode the body to JSON.
        $tBody = json_encode ($tBody);

        /* OLD Code Comment By Mehul
        // Create the Socket Stream.
        $tContext = stream_context_create ();
        stream_context_set_option ($tContext, 'ssl', 'local_cert', $tCert);
        // Remove this line if you would like to enter the Private Key Passphrase manually.
        stream_context_set_option ($tContext, 'ssl', 'passphrase', $tPassphrase);
        // Open the Connection to the APNS Server.
        $tSocket = stream_socket_client ('ssl://'.$tHost.':'.$tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $tContext);
        // Check if we were able to open a socket.
        if (!$tSocket) {
            //exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);
        }
        // Build the Binary Notification.
        @$tMsg = chr (0) . chr (0) . chr (32) . @pack ('H*', $tToken) . @pack ('n', strlen ($tBody)) . $tBody;
        
        // Send the Notification to the Server.
        $tResult = fwrite ($tSocket, $tMsg, strlen ($tMsg));
        if ($tResult)
            $ios='1';
        else
            $ios='0';
        // Close the Connection to the Server.
        fclose ($tSocket);
        
        return $ios;
        */

    
        $package     = 'com.kmiou';

        $url = "https://$tHost/3/device/$tToken";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $tBody);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2_0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("apns-topic: $package"));
        curl_setopt($ch, CURLOPT_SSLCERT, $tCert);
        curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $tPassphrase);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        
        return $httpcode == 200 ? '1' : '0';
    }
    
    public static function send_notification_android($registatoin_ids, $message)
    {
        //$url = 'https://android.googleapis.com/gcm/send';
        $url = 'https://fcm.googleapis.com/fcm/send';
        
        $fields = array(
                        'registration_ids' => $registatoin_ids,
                        'data' => array("body" => array($message)),
                        );
        
        $headers = array(
                         'Authorization: key=AAAA6tDJf4Q:APA91bFkoP4qXumodt8Kmfpt0-iww3bZnDDHyFp8BqBOJrVL3ZKJCyEsZKOkP23v4jClW1EgjXDDwKB0Nwika_EQNhqVoABOXuQIKmHxT0FRi3dvyGJnaMYvX44mhxD5VnoCQeGSgP9f',
                         'Content-Type: application/json'
                         );
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        
        $result = curl_exec($ch);
        
        if ($result === FALSE) {
            //die('Curl failed: ' . curl_error($ch));
        }
        
        curl_close($ch);
        $response = explode("\n", $result);
        
        $responseBody = json_decode($response[count($response) - 1]);
        
        //Added By Mehul
        /*$responseArray['message'] = 'error';
        $responseArray['results'] = '';
        $responseArray['success'] = 0;*/
        
        /* Comment By Mehul
        if ($responseBody->success && !$responseBody->failure) {
            $responseArray['message'] = 'success';
            $responseArray['results'] = $responseBody->results;
            $responseArray['success'] = 1;
        } else if ($responseBody->success && $responseBody->failure) {
            $responseArray['message'] = $responseBody->success . ' sent';
            $responseArray['results'] = $responseBody->results;
            $responseArray['success'] = 0;
        } else if (!$responseBody->success && $responseBody->failure) {
            $responseArray['message'] = 'error';
            $responseArray['results'] = $responseBody->results;
            $responseArray['success'] = 0;
        }
        */
        return $response;
    }
        
    
    public static function getNotificationCount($user_id) {
                
        //Get Notification Count
        $getNotiCount = DB::select("SELECT Count(id) AS total_count FROM notification WHERE to_user_id = $user_id AND is_read = '0'");
        
        if(count($getNotiCount) > 0) {
            
            return $getNotiCount[0]->total_count;
        }
        
        return 0;
    }
    

}
    
?>
