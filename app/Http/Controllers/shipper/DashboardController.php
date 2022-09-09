<?php

namespace App\Http\Controllers\shipper;

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
use App\Card;
use App\Shipment;
use App\Goods_type;
use App\Shipment_bid;
use App\Review;
use App\Driver;
 
class DashboardController extends Controller
{
	
    public function show_dashboard()
    {

      $this->timezone = Auth::guard('shipper')->user()->timezone;

        $user_id = Auth::guard('shipper')->user()->id; 

          $active_list = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,info.quotation_type
            FROM shipment
            left join shipment_info as info on info.shipment_id=shipment.id
            left join users on users.id=shipment.user_id
           where shipment.status IN ("2","4","5","8","9") AND shipment.user_id = '.$user_id.' group by shipment.unique_id order by shipment.updated_at desc limit 3 ');
            
            if($select_shipment != null){

              foreach ($select_shipment as $key => $value) {
                
                $data1 = array();

                $status_color = '';
                $status_string = '';

                if($value->report_emergency != '-1' && $value->status_when_report == $value->status){
                  $status_color = '#FFFF00';// '#EF5163';
                  $status_string = 'Reported Emergency';
                }
                else if($value->status == '1'){
                
                    $status_color = '#00874A';
                    $status_string = 'Accepted';
                
                }else if($value->status == '2'){
                
                    $status_color = '#0063C6';
                    $status_string = 'On The Way';
                
                }else if($value->status == '4'){
                    
                    $status_color = '#00874A';
                    $status_string = 'Arrived at Pickup Location';

                }else if($value->status == '5'){
                    
                    $status_color = '#FFC70D';
                    $status_string = 'Start Shipment';

                }else if($value->status == '6'){
                
                    $status_color = '#12D612';
                    $status_string = 'Delivered';
                
                }else if($value->status == '8'){
                
                    $status_color = '#00874A';
                    $status_string = 'Arrived at Drop off Location';
                
                }else if($value->status == '9'){
                              
                      $status_color = '#00874A';
                      $status_string = 'On The Way To PickUp';
                  }

                $data1['shipment_id'] = $value->id;
                $data1['ship_id'] = $value->id;
                $data1['status'] = is_null($value->status)?'':$value->status;
                $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                $data1['drop'] = is_null($value->drop)?'':$value->drop;
                $data1['status_color'] = $status_color;
                $data1['status_string'] = $status_string;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);
                
                array_push($active_list,$data1);
              }
            }

      return view('shipper.dashboard',compact('active_list')); 

    }  

    public function dashboard_filter(Request $request)
    {
        $user_id = Auth::guard('shipper')->user()->id; 

        $filter_type = $request->filter_type;
        
        $shipper = User::find($user_id);

        $shipper->filter_type = $filter_type;

        $shipper->save();

        $data1 = array();
        
        $data1['instant_quote_count'] = Helper::get_shipper_total_request_count($user_id,$filter_type,"2");
        $data1['fixed_shipment_count'] = Helper::get_shipper_total_request_count($user_id,$filter_type,"1");
        $data1['auction_shipment_count'] = Helper::get_shipper_total_request_count($user_id,$filter_type,"0");
        $data1['total_accepted_shipment'] = Helper::get_user_accepted_shipment_count($user_id,$filter_type);
        $data1['total_cancelled_shipment'] = Helper::get_user_cancelled_shipment_count($user_id,$filter_type);
        $data1['total_reported_shipment'] = Helper::get_user_reported_shipment_count($user_id,$filter_type);
        $data1['received_offer_count'] = Helper::get_user_received_offer_shipment_count($user_id,$filter_type);

        return json_encode(['success' => 1, 'msg' => 'Success','result' => $data1 ]);
    }

    public function show_book_truck()
    {
      $truck = DB::select('select * from truck where status = "1" order by created_at desc ');

      return view('shipper.book_truck',compact('truck'));  
    }

    public function show_enter_book_details(Request $request)
    {

     $this->timezone = Auth::guard('shipper')->user()->timezone;

      $user_id = Auth::guard('shipper')->user()->id; 

      $pickup = $request->pickup;
      $pickup_lat = $request->lat_0;
      $pickup_long = $request->lng_0;
      $drop = $request->drop;
      $drop_lat = $request->lat_1;
      $drop_long = $request->lng_1;
      $vehicle_id = $request->vehicle_id;

      $user = User::find($user_id);

      $user_payment_type = $user->payment_type;
      $shipper_type = $user->shipper_type;
      
      $goods_type = array();
      $goods_type = Goods_type::where('status','1')->get();
      
      return view('shipper.enter_book_details',compact('pickup','drop','pickup_lat','pickup_long','drop_lat','drop_long','vehicle_id','user_payment_type','goods_type','shipper_type'));  
    }

    public function show_help_feedback()
    {
      return view('shipper.help_feedback');  
    }

    public function show_rate_shipment()
    {
      return view('shipper.rate_shipment');  
    } 

    public function show_profile()
    {
        $user_id = Auth::guard('shipper')->user()->id; 

        $user = User::find($user_id);

         $doc = array();

         if($user && $user->doc != '' && $user->doc != null){

            $str = $user->doc;

            $doc = explode ("#####", $str);
         }

        $card = Card::where('user_id',$user_id)->where('status',"1")->get();

      return view('shipper.profile',compact('user','card','doc')); 
    }

    public function update_profile(Request $request)
    {
        $user_id = Auth::guard('shipper')->user()->id; 

        $user = User::find($user_id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->mobile_no = $request->mobile_no;
        $user->address = $request->address;
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

        $owner_id_doc_url = $user->owner_id_doc;
        
        if($request->hasFile('owner_id_doc')){

            $validator = Validator::make($request->all(), [
              'owner_id_doc' => 'required',
              'owner_id_doc.*.file' => 'image|mimes:jpg,jpeg,png',
            ]);
            
               
            foreach ($request->file('owner_id_doc') as $key => $request_doc) {  
            
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

                if($user->owner_id_doc == null || $user->owner_id_doc == ''){
                    $owner_id_doc_url = $pro_pic_url;
                }else{
                    $owner_id_doc_url .= '#####'.$pro_pic_url;
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
        $user->owner_id_doc = $owner_id_doc_url;
        $user->save();

        return back()->with('alert-success','Profile Updated !!!');
    }

     public function add_new_card(Request $request){

        try{  

            $user_id = Auth::guard('shipper')->user()->id; 

            $check_user = User::find($user_id);

            if($check_user != null){

                $user_card  = ($request->card_id == '0')?new Card:Card::find($request->card_id);
                
                $user_card->user_id = $user_id;
                $user_card->card_no = $request->card_no;
                $user_card->holder_name = $request->holder_name;
                $user_card->expiry_month = $request->expiry_month;
                $user_card->expiry_year = $request->expiry_year;
                $user_card->cvv = $request->cvv;
                $user_card->save();

                $msg = ($request->card_id == '0')?'New Card Added Successfully':'Card Updated Successfully';
              
                session()->flash('alert-success', $msg);
                return redirect()->route('shipperShowProfile');

            }
            else
            {
                $msg=trans('User not Found');          
                
                session()->flash('alert-warning', $msg);
                return redirect()->route('shipperShowProfile');
            }
            
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage() ]);
        }
    }

    public function delete_card(Request $request){

    try{  
          $user_id = Auth::guard('shipper')->user()->id; 

            $card = Card::where('id','=',$request->card_id)->where('user_id','=',$user_id)->first();

            if($card != null){

                $card->status = '2';
                $card->save();
                

                $msg=trans('Card Removed Successfully');          
                
                session()->flash('alert-success', $msg);

                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]));
                return json_encode(['success' => 1, 'msg' => $msg,'result' => [] ]);
            }

            else
            {
                $msg=trans('Card not Found');          
                
                session()->flash('alert-warning', $msg);
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]));
                return json_encode(['success' => 0, 'msg' => $msg,'result' => [] ]);
            }
            
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage() ]);
        }
    }

    public function show_notification_list()
    {
        $this->timezone = Auth::guard('shipper')->user()->timezone;

      $user_id = Auth::guard('shipper')->user()->id;

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

      return view('shipper.notification',compact('notification_list')); 
    }  

    public function show_change_password(Request $request)
    { 
        $user_id = Auth::guard('shipper')->user()->id; 

        $user = User::find($user_id);

        return view('shipper.change_password',compact('user')); 
    }
   
}