<?php

namespace App\Http\Controllers\admin;

use App\Helper;
use App\Http\Controllers\Controller;
use App\Transporter_truck;
use App\User;
use DB;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

require './vendor/Twilio/autoload.php';

use Validator;

class UserController extends Controller
{

    public function adminDashboard(Request $request)
    {

        $get_total_accepted_shipment = DB::select(' select count(id) as total_accepted_shipment from shipment where status = "1" ');

        $get_total_reported_shipment = DB::select(' select count(id) as total_reported_shipment from shipment where status = "7" ');

        $get_total_cancelled_shipment = DB::select(' select count(id) as total_cancelled_shipment from shipment where status = "3" ');

        $get_total_request_shipment = DB::select(' select count(id) as total_request_shipment from shipment where status = "0" ');

        $get_instant_quote_request = DB::select(' select count(shipment.id) as instant_quote_request from shipment left join shipment_info as info on info.shipment_id=shipment.id where (shipment.status = "0" OR (shipment.status = "1" AND shipment.driver_id = "0" ) ) AND info.quotation_type = "2"  ');

        $get_total_pending_request_shipment = DB::select(' select count(id) as total_request_shipment from shipment where status = "0" AND shipment.created_at <= "' . date('Y-m-d H:i:s', strtotime('-2 days')) . '"  ');

        $get_total_transporter = DB::select(' select count(id) as total_transporter from users where user_type = "3" AND status != "2" ');

        $get_total_shipper = DB::select(' select count(id) as total_shipper from users where user_type = "2" AND status != "2" ');

        $get_total_single_driver = DB::select(' select count(id) as total_single_driver from users where user_type = "4" AND ref_id = "0" AND status != "2" ');

        $data = array();
        $data['total_accepted_shipment'] = $get_total_accepted_shipment[0]->total_accepted_shipment;
        $data['total_reported_shipment'] = $get_total_reported_shipment[0]->total_reported_shipment;
        $data['total_cancelled_shipment'] = $get_total_cancelled_shipment[0]->total_cancelled_shipment;
        $data['total_transporter'] = $get_total_transporter[0]->total_transporter;
        $data['total_shipper'] = $get_total_shipper[0]->total_shipper;
        $data['single_driver'] = $get_total_single_driver[0]->total_single_driver;
        $data['total_request'] = $get_total_request_shipment[0]->total_request_shipment;
        $data['instant_quote_request'] = $get_instant_quote_request[0]->instant_quote_request;
        $data['total_pending_request'] = $get_total_pending_request_shipment[0]->total_request_shipment;

        return view('admin/dashboard', compact('data'));
    }

    public function admin_filter_dashboard(Request $request)
    {
        $user_id = Auth::guard('admin')->user()->id;

        $filter_type = $request->filter_type;

        $admin = User::find($user_id);

        $admin->filter_type = $filter_type;

        $admin->save();

        $today = date('Y-m-d', strtotime('-1 days'));
        $last_week_date = date('Y-m-d', strtotime('-7 days'));
        $last_month_date = date("Y-m-d", strtotime("-1 month"));
        $last_year_date = date("Y-m-d", strtotime("-1 year"));

        $query = '';
        $user_query = '';
        if ($filter_type == '0') {

            $query = ' AND shipment.updated_at >= "' . $today . '" ';

            $user_query = ' AND shipment.created_at >= "' . $today . '" ';

        } else if ($filter_type == '1') {

            $query = ' AND shipment.updated_at >= "' . $last_week_date . '" ';

            $user_query = ' AND shipment.created_at >= "' . $last_week_date . '" ';

        } else if ($filter_type == '2') {

            $query = ' AND shipment.updated_at >= "' . $last_month_date . '" ';

            $user_query = ' AND shipment.created_at >= "' . $last_month_date . '" ';

        } else if ($filter_type == '3') {

            $query = ' AND shipment.updated_at >= "' . $last_year_date . '" ';

            $user_query = ' AND shipment.created_at >= "' . $last_year_date . '" ';

        }

        $get_total_accepted_shipment = DB::select(' select count(id) as total_accepted_shipment from shipment where status = "1" ' . $query . ' ');

        $get_total_reported_shipment = DB::select(' select count(id) as total_reported_shipment from shipment where status = "7" ' . $query . ' ');

        $get_total_cancelled_shipment = DB::select(' select count(id) as total_cancelled_shipment from shipment where status = "3" ' . $query . ' ');

        $get_total_request_shipment = DB::select(' select count(id) as total_request_shipment from shipment where status = "0" ' . $query . ' ');

        $get_instant_quote_request = DB::select(' select count(shipment.id) as instant_quote_request from shipment left join shipment_info as info on info.shipment_id=shipment.id where shipment.status = "0" OR (shipment.status = "1" AND shipment.driver_id = "0" ) ) AND info.quotation_type = "2"  ');

        $get_total_pending_request_shipment = DB::select(' select count(id) as total_request_shipment from shipment where status = "0" AND shipment.created_at <= "' . date('Y-m-d H:i:s', strtotime('-2 days')) . '"  ' . $query . ' ');

        $get_total_transporter = DB::select(' select count(id) as total_transporter from users as shipment where user_type = "3" AND status != "2" ' . $user_query . ' ');

        $get_total_shipper = DB::select(' select count(id) as total_shipper from users as shipment where user_type = "2" AND status != "2" ' . $user_query . ' ');

        $get_total_single_driver = DB::select(' select count(id) as total_single_driver from users where user_type = "4" AND ref_id = "0" AND status != "2" ');

        $data = array();
        $data['total_accepted_shipment'] = $get_total_accepted_shipment[0]->total_accepted_shipment;
        $data['total_reported_shipment'] = $get_total_reported_shipment[0]->total_reported_shipment;
        $data['total_cancelled_shipment'] = $get_total_cancelled_shipment[0]->total_cancelled_shipment;
        $data['total_transporter'] = $get_total_transporter[0]->total_transporter;
        $data['total_shipper'] = $get_total_shipper[0]->total_shipper;
        $data['single_driver'] = $get_total_single_driver[0]->total_single_driver;
        $data['total_request'] = $get_total_request_shipment[0]->total_request_shipment;
        $data['instant_quote_request'] = $get_instant_quote_request[0]->instant_quote_request;
        $data['total_pending_request'] = $get_total_pending_request_shipment[0]->total_request_shipment;

        return json_encode(['success' => 1, 'msg' => 'Success', 'result' => $data]);
    }

    public function show_add_new_user()
    {

        /* Create user */

        $city = DB::select('select * from city where status = "1" ');
        $country_code = DB::select('select * from tbl_country_code where status = "1" ');
        $truck = DB::select('select * from truck where status = "1" ');

        return view('admin/user/new_user', compact('city', 'truck', 'country_code'));
    }

    function list() {

        /* List Available users */

        /*$data = DB::table('users')->where('user_type','=','2')->where('status','!=','2')->where('approve','1')->get();*/

        $data = DB::select(' Select users.*,city.city_name as shipping_city_name from users left join city on city.id = users.shipping_city where users.user_type = "2" AND users.status != "2" order by created_at desc ');

        return view('admin/user/user_list', compact('data'));
    }

    public function save(Request $request)
    {

        /* Save user */
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_no' => 'required|numeric',
                'city' => 'required',
                'state' => 'required',

            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator);
            } else {

                $check_user_email = null;

                if ($request->email != '' && $request->email != null) {

                    $check_user_email = DB::select('select * from users where email = "' . $request->email . '" and users.status != "2" order by created_at desc limit 1 ');
                }

                if ($check_user_email == null) {

                    $email = '';

                    if ($request->user_type == "4" && $request->email == "") { //Driver
                        $email = $request->mobile_no . '@mobile.com';
                    } else {

                        $email = $request->email;
                    }

                    $verification_code = rand('1000', '9999');

                    $doc_url = null;

                    /*try{

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
                    return redirect(route('showAddNewUser'));
                    }*/

                    if ($request->hasFile('doc')) {

                        $validator = Validator::make($request->all(), [
                            'doc' => 'required',
                            'doc.*.file' => 'image|mimes:jpg,jpeg,png',
                        ]);

                        foreach ($request->file('doc') as $key => $request_doc) {

                            if ($validator->fails()) {
                                return back()->with('alert-warning', 'Only image files allowed !!');
                            } else {
                                $pro_pic_url = null;
                                $pro_pic = $request_doc;
                                $name = time() . $key . '.' . $pro_pic->getClientOriginalExtension();

                                $destinationPath = public_path('images/doc');
                                $pro_pic->move($destinationPath, $name);

                                $pro_pic_url = asset('public/images/doc') . '/' . $name;

                                if ($key == '0') {
                                    $doc_url = $pro_pic_url;
                                } else {
                                    $doc_url .= '#####' . $pro_pic_url;
                                }

                            }
                        }

                    }

                    $user = new User;
                    $user->first_name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->email = $email;
                    $user->mobile_no = $request->mobile_no;
                    $user->password = Hash::make($request->password);
                    $user->address = $request->address;
                    $user->city = trim(substr($request->city, strpos($request->city, '-') + 1));
                    /*$user->state = $request->state;
                    $user->country = $request->country;
                    $user->zipcode = $request->zipcode;*/
                    $user->verification_code = $verification_code;
                    $user->user_type = $request->user_type;
                    $user->doc = $doc_url;
                    $user->equipment_use = is_null($request->equipment_use) ? 0 : $request->equipment_use;
                    $user->operated_equipment_type = is_null($request->operated_equipment_type) ? 0 : $request->operated_equipment_type;
                    $user->truck_count = is_null($request->truck_count) ? 0 : $request->truck_count;
                    $user->shipment_per_month = is_null($request->shipment_per_month) ? 0 : $request->shipment_per_month;
                    $user->shipping_city = is_null($request->shipping_city) ? 0 : $request->shipping_city;
                    $user->headquarters_city = is_null($request->headquarters_city) ? 0 : $request->headquarters_city;
                    $user->language = is_null($request->language) ? 0 : $request->language;
                    $user->udid = $request->ip();
                    $user->device_type = '0';
                    $user->device_token = null;
                    $user->ref_id = is_null($request->user_id) ? 0 : $request->user_id;
                    $user->carrier_number = isset($request->carrier_number) ? $request->carrier_number : null;
                    $user->owner_id_doc = is_null($request->owner_id_doc) ? null : $request->owner_id_doc;
                    $user->shipper_type = is_null($request->register_as) ? '0' : $request->register_as; // 0: individual , 1: Company (this is only for shipper so other case it will be  by default 0)
                    $user->country_code = $request->country_code;
                    $user->status = '0';

                    if ($user->user_type == "2" && $request->register_as == "0") {
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

                    if ($request->user_type == '3') {

                        if (count($request->truck_type) > 1) {

                            foreach ($request->truck_type as $key => $value) {

                                $truck_type = new Transporter_truck;

                                $truck_type->user_id = $user->id;
                                $truck_type->truck_id = $value;
                                $truck_type->status = '1';

                                $truck_type->save();
                            }
                        }
                    } else if ($request->user_type == '4') {

                        $truck_type = new Transporter_truck;

                        $truck_type->user_id = $user->id;
                        $truck_type->truck_id = $request->single_truck_type;
                        $truck_type->status = '1';

                        $truck_type->save();
                    }

                    $user_type = '';
                    if ($request->user_type = '2') {

                        $user_type = 'Shipper';

                    } else if ($request->user_type = '3') {

                        $user_type = 'Transporter';

                    } else if ($request->user_type = '4') {

                        $user_type = 'Driver';
                    }

                    $user_name = is_null($user->first_name) ? '' : $user->first_name . ' ' . (is_null($user->last_name) ? '' : $user->last_name);

                    // new user mail to admin
                    $user_detail2 = array();
                    $user_detail2['user_type'] = $user_type;
                    $user_detail2['user_name'] = $user_name;
                    $user_detail2['email'] = is_null($user->email) ? '' : $user->email;
                    $user_detail2['date'] = date("Y-m-d H:i", strtotime('+60 minutes'));

                    Mail::send('emails.new_user_info', ['user' => (object) $user_detail2], function ($message) use ($user) {
                        $message->from(env('MAIL_USERNAME'), 'KMIOU');
                        $message->to(env('MAIL_ADMIN'));
                        $message->subject('KMIOU NEW USER');
                    });

                    if ($user->email != null && $user->email != '') {

                        Mail::send('emails.verification_link', ['user' => $user], function ($message) use ($user) {

                            $message->from(env('MAIL_USERNAME'), 'KMIOU');
                            $message->to($user->email);
                            $message->subject('KMIOU Verification Code');
                        });
                    }

                    session()->flash('alert-success', 'User Created Successfully.');
                    if ($request->user_type == '2') {
                        return redirect()->route('userList');
                    } else {
                        return redirect()->route('transporterList');
                    }

                } else {
                    session()->flash('alert-warning', 'Email Already Exists !!');
                    return redirect(route('showAddNewUser'));
                }
            }

        } catch (Exception $ex) {

            session()->flash('alert-warning', $ex->getMessage());
            return redirect(route('showAddNewUser'));
        }
    }
    public function editdata($id)
    {
        /* Edit user */

        $data = User::find($id);
        if ($data) {
            return view('admin/user/user_edit', ['data' => $data]);
        } else {
            return redirect()->route('userList');
        }

    }

    public function updatedata(Request $req)
    {
        /* Update user */

        $user = User::find($req->input('id'));
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->city = $req->city;
        $user->state = $req->state;
        $user->mobile_no = $req->mobile_no;
        $user->carrier_number = $req->carrier_number;
        $user->company_name = $req->company_name;
        $user->payment_type = $req->payment_type;
        $user->is_commission = $req->is_commission;

        if ($req->is_commission == '1') {
            $user->commission_percent = $req->commission_percent;
        }

        $user->save();

        return redirect("admin/user/list");

    }

    public function remove(Request $request)
    {
        $data = User::find($request->id);
        $data->status = '2';
        if ($data->save()) {
            return json_encode(['success' => 1, 'msg' => trans('User Removed Successfully'), 'result' => []]);
        }
    }

    /*end user functions*/

    /*start transporter functions*/

    public function transporter_list(Request $request)
    {
        $query = '';
        /* Comment By Mehul
        if(isset($request->filter_type) && $request->filter_type == '1'){

        $query = 'users.user_type = "2"';
        }else if(isset($request->filter_type) && $request->filter_type == '2'){

        $query = 'users.user_type = "3" AND users.ref_id = "0" ';

        }else{

        $query = 'users.user_type IN ("2","3")';
        }
         */

        $query = 'users.user_type = "3"';

        $filder_query = '';
        $status = isset($request->id) ? $request->id : "-1";
        if ($status != "-1") {
            $filder_query = ' AND users.approve = "' . $request->id . '"';
        }

        /* List transporter and driver */

        /*$data = DB::table('users')->where('user_type','!=','1')->where('user_type','!=','2')->where('status','!=','2')->orderBy('created_at','desc')->get();*/

        $data = DB::select('select users.*,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name
        from users
        left join users as transporter on transporter.id = users.ref_id
        where ' . $query . ' AND users.status != "2" AND users.approve != "2" ' . $filder_query . ' order by created_at desc ');

        return view('admin/transporter/transporter_list', compact('data', 'status'));
    }

    public function transporter_save(Request $request)
    {

        /* Save transporter */

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'mobile_no' => 'required|numeric',
            'city' => 'required',
            'state' => 'required',
            'email' => 'required|email|unique:users',

        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        } else {
            $user = new User;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->mobile_no = $request->mobile_no;
            $user->carrier_number = $request->carrier_number;
            $user->company_name = $request->company_name;
            $user->user_type = $request->user_type;
            $user->payment_type = $request->payment_type;

            $user->save();
            session()->flash('alert-success', 'User Created Successfully.');
            return redirect()->route('transporterList');
        }
    }
    public function transporter_editdata($id)
    {
        /* Edit user */

        $data = User::find($id);
        if ($data) {

            $city = DB::select('select * from city where status = "1" ');

            $doc = DB::select('select doc from users where id = ' . $id . ' ');

            return view('admin/transporter/transporter_edit', ['data' => $data, 'city' => $city, 'doc' => $doc]);
        } else {
            return redirect()->route('transporterUserList');
        }

    }

    public function transporter_updatedata(Request $request)
    {
        /* Update transporter */

        $user = User::find($request->input('id'));

        $doc_url = $user->doc;

        if ($request->hasFile('doc')) {

            $validator = Validator::make($request->all(), [
                'doc' => 'required',
                'doc.*.file' => 'image|mimes:jpg,jpeg,png',
            ]);

            foreach ($request->file('doc') as $key => $request_doc) {

                if ($validator->fails()) {
                    return back()->with('alert-warning', 'Only image files allowed !!');
                } else {
                    $pro_pic_url = null;
                    $pro_pic = $request_doc;
                    $name = time() . $key . '.' . $pro_pic->getClientOriginalExtension();

                    $destinationPath = public_path('images/doc');
                    $pro_pic->move($destinationPath, $name);

                    $pro_pic_url = asset('public/images/doc') . '/' . $name;

                    if ($user->doc == null || $user->doc == '') {
                        $doc_url .= $pro_pic_url;
                    } else {
                        $doc_url .= '#####' . $pro_pic_url;
                    }

                }
            }
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->mobile_no = $request->mobile_no;
        $user->carrier_number = $request->carrier_number;
        $user->company_name = $request->company_name;
        $user->payment_type = $request->payment_type;
        $user->doc = $doc_url;

        $user->save();

        return redirect("admin/transporter/user_list");

    }

    public function transporter_remove(Request $request)
    {
        $data = User::find($request->id);
        $data->status = '2';
        if ($data->save()) {
            return json_encode(['success' => 1, 'msg' => trans('User Removed Successfully'), 'result' => []]);
        }
    }

    public function change_approve_status(Request $request)
    {

        $check_user = User::find($request->id);

        if ($check_user != null) {

            $check_user->approve = $request->status;

            if ($request->status == '1') {

                $check_user->status = "1";
                $check_user->email_verified_at = date('Y-m-d H:i:s');

                /*$password = Str::random(8);
                $hasPassword = Hash::make($password);*/

                $user_name = is_null($check_user->first_name) ? '' : $check_user->first_name . ' ' . (is_null($check_user->last_name) ? '' : $check_user->last_name);

                Mail::send('emails.new_account_cred', ['email' => $check_user->email, 'user_name' => $user_name], function ($message) use ($request, $check_user) {

                    $message->from((env('MAIL_USERNAME')), 'KMIOU');
                    $message->to($check_user->email);
                    $message->subject('KMIOU New Account Details');

                });
            }
            // else if($request->status == '2'){

            //   $checkuser->status="2";
            // }

            $check_user->save();

            return json_encode(['success' => 1, 'msg' => 'User Status Changed', 'result' => '[]']);
        }
    }

    /*end transporter functions*/

    // notification from admin

    public function show_notification_from_admin()
    {
        $user = array();
        $transporter = array();
        $driver = array();

        $user = DB::select('select user.id,
          user.first_name,user.last_name
          from users as user
          where status = "1" and user_type = "2" order by created_at desc ');

        $transporter = DB::select('select user.id,
          user.first_name,user.last_name
          from users as user
          where status = "1" and user_type = "3" order by created_at desc ');

        $driver = DB::select('select user.id,
          user.first_name,user.last_name
          from users as user
          where status = "1" and user_type = "4" order by created_at desc ');

        /*if($transporter != null){
        $user = array_merge($user, $transporter);
        }

        if($driver != null){
        $user = array_merge($user, $driver);
        }*/

        return view('admin/notification/send_notification', compact('user', 'transporter', 'driver'));
    }

    public function send_notification_from_admin(Request $request)
    {
        $user_id = Auth::guard('admin')->user()->id;

        if ($request->user_type != '') {

            foreach ($request->user_id as $key => $value) {

                $user = User::find($value);

                if ($user != null && $user->user_type == $request->user_type) {

                    // send notification
                    Helper::send_admin_push_notification($user_id, $value, $request->title, $request->message, '16', '0');
                }
            }
        }

        return back()->with('alert-success', 'Notification Sent Successfully');
    }

    public function driver_list(Request $request)
    {
        $query = '';
        if (isset($request->filter_type) && $request->filter_type == '1') {

            $query = 'users.user_type = "4"';
        } else if (isset($request->filter_type) && $request->filter_type == '2') {

            $query = 'users.user_type = "4" AND users.ref_id = "0" ';

        } else {

            $query = 'users.user_type IN ("4")';
        }

        $filder_query = '';
        $status = isset($request->id) ? $request->id : "-1";
        if ($status != "-1") {
            $filder_query = ' AND users.approve = "' . $request->id . '"';
        }

        /* List driver */

        /*$data = DB::table('users')->where('user_type','!=','1')->where('user_type','!=','2')->where('status','!=','2')->orderBy('created_at','desc')->get();*/

        $data = DB::select('select users.*,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name
        from users
        left join users as transporter on transporter.id = users.ref_id
        where ' . $query . ' AND users.status != "2" ' . $filder_query . ' order by created_at desc ');

        return view('admin/transporter/driver_list', compact('data', 'status'));
    }

}
