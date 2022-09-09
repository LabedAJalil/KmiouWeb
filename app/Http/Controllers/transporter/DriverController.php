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
use App\Truck;
use App\Transporter_truck;
 
class DriverController extends Controller
{
    private $timezone;

     public function add_driver(Request $request)
     {

    try{
            $this->timezone=Auth::guard('transporter')->user()->timezone;
            
            $user_id = Auth::guard('transporter')->user()->id; 

            $doc_url = null;

            $first_name = $request->first_name;
            $last_name = $request->last_name;
            //$email = $request->email;
            $email = $request->mobile_no.'@mobile.com';
            $user_type = '4';
            $password =  Hash::make($request->password);    
            $verification_code = rand(1111,9999);
            
            //Check Email Is Exist Or Not
            $check_participate = User::where('email', '=', $email)->where('status', '!=',"2")->first();
            $check_mobile_no = User::where('mobile_no', '=', $request->mobile_no)->where('status', '!=',"2")->first();

            if(is_null($check_participate) && (is_null($check_mobile_no))){
               
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
                    
                $user  = new User;
                
                $user->first_name = $first_name;
                $user->last_name = $last_name;
                $user->email = $email;
                $user->user_type = $user_type;
                $user->password = $password;
                $user->profile_pic = is_null($request->profile_pic)?null:$request->profile_pic;
                $user->mobile_no = is_null($request->mobile_no)?null:$request->mobile_no;
                
                $user->ref_id = is_null($user_id)?0:$user_id;
                $user->carrier_number = is_null($request->carrier_number)?0:$request->carrier_number;
                $user->language = is_null($request->language)?1:$request->language;
                $user->doc = $doc_url;
                $user->is_verify = '1'; 
                $user->status = '1';
                $user->approve = '1';
                
                $user->save();

                $driver = new Driver;
                $driver->transporter_id = $user_id;
                $driver->driver_id = $user->id;
                $driver->status = '1';
                $driver->save();

                $truck_type = new Transporter_truck;                        
                $truck_type->user_id = $user->id; 
                $truck_type->truck_id = $request->truck_id; 
                $truck_type->status = '1';                 
                $truck_type->save(); 

                session()->flash('alert-success','Driver Added Successfully');
                return redirect(route('transporterShowDriverList'));
                
            }
            else
            {
                $msg=trans('Mobile Number Already Exists');          
                
                session()->flash('alert-warning',$msg);
                return redirect(route('transporterShowDriverList'));
            }
        }catch(Exception $ex) {
                
            return back()->with('alert-warning', $ex->getMessage());
        }
    }

    public function show_driver_list()
    {
    try{
            $this->timezone=Auth::guard('transporter')->user()->timezone;
            
            $user_id = Auth::guard('transporter')->user()->id; 

        	//$check_driver = Driver::where('transporter_id',$user_id)->where('status','=','1')->orderBy('created_at','desc')->get();    
            $check_driver = DB::select('select driver.*,users.first_name as first_name,users.last_name as last_name, (SELECT truck_img FROM truck WHERE id = (SELECT truck_id FROM transporter_truck WHERE user_id = driver.driver_id) ) AS truck_img, (SELECT truck_name FROM truck WHERE id = (SELECT truck_id FROM transporter_truck WHERE user_id = driver.driver_id) ) AS truck_name
             from driver 
            left join users on users.id = driver.driver_id 
            where driver.transporter_id='.$user_id.'  AND driver.status = "1" AND users.status = "1" order by driver.created_at desc ');
                
            $driver_list = array();

            if($check_driver != null){
                
                foreach ($check_driver as $key => $value) {
                    
                    $data1 = array();
                    
                    $select_user = User::find($value->driver_id);

                    if($select_user != null && $select_user->status == '1'){

                        $data1['user_id'] = $select_user->id;
                        $data1['user_name'] = ($select_user->first_name == null)?'':$select_user->first_name.' '.(($select_user->last_name == null)?'':$select_user->last_name);
                        $data1['profile_pic'] = ($select_user->profile_pic == null)?'':$select_user->profile_pic;
                        $data1['email'] = ($select_user->email == null)?'':$select_user->email;
                        $data1['truck_img'] = ($value->truck_img == null)?'':$value->truck_img;
                        $data1['truck_name'] = ($value->truck_name == null)?'':$value->truck_name;
                        $data1['mobile'] = ($select_user->mobile_no == null)?'':$select_user->mobile_no;
                        $data1['doc'] = ($select_user->doc == null)?'':$select_user->doc;
                        $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);
                        
                        array_push($driver_list,$data1);   
                    }
                }
            }

            return view('transporter.driver.driver_list',compact('driver_list'));  
            
        }catch(Exception $ex) {
                
            return back()->with('alert-warning', $ex->getMessage());
        }
    }

    public function show_truck_list_for_driver_add(Request $request)
    {
    try{
            $this->timezone=Auth::guard('transporter')->user()->timezone;
            
            $user_id = Auth::guard('transporter')->user()->id; 

            $query = '';

            if($request->search_string != null && $request->search_string != ''){
         
                $query .= '  AND ( (LOWER(CAST(truck.truck_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.$request->search_string.'%")  OR (LOWER(CAST(truck.capacity AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.$request->search_string.'%") )';
            }

            $check_truck = DB::select(' Select * from truck where status = "1" '.$query.' order by created_at desc ');
            
            $truck_list = array();

            if($check_truck != null){
                
                foreach ($check_truck as $key => $value) {
                    
                    $data1 = array();

                    $data1['truck_id'] = $value->id;
                    $data1['truck_name'] = ($value->truck_name == null)?'':$value->truck_name;
                    $data1['truck_img'] = ($value->truck_img == null)?'':$value->truck_img;
                    $data1['truck_type'] = ($value->truck_type == null)?'':$value->truck_type;
                    $data1['capacity'] = ($value->capacity == null)?'':$value->capacity;
                    $data1['weight_type'] = ($value->weight_type == null)?'':$value->weight_type;
                    $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);
                    
                    array_push($truck_list,$data1);   
                }
            }

            return json_encode(['success' => 1, 'msg' => 'Success','result' => $truck_list ]);        
            
        }catch(Exception $ex) {
                
            return json_encode(['success' => 0, 'msg' => $ex->getMessage(),'result' => [] ]);
        }
    }



    public function update_driver_doc(Request $request)
        {         
          /* Update transporter */
        
          $user = User::find($request->input('driver_id'));

          $doc_url = $user->doc;
          
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

                  if($user->doc == null || $user->doc == ''){
                      $doc_url .= $pro_pic_url;
                  }else{
                      $doc_url .= '#####'.$pro_pic_url;
                  }

                }
              }
          }
          
          $user->doc = $doc_url;

          $user->save();
            
        return redirect(route("transporterShowDriverList"));

    }


    public function show_driver_list_for_assign()
    {
        $this->timezone=Auth::guard('transporter')->user()->timezone;
        
        $user_id = Auth::guard('transporter')->user()->id; 

        //$check_driver = Driver::where('transporter_id',$user_id)->where('status','=','1')->orderBy('created_at','desc')->get();    
        $check_driver = DB::select('select driver.*,users.first_name as first_name,users.last_name as last_name, (SELECT truck_img FROM truck WHERE id = (SELECT truck_id FROM transporter_truck WHERE user_id = driver.driver_id) ) AS truck_image
             from driver 
            left join users on users.id = driver.driver_id 
            where driver.transporter_id='.$user_id.'  AND driver.status = "1" AND users.status = "1" order by driver.created_at desc ');
            
        $driver_list = array();

        if($check_driver != null){
            
            foreach ($check_driver as $key => $value) {
                
                $data1 = array();
                
                $select_user = User::find($value->driver_id);

                if($select_user != null && $select_user->status == '1'){

                    $data1['user_id'] = $select_user->id;
                    $data1['user_name'] = ($select_user->first_name == null)?'':$select_user->first_name.' '.(($select_user->last_name == null)?'':$select_user->last_name);
                    $data1['profile_pic'] = ($select_user->profile_pic == null)?'':$select_user->profile_pic;
                    $data1['truck_img'] = ($value->truck_image == null)?'':$value->truck_image;
                    $data1['email'] = ($select_user->email == null)?'':$select_user->email;
                    $data1['mobile'] = ($select_user->mobile_no == null)?'':$select_user->mobile_no;
                    $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);
                    
                    array_push($driver_list,$data1);   
                }
            }
        }

        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $driver_list ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' => $driver_list ]);        
    }  
    

    public function remove_join_driver(Request $request)
    {
    	$this->timezone=Auth::guard('transporter')->user()->timezone;
        
        $user_id = Auth::guard('transporter')->user()->id; 

    	$driver = Driver::where('transporter_id',$user_id)->where('driver_id',$request->driver_id)->where('status','!=','2')->orderBy('created_at','desc')->first(); 

            if($driver && $driver->status == '1'){
	           
	            $driver->status = '2';
	           
	            $driver->save();
	            
                $user = User::find($request->driver_id);
                $user->status = '2';
                $user->save();

	        	$msg = 'Driver Removed Successfully';

	        	session()->flash('alert-success', $msg);

	            Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
	            return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
	    
	        }else{

	        	$msg = 'Driver Already Removed';
	        	
	        	session()->flash('alert-warning', $msg);

	        	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
	        }

    } 

    /* transporter truck list*/
    

    public function show_truck_list()
    {
        $this->timezone=Auth::guard('transporter')->user()->timezone;
        
        $user_id = Auth::guard('transporter')->user()->id; 

        $check_truck = Transporter_truck::where('user_id',$user_id)->where('status','!=','2')->orderBy('created_at','desc')->get();    
            
        $truck_list = array();

        if($check_truck != null){
            
            foreach ($check_truck as $key => $value) {
                
                $data1 = array();
                
                $select_truck = Truck::find($value->truck_id);

                if($select_truck != null){

                    $data1['truck_id'] = $value->truck_id;
                    $data1['truck_name'] = ($select_truck->truck_name == null)?'':$select_truck->truck_name;
                    $data1['truck_img'] = ($select_truck->truck_img == null)?'':$select_truck->truck_img;
                    $data1['truck_type'] = ($select_truck->truck_type == null)?'':$select_truck->truck_type;
                    $data1['capacity'] = ($select_truck->capacity == null)?'':$select_truck->capacity;
                    $data1['weight_type'] = ($select_truck->weight_type == null)?'':$select_truck->weight_type;
                    $data1['status'] = ($value->status == null)?'':$value->status;
                    $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);
                    
                    array_push($truck_list,$data1);   
                }
            }
        }

        return view('transporter.truck.truck_list',compact('truck_list'));  
    }

    public function show_truck_list_for_add(Request $request)
    {
        $this->timezone=Auth::guard('transporter')->user()->timezone;
        
        $user_id = Auth::guard('transporter')->user()->id; 

        $select_users_truck = DB::select(' Select GROUP_CONCAT(truck_id) as truck_id from transporter_truck where user_id = '.$user_id.' AND status != "2" order by created_at desc ');

        $query = '';

        if($select_users_truck[0]->truck_id != null ){
            $query = ' AND id not in ('.$select_users_truck[0]->truck_id.') ';
        }

        if($request->search_string != null && $request->search_string != ''){
     
            $query .= '  AND ( (LOWER(CAST(truck.truck_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.$request->search_string.'%")  OR (LOWER(CAST(truck.capacity AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%'.$request->search_string.'%") )';
        }

        $check_truck = DB::select(' Select * from truck where status = "1" '.$query.' order by created_at desc ');

        $truck_list = array();

        if($check_truck != null){
            
            foreach ($check_truck as $key => $value) {
                
                $data1 = array();

                $data1['truck_id'] = $value->id;
                $data1['truck_name'] = ($value->truck_name == null)?'':$value->truck_name;
                $data1['truck_img'] = ($value->truck_img == null)?'':$value->truck_img;
                $data1['truck_type'] = ($value->truck_type == null)?'':$value->truck_type;
                $data1['capacity'] = ($value->capacity == null)?'':$value->capacity;
                $data1['weight_type'] = ($value->weight_type == null)?'':$value->weight_type;
                $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);
                
                array_push($truck_list,$data1);   
            
            }
        }
        
        return json_encode(['success' => 1, 'msg' => 'Success','result' => $truck_list ]);        
    } 

    public function add_new_truck(Request $request)
    {
        $this->timezone=Auth::guard('transporter')->user()->timezone;
        
        $user_id = Auth::guard('transporter')->user()->id; 

        $check_truck = Transporter_truck::where('user_id',$user_id)->where('truck_id',$request->truck_id)->where('status','!=',"2")->first();

        if($check_truck == null){

            $truck = new Transporter_truck;
            $truck->user_id = $user_id;
            $truck->truck_id = $request->truck_id;
            $truck->status = '1';
            $truck->save();

            $msg=trans('Truck Added Successfully');          
            session()->flash('alert-success',$msg);
            return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
        }
        else
        {
            $msg=trans('Truck Already Added');          
            
            session()->flash('alert-warning',$msg);
            return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
        }
    }


    public function change_truck_status(Request $request)
    {

        $this->timezone=Auth::guard('transporter')->user()->timezone;
        
        $user_id = Auth::guard('transporter')->user()->id; 

        $truck = Transporter_truck::where('user_id',$user_id)->where('truck_id',$request->truck_id)->where('status','!=','2')->orderBy('created_at','desc')->first(); 

          if($truck != null){

            $truck->status = $request->status;

            $truck->save();

          } 
         return json_encode(['success' => 1, 'msg' => 'Truck Status Changed','result' => '[]']);
    }


    public function remove_added_truck(Request $request)
    {
        $this->timezone=Auth::guard('transporter')->user()->timezone;
        
        $user_id = Auth::guard('transporter')->user()->id; 

        $truck = Transporter_truck::where('user_id',$user_id)->where('truck_id',$request->truck_id)->where('status','!=','2')->orderBy('created_at','desc')->first(); 

            if($truck != null){
               
                $truck->status = '2';
               
                $truck->delete();

                $msg = 'Truck Removed Successfully';

                session()->flash('alert-success', $msg);

                return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
        
            }else{

                $msg = 'Truck Already Removed';
                
                session()->flash('alert-warning', $msg);

                return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
            }

    } 


    /* transporter truck list*/


// end controller function
}