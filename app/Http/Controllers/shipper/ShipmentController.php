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
use App\Review;
use App\Shipment;
use App\Goods_type;
use App\Commission;
use App\Payment_info;
use App\Shipment_bid;
use App\Shipment_info;
use App\Track_shipment;
use App\Transporter_truck;
 
class ShipmentController extends Controller
{
	 private $timezone;

    public function book_new_shipment(Request $request)
    {
      
      try{
        
          $user_id = Auth::guard('shipper')->user()->id; 

          $user = User::find($user_id);

          $sender_info = $request->sender_info;

          $doc_url = null;

          $unique_id = rand(111111,999999);

          if($request->hasFile('document')){

            $validator = Validator::make($request->all(), [
              'document' => 'required',
                      'document.*.file' => 'image|mimes:jpg,jpeg,png',
            ]);
            
            foreach ($request->file('document') as $key => $request_doc) {  

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

          for ($i=0; $i < $request->no_of_vehicle; $i++) {

              $shipment = new Shipment;

              $shipment->user_id = $user->id;
              $shipment->driver_id = is_null($request->driver_id)?0:$request->driver_id;
              $shipment->transporter_id = is_null($request->transporter_id)?0:$request->transporter_id;
              $shipment->vehicle_id = $request->vehicle_id;
              $shipment->card_id = is_null($request->card_id)?0:$request->card_id;
              $shipment->promo_id = isset($request->promo_id)?0:$request->promo_id;
              $shipment->pickup = $request->pickup;
              $shipment->drop = $request->drop;
              $shipment->amount = is_null($request->quotation_amount)?0:$request->quotation_amount;
              $shipment->basic_amount = is_null($request->quotation_amount)?0:$request->quotation_amount;
              $shipment->payment_type = isset($request->payment_type)?$request->payment_type:'2';
              /*$shipment->tax_per = $request->tax_per;
              $shipment->tax_amount = $request->tax_amount;*/
              $shipment->discount_per = is_null($request->discount)?'0':$request->discount;
              //$shipment->discount_amount = $request->discount_amount;
              $shipment->receipt = $request->receipt;
              $shipment->status = '0';
              $shipment->payment_status = '0';
              
              // update discount in shipment
              if($shipment->discount_per != '0' && $request->quotation_type != '0'){
                $shipment->discount_amount = ($shipment->amount * $shipment->discount_per) / 100;
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
              $shipment_info->pickup_date = ((date('Y-m-d',strtotime($request->pickup_date))).' '.(date('H:i:s',strtotime($request->pickup_time))));
              $shipment_info->pickup_lat = $request->pickup_lat;
              $shipment_info->pickup_long = $request->pickup_long;
              $shipment_info->drop_lat = $request->drop_lat;
              $shipment_info->drop_long = $request->drop_long;
              //$shipment_info->no_of_vehicle = $request->no_of_vehicle;
              $shipment_info->no_of_vehicle = ($i+1);
              $shipment_info->total_vehicle = $request->no_of_vehicle;
              $shipment_info->sender_first_name = $sender_info[0]['sender_first_name'];
              $shipment_info->sender_last_name = $sender_info[0]['sender_last_name'];
              $shipment_info->sender_email = $sender_info[0]['sender_email'];
              $shipment_info->sender_mobile = $sender_info[0]['sender_mobile'];
              $shipment_info->receiver_first_name = $sender_info[0]['receiver_first_name'];
              $shipment_info->receiver_last_name = $sender_info[0]['receiver_last_name'];
              $shipment_info->receiver_email = $sender_info[0]['receiver_email'];
              $shipment_info->receiver_mobile = $sender_info[0]['receiver_mobile'];
              $shipment_info->promo_code = $request->promo_code;
              $shipment_info->quotation_type = $request->quotation_type;
              $shipment_info->quotation_amount = is_null($request->quotation_amount)?0:$request->quotation_amount;
              $shipment_info->goods_type = implode(',',$request->goods_type);
              $shipment_info->weight_type = $request->weight_type;
              $shipment_info->weight = $request->weight;
              $shipment_info->document = $doc_url;
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
                $order_type = 'Bid Shipment Truck No '.($i+1).' Order';
              }else if ($request->quotation_type == '1'){
                $order_type = 'Fixed Shipment Truck No '.($i+1).' Order';
              }

              $check_transporter = User::where('user_type',"3")->where('approve',"1")->where('is_verify','1')->where('status','1')->orderBy('created_at','desc')->get(); 
                
                foreach ($check_transporter as $key => $value) {

                  $check_truck = Transporter_truck::where('truck_id',$request->vehicle_id)->where('user_id',$value->id)->where('status',"1")->orderBy('created_at','desc')->first();

                  if($check_truck != null){
                    // send notification
                    Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment',' Book New '.$order_type.' No. #'.$unique_id,'1',$shipment->id);

                    // read request count
                    DB::table('shipment_request_count')->insert(['user_id' => $value->id,'shipment_id' => $shipment->id,'is_read' => '0']);

                  }
                }

              $check_driver = User::where('user_type',"4")->where('approve',"1")->where('is_verify','1')->where('status','1')->where('ref_id','0')->orderBy('created_at','desc')->get(); 

                foreach ($check_driver as $key => $value) {
                
                  $check_truck = Transporter_truck::where('truck_id',$request->vehicle_id)->where('user_id',$value->id)->where('status',"1")->orderBy('created_at','desc')->first();

                  if($check_truck != null){
                   
                      // send notification
                      Helper::send_push_notification($shipment->user_id,$value->id,'New Shipment',' Book New '.$order_type.' No. #'.$unique_id,'1',$shipment->id);

                      // read request count
                      DB::table('shipment_request_count')->insert(['user_id' => $value->id,'shipment_id' => $shipment->id,'is_read' => '0']);
                    }
                }

            }
        // end for loop
        }

          $user_name = is_null($user->first_name)?'':$user->first_name.' '.(is_null($user->last_name)?'':$user->last_name);

              // new user mail to admin
              $user_detail2 =array();
              $user_detail2['email'] = $user->email;
              $user_detail2['shipment_id'] = $unique_id;
              $user_detail2['user_name'] = $user_name;
              $user_detail2['date'] = date("Y-m-d H:i", strtotime('+60 minutes'));


              Mail::send('emails.new_instant_quote', ['user' => (object)$user_detail2], function($message) use ($user) {
                  $message->from(env('MAIL_USERNAME'), 'KMIOU');
                  $message->to(env('MAIL_ADMIN'));
                  $message->subject('KMIOU NEW INSTANT QUOTE ORDER');
              });

          session()->flash('alert-success', 'Booking Added Successfully.');
          return redirect()->route('shipperShowDashboard');
      
    } catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('auth.technical_issue'), 'result' => $ex->getMessage() ]);
      }
    } 

    public function apply_promo_code(Request $request){
    try{  
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

          $msg = 'Promo Code Invalid';
          Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                  return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
        }
                
          }catch(Exception $ex) {
                  
                  Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                  return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage() ]);
          }
      }

    public function select_bidder(Request $request)
    {
      try{
        
         $user_id = Auth::guard('shipper')->user()->id; 

        $shipment = Shipment::find($request->shipment_id);   
        
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
            
            $msg = 'Bidder Selected Successfully';

            $shipment->updated_by = $user_id;
          
            $shipment->save();

            /*DB::table('shipment_bid')->where('shipment_id',$request->shipment_id)->where('id','!=',$request->bid_id)->update(['status' => "2"]);*/

            $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

            // send notification
            Helper::send_push_notification($user_id,$bid->user_id,'Bidder Selected','selected your bid order Truck No. '.$get_truck_no->no_of_vehicle.' Order No. #'.$shipment->unique_id,'3',$shipment->id);

            session()->flash('alert-success', $msg);
            return redirect(route('shipperShowDashboard'));
          }
          else
          {
            Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
              return json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]);
              
          }
      
    } catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('auth.technical_issue'), 'result' => $ex->getMessage() ]);
      }
    }

    public function show_active_shipment($filter_type = '0')
    {

      $filter_type = ($filter_type == null || $filter_type == '')?'0':$filter_type;
      
      return view('shipper.active_shipment',compact('filter_type')); 
    }

    public function active_shipment_filter(Request $request)
    {
      $this->timezone = Auth::guard('shipper')->user()->timezone;

        $user_id = Auth::guard('shipper')->user()->id; 

          $active_list = array(); 

          //accepted
        if(isset($request->filter_type) && $request->filter_type == "1")
        {
          $query = ' shipment.status = "1" ';
        
          //fixed
        }else if(isset($request->filter_type) && $request->filter_type == "2")
        {
          $query = ' info.quotation_type ="1" AND shipment.status = "0" ';
        
          //bidded
        }else if(isset($request->filter_type) && $request->filter_type == "3")
        {
          $query = ' info.quotation_type = "0" AND shipment.status = "0" ';
        
          //received offers
        }else if(isset($request->filter_type) && $request->filter_type == "4")
        {
          $query = ' shipment.status = "0" AND shipment.transporter_id = "0" AND shipment.driver_id = "0" AND shipment.bid_status = "0" AND info.quotation_type = "0" ';
        
          //active shipments
        }else{

            $query = ' shipment.status IN ("2","4","5","8","9") ';
        }  


        if(!empty($request->from_date))
        {
          $query .= " AND DATE(info.pickup_date) >= '".$request->from_date."'";     
        }

        if(!empty($request->to_date))
        {
          $query .= " AND DATE(info.pickup_date) <= '".$request->to_date."'";     
        }             
        
          $select_shipment = DB::select('SELECT shipment.*,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,info.quotation_type
            FROM shipment
            left join shipment_info as info on info.shipment_id=shipment.id
            left join users on users.id=shipment.user_id
           WHERE '.$query.' AND shipment.user_id = '.$user_id.' order by shipment.created_at desc');
            
            if($select_shipment != null){

              foreach ($select_shipment as $key => $value) {
                
                $data1 = array();

                $select_bidder = Shipment_bid::where('shipment_id',$value->id)->first();

                $data1['shipment_id'] = $value->id;
                $data1['ship_id'] = $value->unique_id;
                $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                $data1['drop'] = is_null($value->drop)?'':$value->drop;
                $data1['quotation_type'] = is_null($value->quotation_type)?'':$value->quotation_type;
                $data1['status'] = is_null($value->status)?'':$value->status;
                $data1['amount'] = is_null($value->amount)?'':$value->amount;
                $data1['bid_status'] = is_null($value->bid_status)?'':$value->bid_status;
                $data1['bidder_count'] = is_null($select_bidder)?'0':'1';
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'d-M-Y h:i A', $this->timezone);
                
                array_push($active_list,$data1);
              }
            }

        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $active_list ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' => $active_list ]);

    }

    public function show_active_shipment_detail($id)
    {

      $this->timezone = Auth::guard('shipper')->user()->timezone;

        $user_id = Auth::guard('shipper')->user()->id; 
        $check_user = User::find($user_id); 
        
        $details = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,users.profile_pic as transporter_profile_pic ,users.first_name as transporter_first_name,users.last_name as transporter_last_name,users.mobile_no as transporter_mobile,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,info.receiver_first_name,truck.truck_img, truck.truck_name, info.receiver_last_name, info.receiver_mobile
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.transporter_id
              left join users as driver on driver.id=shipment.driver_id
              left join truck on truck.id=shipment.vehicle_id
             WHERE shipment.id = '.$id.' ');
            
          if($select_shipment != null && $select_shipment[0]->status != '3' && $select_shipment[0]->status != '7' && $select_shipment[0]->status != '6'){

              foreach ($select_shipment as $key => $value) {

                $select_bidder = Shipment_bid::where('shipment_id',$value->id)->first();
                
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
                  $data1['transporter_id'] = $value->transporter_id;
                  $data1['transporter_first_name'] = is_null($value->transporter_first_name)?'':$value->transporter_first_name;
                  $data1['transporter_last_name'] = is_null($value->transporter_last_name)?'':$value->transporter_last_name;
                  $data1['transporter_profile_pic'] = is_null($value->transporter_profile_pic)?'':$value->transporter_profile_pic;
                  $data1['transporter_mobile'] = is_null($value->transporter_mobile)?'':$value->transporter_mobile;
                  $data1['driver_first_name'] = is_null($value->driver_first_name)?'':$value->driver_first_name;
                  $data1['driver_last_name'] = is_null($value->driver_last_name)?'':$value->driver_last_name;
                  $data1['driver_profile_pic'] = is_null($value->driver_profile_pic)?'':$value->driver_profile_pic;
                  $data1['driver_mobile'] = is_null($value->driver_mobile)?'':$value->driver_mobile;
                  $data1['receiver_first_name'] = is_null($value->receiver_first_name)?'':$value->receiver_first_name;
                  $data1['receiver_last_name'] = is_null($value->receiver_last_name)?'':$value->receiver_last_name;
                  $data1['receiver_mobile'] = is_null($value->receiver_mobile)?'':$value->receiver_mobile;
                  $data1['quotation_type'] = $value->quotation_type;
                  $data1['quotation_amount'] = is_null($value->quotation_amount)?0:$value->quotation_amount;
                  $data1['bid_status'] = is_null($value->bid_status)?'':$value->bid_status;
                  $data1['bidder_count'] = is_null($select_bidder)?'0':'1';
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
                  $data1['bid_status'] = is_null($value->bid_status)?'0':$value->bid_status;
                  $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);
                  
                  //removed old

                  /*$truck_arr = array();

                  if($value->total_vehicle > '1'){

                    $get_total_truck_of_shipment = Shipment::where('unique_id',$value->unique_id)->where('status',["0","1","2","4","5","8"])->get();
                    
                    foreach ($get_total_truck_of_shipment as $key => $value) {

                      $get_truck_number = Shipment_info::where('shipment_id',$value->id)->first();

                      $data2 = array();
                      $data2['title'] = 'Truck No : '.$get_truck_number->no_of_vehicle;
                      $data2['shipment_id'] = $value->id;
      
                      array_push($truck_arr,$data2);
                  
                    }
                  }
                  
                  $data1['truck'] = $truck_arr;*/ 

                  $truck_arr = array();

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

                      $get_total_truck_of_shipment = DB::select(' select * from shipment where unique_id = '.$value->unique_id.' '.$truck_query.' AND bid_status="'.$value->bid_status.'"');

                      foreach ($get_total_truck_of_shipment as $key => $value) {
                        
                        //get truck number
                        $get_truck_number = Shipment_info::where('shipment_id',$value->id)->first();

                        $data2 = array();
                        $data2['title'] = 'Truck No : '.$get_truck_number->no_of_vehicle;
                        $data2['shipment_id'] = $value->id;
        
                        array_push($truck_arr,$data2);
                    
                      }
                    
                    $data1['truck'] = $truck_arr;
                  }

                  
                  array_push($details,$data1);
              }
          
          }else{
            
            if($select_shipment[0]->status == '3'){
            
              return redirect()->route('shipperShowCancelShipmentDetails',['id'=>$id]);
            
            }else if($select_shipment[0]->status == '7'){
              
              return redirect()->route('shipperShowReportShipmentDetails',['id'=>$id]);
            
            }else if($select_shipment[0]->status == '6'){
              
              return redirect()->route('shipperShowPastShipmentDetails',['id'=>$id]);
            
            }else{
              return redirect()->route('shipperShowActiveShipment');
            }
          }

          $doc = array();

         if($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null){

            $str = $select_shipment[0]->document;

            $doc = explode ("#####", $str);
         }

         $bid = array();

        $bid = DB::select('Select shipment_bid.*,users.first_name as user_first_name,users.last_name as user_last_name,users.profile_pic as user_profile_pic,users.total_rate_count as total_rate_count,users.avg_rating as avg_rating from shipment_bid left join users on users.id=shipment_bid.user_id where shipment_id = '.$id.' ');
          

      return view('shipper.active_shipment_details',compact('details','doc','bid'));  

    }

    public function show_past_shipment()
    {
        return view('shipper.past_shipment'); 
    }

    public function past_shipment_filter(Request $request)
    {
        $timezone = Auth::guard('shipper')->user()->timezone;
          
        $user_id = Auth::guard('shipper')->user()->id;  

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
           WHERE '.$filter_query.' AND shipment.user_id = '.$user_id.' '.$query.' order by shipment.created_at desc');
          
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
              $data1['status_color'] = $status_color;
              $data1['status_string'] = $status_string;
              $data1['amount'] = is_null($value->amount)?'':$value->amount;
              $data1['created_at'] = Helper::convertTimestampWithTimezone($value->pickup_date,'Y-m-d H:i:s', $timezone);
              
              array_push($past_list,$data1);
            }
          }
          
        Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Success','result' => $past_list ]));
        return json_encode(['success' => 1, 'msg' => 'Success','result' => $past_list ]);
    }

    public function show_past_shipment_details($id)
    { 

        $timezone = Auth::guard('shipper')->user()->timezone;

          $user_id = Auth::guard('shipper')->user()->id; 
          
          $details = array();                
          
            $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,info.person_name,info.id_proof_image,info.signature_image,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,transporter.profile_pic as transporter_profile_pic ,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,transporter.mobile_no as transporter_mobile,truck.truck_img, truck.truck_name, info.receiver_first_name,info.receiver_last_name, info.receiver_mobile
                FROM shipment
                left join shipment_info as info on info.shipment_id=shipment.id
                left join users on users.id=shipment.user_id
                left join users as driver on driver.id=shipment.driver_id
                left join users as transporter on transporter.id=shipment.transporter_id
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
                    $data1['driver_id'] = $value->driver_id;
                    $data1['transporter_id'] = $value->transporter_id;
                    $data1['driver_first_name'] = is_null($value->driver_first_name)?'':$value->driver_first_name;
                    $data1['driver_last_name'] = is_null($value->driver_last_name)?'':$value->driver_last_name;
                    $data1['driver_profile_pic'] = is_null($value->driver_profile_pic)?'':$value->driver_profile_pic;
                    $data1['driver_mobile'] = is_null($value->driver_mobile)?'':$value->driver_mobile;
                    $data1['transporter_first_name'] = is_null($value->transporter_first_name)?'':$value->transporter_first_name;
                    $data1['transporter_last_name'] = is_null($value->transporter_last_name)?'':$value->transporter_last_name;
                    $data1['transporter_profile_pic'] = is_null($value->transporter_profile_pic)?'':$value->transporter_profile_pic;
                    $data1['transporter_mobile'] = is_null($value->transporter_mobile)?'':$value->transporter_mobile;
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
                    $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $timezone);

                    $truck_arr = array();

                    if($value->total_vehicle > '1'){

                      $get_total_truck_of_shipment = Shipment::where('unique_id',$value->unique_id)->where('status',"6")->get();
                      
                      foreach ($get_total_truck_of_shipment as $key => $value) {

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

              return redirect()->route('shipperShowPastShipment');
            }

            $doc = array();

           if($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null){

              $str = $select_shipment[0]->document;

              $doc = explode ("#####", $str);
           }

           $review = Review::where('ref_id',$id)->where('user_id',$user_id)->get();

           $bid = DB::select('Select shipment_bid.*,users.first_name as user_first_name,users.last_name as user_last_name,users.profile_pic as user_profile_pic,users.total_rate_count as total_rate_count,users.avg_rating as avg_rating from shipment_bid left join users on users.id=shipment_bid.user_id where shipment_id = '.$id.' ');

        return view('shipper.past_shipment_details',compact('details','doc','review','bid'));  

    }

    public function cancel_shipment(Request $request)
    {
      try{
          $user_id = Auth::guard('shipper')->user()->id; 

          $shipment = Shipment::find($request->shipment_id);   
          

          if($shipment != null && ($shipment->status == '0' || $shipment->status == '1' || $shipment->status == '4') ){

            $msg = '';

              $shipment->status = '3';
              $msg = 'Shipment Cancelled Successfully';

              $shipment->cancel_reason = is_null($request->cancel_reason)?"4":$request->cancel_reason;
              $shipment->cancel_comment = $request->comment;
              $shipment->cancel_by = $user_id;
            
              $shipment->save();

              $track_shipment = new Track_shipment;
                $track_shipment->shipment_id = $shipment->id;
                $track_shipment->status = '3';
                $track_shipment->payment_status = $shipment->payment_status;
                $track_shipment->save();


              DB::table('notification')->where('noti_type',"1")->where('ref_id',$request->shipment_id)->delete();

                if($shipment->driver_id != '0'){
                
                $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

                  // send notification to driver
                  Helper::send_push_notification($user_id,$shipment->driver_id,'Order Cancelled',' (shipper) cancelled your order Truck No. '.$get_truck_no->no_of_vehicle.' Order No. #'.$shipment->unique_id,'7',$shipment->id);
                }
                
                if($shipment->transporter_id != '0'){

                  $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

                  // send notification to transporter
                  Helper::send_push_notification($user_id,$shipment->transporter_id,'Order Cancelled',' (shipper) cancelled your order Truck No. '.$get_truck_no->no_of_vehicle.' Order No. #'.$shipment->unique_id,'7',$shipment->id);
                }

              session()->flash('alert-success',$msg);
              return redirect(route('shipperShowCancelShipment'));
            }
            else
            {
              Helper::logs($_POST,json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]));
                return json_encode(['success' => 0, 'msg' => 'Shipment Not Found','result' => [] ]);
                
            }
      
    } catch(Exception $ex) {
            
            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('auth.technical_issue'), 'result' => $ex->getMessage() ]);
      }
    }

    public function show_cancel_shipment()
    {
      $this->timezone = Auth::guard('shipper')->user()->timezone;

        $user_id = Auth::guard('shipper')->user()->id; 

          $cancel_list = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name
            FROM shipment
            left join shipment_info as info on info.shipment_id=shipment.id
            left join users on users.id=shipment.user_id
           WHERE shipment.status = "3" AND shipment.user_id = '.$user_id.' order by shipment.created_at desc');
            
            if($select_shipment != null){

              foreach ($select_shipment as $key => $value) {
                
                $data1 = array();

                $data1['shipment_id'] = $value->id;
                $data1['ship_id'] = $value->unique_id;
                /*$data1['shipper_id'] = $value->user_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;*/
                $data1['status'] = is_null($value->status)?'':$value->status;
                $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                $data1['drop'] = is_null($value->drop)?'':$value->drop;
                $data1['amount'] = is_null($value->amount)?'':$value->amount;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);
                
                array_push($cancel_list,$data1);
              }
            }

      return view('shipper.cancel_shipment',compact('cancel_list'));  

    }

    public function show_cancel_shipment_detail($id)
    {

      $this->timezone = Auth::guard('shipper')->user()->timezone;

        $user_id = Auth::guard('shipper')->user()->id; 
        
        $details = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,users.profile_pic as transporter_profile_pic ,users.first_name as transporter_first_name,users.last_name as transporter_last_name,users.mobile_no as transporter_mobile,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,truck.truck_img, truck.truck_name, info.receiver_first_name,info.receiver_last_name, info.receiver_mobile
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.transporter_id
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
                  $data1['transporter_id'] = $value->transporter_id;
                  $data1['driver_id'] = $value->driver_id;
                  $data1['transporter_first_name'] = is_null($value->transporter_first_name)?'':$value->transporter_first_name;
                  $data1['transporter_last_name'] = is_null($value->transporter_last_name)?'':$value->transporter_last_name;
                  $data1['transporter_profile_pic'] = is_null($value->transporter_profile_pic)?'':$value->transporter_profile_pic;
                  $data1['transporter_mobile'] = is_null($value->transporter_mobile)?'':$value->transporter_mobile;
                   $data1['driver_first_name'] = is_null($value->driver_first_name)?'':$value->driver_first_name;
                  $data1['driver_last_name'] = is_null($value->driver_last_name)?'':$value->driver_last_name;
                  $data1['driver_profile_pic'] = is_null($value->driver_profile_pic)?'':$value->driver_profile_pic;
                  $data1['driver_mobile'] = is_null($value->driver_mobile)?'':$value->driver_mobile;
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
                  $data1['truck_name'] = is_null($value->truck_name)?'':$value->truck_name;
                  $data1['truck_img'] = is_null($value->truck_img)?'':$value->truck_img;
                  $data1['cancel_reason'] = is_null($value->cancel_reason)?'0':$value->cancel_reason;
                  $data1['cancel_comment'] = is_null($value->cancel_comment)?'':$value->cancel_comment;
                  $data1['payment_type'] = is_null($value->payment_type)?0:$value->payment_type;
                  $data1['payment_status'] = is_null($value->payment_status)?'':$value->payment_status;
                  $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);

                  $truck_arr = array();

                  if($value->total_vehicle > '1'){

                    $get_total_truck_of_shipment = Shipment::where('unique_id',$value->unique_id)->where('status',"3")->get();
                    
                    foreach ($get_total_truck_of_shipment as $key => $value) {

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

            return redirect()->route('shipperShowCancelShipment');
          }

          $doc = array();

         if($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null){

            $str = $select_shipment[0]->document;

            $doc = explode ("#####", $str);
         }


        return view('shipper.cancel_shipment_details',compact('details','doc'));  

    }

    public function show_report_shipment()
    {
      $this->timezone = Auth::guard('shipper')->user()->timezone;

        $user_id = Auth::guard('shipper')->user()->id; 

          $report_list = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name
            FROM shipment
            left join shipment_info as info on info.shipment_id=shipment.id
            left join users on users.id=shipment.user_id
           WHERE shipment.status = "7" AND shipment.user_id = '.$user_id.' order by shipment.created_at desc');
            
            if($select_shipment != null){

              foreach ($select_shipment as $key => $value) {
                
                $data1 = array();

                $data1['shipment_id'] = $value->id;
                $data1['ship_id'] = $value->unique_id;
                /*$data1['shipper_id'] = $value->user_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name)?'':$value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name)?'':$value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic)?'':$value->shipper_profile_pic;*/
                $data1['status'] = is_null($value->status)?'':$value->status;
                $data1['pickup'] = is_null($value->pickup)?'':$value->pickup;
                $data1['drop'] = is_null($value->drop)?'':$value->drop;
                $data1['amount'] = is_null($value->amount)?'':$value->amount;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);
                
                array_push($report_list,$data1);
              }
            }

      return view('shipper.report_shipment',compact('report_list'));  

    }

    public function show_report_shipment_detail($id)
    {

      $this->timezone = Auth::guard('shipper')->user()->timezone;

        $user_id = Auth::guard('shipper')->user()->id; 
        
        $details = array();                
        
          $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,users.profile_pic as transporter_profile_pic ,users.first_name as transporter_first_name,users.last_name as transporter_last_name,users.mobile_no as transporter_mobile,driver.profile_pic as driver_profile_pic,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.transporter_id
              left join users as driver on driver.id=shipment.driver_id
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

                  $data1['shipper_id'] = $value->user_id;
                  $data1['shipment_id'] = $value->id;
                  $data1['driver_id'] = $value->driver_id;
                  $data1['transporter_id'] = $value->transporter_id;
                  $data1['transporter_first_name'] = is_null($value->transporter_first_name)?'':$value->transporter_first_name;
                  $data1['transporter_last_name'] = is_null($value->transporter_last_name)?'':$value->transporter_last_name;
                  $data1['transporter_profile_pic'] = is_null($value->transporter_profile_pic)?'':$value->transporter_profile_pic;
                  $data1['transporter_mobile'] = is_null($value->transporter_mobile)?'':$value->transporter_mobile;
                  $data1['driver_first_name'] = is_null($value->driver_first_name)?'':$value->driver_first_name;
                  $data1['driver_last_name'] = is_null($value->driver_last_name)?'':$value->driver_last_name;
                  $data1['driver_profile_pic'] = is_null($value->driver_profile_pic)?'':$value->driver_profile_pic;
                  $data1['driver_mobile'] = is_null($value->driver_mobile)?'':$value->driver_mobile;
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
                  $data1['report_emergency'] = is_null($value->report_emergency)?'0':$value->report_emergency;
                  $data1['report_comment'] = is_null($value->report_comment)?'':$value->report_comment;
                  $data1['payment_type'] = is_null($value->payment_type)?0:$value->payment_type;
                  $data1['payment_status'] = is_null($value->payment_status)?'':$value->payment_status;
                  $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at,'Y-m-d H:i:s', $this->timezone);
                  
                  array_push($details,$data1);
              }
          
          }else{

            return redirect()->route('shipperShowReportShipment');
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


        return view('shipper.report_shipment_details',compact('details','doc','comment'));  

    }

  public function rate_shipment(Request $request){

    try{  
            $timezone = Auth::guard('shipper')->user()->timezone;

            $user_id = Auth::guard('shipper')->user()->id; 

            $shipment = Shipment::find($request->ref_id);   
        
            if($shipment != null){

                $review  = new Review;
                
                $review->user_id = $user_id;
                
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

                $get_truck_no = Shipment_info::where('shipment_id',$request->shipment_id)->first();

                // send notification
                Helper::send_push_notification($user_id,$shipment->driver_id,'Rate Shipment',' '.$message.' added Review to your shipment Truck No. '.$get_truck_no->no_of_vehicle.' Order No. #'.$shipment->unique_id,'11',$shipment->id);

                Helper::logs($_POST,json_encode(['success' => 1, 'msg' => 'Shipment Rating Submitted Successfully','result' => [] ]));
                return json_encode(['success' => 1, 'msg' => 'Shipment Rating Submitted Successfully','result' => []]);

            }
            else
            {
                $msg=trans('Shipment not Found');          
                
                Helper::logs($_POST,json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]));
                return json_encode(['success' => 0, 'msg' =>  $msg,'result' => []]);
            }
            
        }catch(Exception $ex) {
                
                Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
                return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage() ]);
        }
    } 

    public function track_shipment(Request $request){

    try{  
          $this->timezone = Auth::guard('shipper')->user()->timezone;

          $user_id = Auth::guard('shipper')->user()->id; 

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

// end controller function
}