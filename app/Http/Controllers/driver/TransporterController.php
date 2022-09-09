<?php

namespace App\Http\Controllers\driver;

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
 
class TransporterController extends Controller
{
    private $timezone;

    public function show_transporter_list()
    {
  		$this->timezone=Auth::guard('driver')->user()->timezone;
        
        $user_id = Auth::guard('driver')->user()->id; 

    	$check_driver = Driver::where('driver_id',$user_id)->where('status','=','1')->orderBy('created_at','desc')->get();    
            
        $transporter_list = array();

        if($check_driver != null){
            
            foreach ($check_driver as $key => $value) {
                
                $data1 = array();
                
                $select_user = User::find($value->transporter_id);

                if($select_user != null){

                    $data1['user_id'] = $select_user->id;
                    $data1['user_name'] = ($select_user->first_name == null)?'':$select_user->first_name.' '.(($select_user->last_name == null)?'':$select_user->last_name);
                    $data1['profile_pic'] = ($select_user->profile_pic == null)?'':$select_user->profile_pic;
                    $data1['email'] = ($select_user->email == null)?'':$select_user->email;
                    $data1['mobile'] = ($select_user->mobile_no == null)?'':$select_user->mobile_no;
                    $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);
                    
                    array_push($transporter_list,$data1);   
                }
            }
        }

        $check_request = Driver::where('driver_id',$user_id)->where('status','=','0')->orderBy('created_at','desc')->get();    
            
        $request_list = array();

        if($check_request != null){
            
            foreach ($check_request as $key => $value) {
                
                $data1 = array();
                
                $select_user = User::find($value->transporter_id);

                if($select_user != null){

                    $data1['request_id'] = $value->id;
                    $data1['user_id'] = $select_user->id;
                    $data1['user_name'] = ($select_user->first_name == null)?'':$select_user->first_name.' '.(($select_user->last_name == null)?'':$select_user->last_name);
                    $data1['profile_pic'] = ($select_user->profile_pic == null)?'':$select_user->profile_pic;
                    $data1['email'] = ($select_user->email == null)?'':$select_user->email;
                    $data1['mobile'] = ($select_user->mobile_no == null)?'':$select_user->mobile_no;
                    $data1['created_at'] = Helper::convertDateWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);
                    
                    array_push($request_list,$data1);   
                }
            }
        }

        return view('driver.transporter.transporter_list',compact('transporter_list','request_list'));  
    }

    public function accept_reject_join_request(Request $request){
    try{
            
            $user_id = Auth::guard('driver')->user()->id; 

            $driver_request = Driver::find($request->request_id);

            $msg = 'Success';
            $message = '';
            
            if($driver_request != null && $driver_request->status == '0'){

	            if($request->is_accept == '1'){

	                $driver_request->status = '1';
	                $msg = 'Request Accepted Successfully';
                    $message = 'Accepted';

	            }else{

	                $driver_request->status = '2';
	                $msg = 'Request Rejected Successfully';
                    $message = 'Rejected';
	            }
	           
	            $driver_request->save();

	            session()->flash('alert-success',$msg);

	            Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
	            return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
	            
            }else{

            	if($driver_request && $driver_request->status == '1'){

	                $msg = 'Request Already Accepted';
            	}else{

	                $msg = 'Request Already Rejected';
            	}
	            
	            session()->flash('alert-warning',$msg);

            	Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
	            return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
            }
    
        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage() ]);
        }
    }

    /*truck apis*/

    public function show_truck_list_for_add(Request $request)
    {
        $this->timezone=Auth::guard('driver')->user()->timezone;
        
        $user_id = Auth::guard('driver')->user()->id; 

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
        $this->timezone=Auth::guard('driver')->user()->timezone;
        
        $user_id = Auth::guard('driver')->user()->id; 

        $check_truck = Transporter_truck::where('user_id',$user_id)->where('truck_id',$request->truck_id)->where('status','!=',"2")->first();

        if($check_truck == null){

             // remove other trucks
            DB::table('transporter_truck')->where('user_id',$user_id)->where('truck_id','!=',$request->truck_id)->update(['status' => '2']);

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

        $this->timezone=Auth::guard('driver')->user()->timezone;
        
        $user_id = Auth::guard('driver')->user()->id; 

        $truck = Transporter_truck::where('user_id',$user_id)->where('truck_id',$request->truck_id)->where('status','!=','2')->orderBy('created_at','desc')->first(); 

          if($truck != null){

            $truck->status = $request->status;

            $truck->save();

          } 
         return json_encode(['success' => 1, 'msg' => 'Truck Status Changed','result' => '[]']);
    }


    public function remove_added_truck(Request $request)
    {
        $this->timezone=Auth::guard('driver')->user()->timezone;
        
        $user_id = Auth::guard('driver')->user()->id; 

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

    /* driver truck list*/


// end controller function
}