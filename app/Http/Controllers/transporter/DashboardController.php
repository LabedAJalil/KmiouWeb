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
 
class DashboardController extends Controller
{
    private $timezone;

    public function show_dashboard()
    {
 
        $this->timezone=Auth::guard('transporter')->user()->timezone;
        
        $user_id = Auth::guard('transporter')->user()->id; 

          $active_list = array();  

          $check_user = User::find($user_id);
          
          $query = '';

          // for newly registered user
          if($check_user->email_verified_at != null){
            
            $query .= ' AND (shipment.created_at >= "'.$check_user->email_verified_at.'" ) ';

          }

              $select_shipment = DB::select(' select shipment.id as shipment_id,shipment.unique_id,shipment.bid_status,shipment.pickup,shipment.drop,shipment.status, shipment.report_emergency, shipment.status_when_report,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.profile_pic as shipper_profile_pic,users.mobile_no as user_mobile,shipment.id as shipment_id,users.id as user_id,shipment.updated_at,shipment.created_at,info.quotation_type, info.pickup_date
                from shipment 
                left join users on users.id = shipment.user_id 
                left join shipment_info as info on info.shipment_id=shipment.id
                left join shipment_bid as bid on bid.shipment_id = shipment.id
                where (shipment.status IN ("2","4","5","8","9") AND shipment.transporter_id = '.$user_id.' '.$query.') group by shipment.unique_id order by info.pickup_date desc limit 3 ');
                //where ((shipment.status = "0" AND info.quotation_type = "0" AND bid.user_id = '.$user_id.' AND bid.status = "0" AND shipment.bid_status = "0") OR (shipment.status IN ("1","2","4","5","8") AND shipment.transporter_id = '.$user_id.')) '.$query.' group by shipment.unique_id order by shipment.updated_at desc limit 3'); //OLD Where Condition
          //dd($select_shipment);  
            
            if($select_shipment != null){

              foreach ($select_shipment as $key => $value) {
                
                $data1 = array();

                $data1['shipment_id'] = $value->shipment_id;
                $data1['ship_id'] = $value->unique_id;
                $data1['shipper_id'] = $value->user_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;
                $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                $data1['drop'] = is_null($value->drop)?'':$value->drop;
                $data1['status'] = is_null($value->status)?'':$value->status;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);
                
                array_push($active_list,$data1);
              }
            }

        return view('transporter.dashboard',compact('active_list'));  
    }  

    public function dashboard_filter(Request $request)
    {
        $user_id = Auth::guard('transporter')->user()->id; 

        $filter_type = $request->filter_type;
        
        $transporter = User::find($user_id);

        $transporter->filter_type = $filter_type;

        $transporter->save();

        $data1 = array();
        
        $data1['total_accepted_shipment'] = Helper::get_user_accepted_shipment_count($user_id,$filter_type);
        $data1['total_cancelled_shipment'] = Helper::get_user_cancelled_shipment_count($user_id,$filter_type);
        $data1['total_reported_shipment'] = Helper::get_user_reported_shipment_count($user_id,$filter_type);
        $data1['total_request_shipment'] = Helper::get_user_total_request_count($user_id,$filter_type);
        $data1['total_driver'] = Helper::get_user_total_driver($user_id);
        $data1['pending_accepted_award_count'] = Helper::get_pending_accepted_award_count($user_id,$filter_type);
        $data1['pending_assign_driver_count'] = Helper::get_pending_assign_driver_count($user_id,$filter_type);
        $data1['total_bidded_trip_count'] = Helper::get_total_bidded_trip_count($user_id,$filter_type);
        

        return json_encode(['success' => 1, 'msg' => 'Success','result' => $data1 ]);
    }

    public function show_help_feedback()
    {

      return view('transporter.help_feedback');  
    } 

    public function show_rate_shipment()
    {
      return view('transporter.rate_shipment');  
    } 

    public function show_profile()
    {
      $user_id = Auth::guard('transporter')->user()->id; 

        $user = User::find($user_id);

        $doc = array();

       if($user && $user->doc != '' && $user->doc != null){

          $str = $user->doc;

          $doc = explode ("#####", $str);
       }

      return view('transporter.profile',compact('user','doc')); 

    }

     public function update_profile(Request $request)
    {
        $user_id = Auth::guard('transporter')->user()->id; 

        $user = User::find($user_id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->mobile_no = $request->mobile_no;
        $user->address = $request->address;
        $user->no_of_vehicle = $request->no_of_vehicle;
        $user->push_notification = $request->push_notification;

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
        
        $profile_url = null;

        if($request->hasFile('profile_pic')){

            $validator = Validator::make($request->all(), [
              'profile_pic.*' => 'required|mimes:jpg,jpeg,png',
            ]);
            
            if ($validator->fails()) {
              return back()->with('alert-warning', 'Only image files allowed !!');
            } 
            else
            {
              $pro_pic_url = null;          
              $pro_pic = $request->file('profile_pic');
              $name = time().'.'.$pro_pic->getClientOriginalExtension();

              $destinationPath = public_path('images/user');
              $pro_pic->move($destinationPath, $name);

              $pro_pic_url = asset('public/images/user').'/'.$name;

              $profile_url = $pro_pic_url;
            }
        }
        
        $user->doc = $doc_url;
        $user->profile_pic = $profile_url;
        $user->company_name = $request->company_name;
        $user->carrier_number = $request->carrier_number;
        $user->save();

        return back()->with('alert-success','Profile Updated !!!');
    }

    public function update_online_status(Request $request)
    {   
    try{
            $user_id = Auth::guard('transporter')->user()->id; 

            $check_user = User::find($user_id);
            
            if($check_user != null)
            {   
                $check_user->is_active = $request->is_active;
                
                $check_user->save();


                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Status Updated Successfully !!','result' => [] ]));
                return json_encode(['success' => 1, 'msg' => 'Status Updated Successfully !!','result' => [] ]);

            }else{
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'User not found','result' => []]));
                return json_encode(['success' => 0, 'msg' => 'User not found','result' => []]);
            }

        }catch(Exception $ex) {
                
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage() ]);
        }
    }

    public function show_notification_list()
    {
      $this->timezone = Auth::guard('transporter')->user()->timezone;

      $user_id = Auth::guard('transporter')->user()->id;

       $user_exists = User::find($user_id);
        
        $notification_list = array();
        
        $notification = DB::select('SELECT notification.* from notification where notification.to_user_id = '.$user_id.' order by created_at desc ');
    
        //Read All Notification
        DB::statement("UPDATE notification SET is_read = '1' WHERE to_user_id = ".$user_id);

        if(count($notification)>0)
        {
            foreach ($notification as $key => $value) {

              $from_user = User::find($value->from_user_id);
           
              $shipment = Shipment::find($value->ref_id);
              

               $data = array();
               $data['id'] = $value->id;
               $data['from_user_id'] = $value->from_user_id;
               $data['to_user_id'] = $value->to_user_id;
               $data['user_name'] = is_null($from_user->first_name)?'':$from_user->first_name.' '.(is_null($from_user->first_name)?'':$from_user->first_name);
               $data['title'] = $value->title;
               $data['message'] = $value->message;
               $data['noti_type'] = $value->noti_type;  
               $data['ref_id'] = $value->ref_id;
               $data['status'] = $shipment->status;
               $data['is_read'] = $value->is_read;
               $data['created_at'] =  Helper::convertDateWithTimezone($value->created_at, 'd-M-Y h:i A', $this->timezone);
               
               array_push($notification_list, $data);
            }
        }

      return view('transporter.notification',compact('notification_list'));  
    }  
  
    public function show_change_password(Request $request)
    { 
        $user_id = Auth::guard('transporter')->user()->id; 

        $user = User::find($user_id);

        return view('transporter.change_password',compact('user')); 
    }
}