<?php

namespace App\Http\Controllers;

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
use Illuminate\Support\Facades\Auth;
use App\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\User;
use App\Notification;
use App\Transporter_truck;

require './vendor/Twilio/autoload.php';

use Twilio\Rest\Client;

class UserController extends Controller
{
    	public function show_verify_user($user_id){

		return view('otp',compact('user_id'));
	}

	public function delete_notification(Request $request){

		$user = User::find($request->user_id);

        if($user != null){

            $notification = Notification::find($request->notification_id);
            
            $notification->delete();
        
        }
        return json_encode(['success' => 1, 'msg' => 'Success','result' => [] ]);

	}

	public function verify_user(Request $request){
		
		$check_user = User::find($request->user_id);
		
		if($check_user != null && $check_user->verification_code == $request->verification_code){
			
			$check_user->is_verify = '1';
			$check_user->verification_code = '0';
			$check_user->save();
			
			return redirect()->route('showLogin')->with('alert-success','User Email Address Verified Successfully !!!');

		}else if($check_user->verification_code != $request->verification_code){

			return back()->with('alert-danger','Please Enter Valid OTP !!!');
		
		}else{

			return view('user/link_expire');
		}
	}	

	public function show_register(){

		$city = DB::select('select * from city where status = "1" ');
		$country_code = DB::select('select * from tbl_country_code where status = "1" ');
		$truck = DB::select('select * from truck where status = "1" ');
		
		return view('register',compact('city','truck','country_code'));
	}

	public function check_email_exists(Request $request){

		$check_user_email = DB::select('select * from users where email = "'.$request->email.'" and users.status != "2" order by created_at desc limit 1 ');
		
		if($check_user_email == null){
				
				Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' =>[] ]));    
                return json_encode(['success' => 1, 'msg' =>  'Success','result' => []]);
		}else{
				Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Email Already Taken','result' =>[] ]));    
                return json_encode(['success' => 0, 'msg' =>  'Email Already Taken','result' => []]);
		
		}		
	}


	public function register(Request $request)
	{	
		try{
		$check_user_email = null;

		if($request->email != '' && $request->email != null){
			
			$check_user_email = DB::select('select * from users where email = "'.$request->email.'" and users.status != "2" order by created_at desc limit 1 ');
		}
	
		if($check_user_email == null){

			$email = '';
			
			if($request->user_type == "4" && $request->email == "") {//Driver
                $email = $request->mobile_no.'@mobile.com';
            }else{

                $email = $request->email;
            }

			$verification_code = rand('1000','9999');

			$doc_url = null;

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

                session()->flash('alert-warning', $ex->getMessage());
        		return redirect(route('showRegister'));
            }

			if($request->hasFile('doc')){

				$validator = Validator::make($request->all(), [
					'doc' => 'required',
              		'doc.*.file' => 'image|mimes:jpg,jpeg,png',
				]);
				
				foreach ($request->file('doc') as $key => $request_doc) {  

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
	                        $doc_url = $pro_pic_url;
	                    }else{
	                        $doc_url .= '#####'.$pro_pic_url;
	                    }

					}
				}

			}

			$user = new User;			
			$user->first_name = $request->first_name;
			$user->last_name = $request->last_name;
			$user->email = $email;
			$user->mobile_no = $request->mobile_no;
			$user->password =  Hash::make($request->password);
			$user->address = $request->address;
			$user->city = trim(substr($request->city, strpos($request->city, '-') + 1)); 
			/*$user->state = $request->state;
			$user->country = $request->country;
			$user->zipcode = $request->zipcode;*/
			$user->verification_code = $verification_code;
			$user->user_type = $request->user_type;
			$user->doc = $doc_url;
			$user->equipment_use = is_null($request->equipment_use)?0:$request->equipment_use;
			$user->operated_equipment_type = is_null($request->operated_equipment_type)?0:$request->operated_equipment_type;
			$user->truck_count = is_null($request->truck_count)?0:$request->truck_count;
			$user->shipment_per_month = is_null($request->shipment_per_month)?0:$request->shipment_per_month;
			$user->shipping_city = is_null($request->shipping_city)?0:$request->shipping_city;
			$user->headquarters_city = is_null($request->headquarters_city)?0:$request->headquarters_city;
			$user->language = is_null($request->language)?0:$request->language;
			$user->udid = $request->ip();
			$user->device_type = '0';
			$user->device_token = null;
			$user->ref_id = is_null($request->user_id)?0:$request->user_id;
			$user->carrier_number = isset($request->carrier_number)?$request->carrier_number:null;
			$user->owner_id_doc = is_null($request->owner_id_doc)?null:$request->owner_id_doc;
			$user->shipper_type = $request->register_as; // 0: individual , 1: Company (this is only for shipper so other case it will be  by default 0)
            $user->country_code = $request->country_code;                
			$user->status = '0';

			if($user->user_type == "2" && $request->register_as == "0") {
	            $user->status = '1'; 
	            $user->approve = '1'; 
	        } else {
	            $user->status = '0'; 
	            $user->approve = '0'; 
	        }

	        /*if($user->user_type == '2'){
                    
                $user->truck_count = 0;
                $user->shipment_per_month = is_null($request->truck_count)?0:$request->truck_count;
            }*/

			/*if($request->user_type == '2'){

				$user->status = '1';
				$user->approve = '1';
			}*/

			$user->save();

			if($request->user_type == '3'){

				if(count($request->truck_type) > 1 ){
				
					foreach ($request->truck_type as $key => $value) {
						
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
				$truck_type->truck_id = $request->single_truck_type; 
				$truck_type->status = '1'; 
				
				$truck_type->save(); 
			}

			$user_type = '';
            if($request->user_type = '2'){

                $user_type = 'Shipper';
            
            }else if($request->user_type = '3'){

                $user_type = 'Transporter';
            
            }else if($request->user_type = '4'){

                $user_type = 'Driver';
            }

            $user_name = is_null($user->first_name)?'':$user->first_name.' '.(is_null($user->last_name)?'':$user->last_name);

			// new user mail to admin
            $user_detail2 =array();
            $user_detail2['user_type'] = $user_type;
            $user_detail2['user_name'] = $user_name;
            $user_detail2['email'] = is_null($user->email)?'':$user->email;
            $user_detail2['date'] = date("Y-m-d H:i", strtotime('+60 minutes'));


            Mail::send('emails.new_user_info', ['user' => (object)$user_detail2], function($message) use ($user) {
                $message->from(env('MAIL_USERNAME'), 'KMIOU');
                $message->to(env('MAIL_ADMIN'));
                $message->subject('KMIOU NEW USER');
            });

            if($user->email != null && $user->email != ''){

				Mail::send('emails.verification_link', ['user' => $user], function($message) use ($user) {

	                $message->from(env('MAIL_USERNAME'), 'KMIOU');
	                $message->to($user->email);
	                $message->subject('KMIOU Verification Code');
	            });
            }

			session()->flash('alert-success', 'Verification Code Sent to You, Please Verify OTP. !!');
        	return redirect(route('showVerifyUser',array('user_id'=>$user->id)));  

		}else{
			session()->flash('alert-warning', 'Email Already Exists !!');
        	return redirect(route('showRegister'));  
		}

		}catch(Exception $ex){
                  
          session()->flash('alert-warning', $ex->getMessage());
	      return redirect(route('showRegister'));
        }  
	}

	public function resend_code(Request $request)
    {   
      	$user = User::find($request->user_id);

        if($user != null){   
            
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

                try{

		            $sid = env('ACCOUNT_SID');
		            $token = env('AUTH_TOKEN');  
		            $twilio = new Client($sid, $token);
		            
		            $message = $twilio->messages->create($user->country_code.$user->mobile_no, // to
		                   [
		                        "body" => "KMIOU Verification Code :: ".$verification_code,
		                        "from" => "+14086693128"
		                   ]
		              );

		            }catch(Exception $ex){
		            	
		                session()->flash('alert-warning', $ex->getMessage());
		        		return redirect(route('showRegister'));
		            }

                Mail::send('emails.verification_link', ['user' => (object)$user_detail], function($message) use ($user) {
                    $message->from(env('MAIL_USERNAME'), 'KMIOU');
                    $message->to($user->email);
                    $message->subject('KMIOU Verification Code');
                });
                 
                return back()->with('alert-success','Verification code sent in email !!!');
            }
            else
            {  
            	return back()->with('alert-warning','User Not Found Verified !!!');
            }
        }else{
			
			return back()->with('alert-warning','User Not Found !!!');
		}
       
    }

	public function get_reset_password($user_id,$verification_code)
    {   
      	$user = DB::table('users')->where('id','=',$user_id)->where('forgot_token','=',$verification_code)->first();
        if($user && $user != null){

			return view('reset_password',compact('user'));
        
        }else{
			
			return view('user/link_expire');
		}
       
    }

    public function reset_new_password(Request $request)
    {   
      	$New_pass = $request->new_password;
		$Con_pass = $request->confirm_password;
    	
		$user = DB::table('users')->where('email','=',$request->email)->first();

		if($New_pass == $Con_pass)
		{
			DB::table('users')->where('email', $request->email)->update(['password' => Hash::make($Con_pass),'forgot_token' => null]);
			
			return redirect()->route('showLogin')->with('alert-success','Password Changed Successfully !!!');
		}
		else
		{
			return back()->with('alert-danger','New Password & Confirmed Password Not Matched !!!');
		}
       
    }

    /*change password*/

    public function change_password(Request $req)
    {
        $change = DB::table('users')->where('id','=',$req->user_id)->first();

         if($change)
        {
            if(Hash::check($req->old_password,$change->password))
            {
                
                $user = DB::table('users')->where('email','=',$req->email)->first();

                $New_pass = $req->new_password;
                $Con_pass = $req->confirm_password;
                if($New_pass == $Con_pass)
                {
                      $change_password = User::find($req->user_id);
                     
                      $change_password->password=Hash::make($req->new_password); 
                      $change_password->save();

                    session()->flash('alert-success', 'password changed successfully');
                    return redirect()->back();
                }
                else
                {
                    return back()->with('alert-danger','New Password & Confirmed Password Not Matched !!!');
                }
            }
            else{
                return back()->with('alert-danger','old password is wrong');
             }

        }else{
            return back()->with('alert-danger','user not found');
        }

    }
    
    /*change password*/

    public function contact_us(Request $request)
	{
				
		$data = DB::table('contact_us')->insert(['user_id' => '0','name' => $request->name, 'email' => $request->email,'subject' => $request->subject,'msg' => $request->msg]);
		
			//session()->flash('alert-success', 'Message Sent Successfully !!');

			return back()->with('alert-success', 'Message Sent Successfully !!');
        
	}

    public function show_contact_us_list(Request $request){

		$data = DB::select('select contact_us.*
       	from contact_us order by created_at desc ');
		
		return view('admin/contact_us_list',compact('data'));
	}
	
	public function show_shipment_detaintion_info(Request $request){

		$data = DB::select('select shipment_surge_price.*
       	from shipment_surge_price where shipment_id = '.$request->shipment_id.' limit 1 ');

        return json_encode(['success' => 1, 'msg' => trans('Success'),'result' => array($data) ]);
		
	}
	
	

// end controller function
}