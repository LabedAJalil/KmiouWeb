<?php

namespace App\Http\Controllers\Admin;

use App\Commission;
use App\Coupon;
use App\Goods_type;
use App\Helper;
use App\Http\Controllers\Controller;
use App\Payment_info;
use App\Review;
use App\Shipment;
use App\Shipment_bid;
use App\Shipment_info;
use App\Shipment_surge_price;
use App\Surge_price;
use App\Track_shipment;
use App\Truck;
use App\Truck_capacity;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require './vendor/Twilio/autoload.php';

use Validator;

class ShipmentController extends Controller
{
    public function request_list(Request $request)
    {

        if (isset($request->filter_type) && $request->filter_type == '1') {

            $query = 'shipment.created_at asc';
        } else {

            $query = 'shipment.created_at desc';
        }

        $data = DB::select('select shipment.*,
       			users.first_name as user_first_name,users.last_name as user_last_name,
       			driver.first_name as driver_first_name,driver.last_name as driver_last_name,info.quotation_type,count(shipment_bid.id) as total_bidder_count
       			from shipment
       			left join users on users.id=shipment.user_id
            left join shipment_bid on shipment_bid.shipment_id = shipment.id
       			left join users as driver on driver.id=shipment.driver_id
            left join shipment_info as info on info.shipment_id=shipment.id
       			where shipment.status = "0" AND info.quotation_type != "2" group by shipment.id order by ' . $query . '  ');

        return view('admin.shipment.request_list', compact('data'));

    }

    public function quote_request_list(Request $request)
    {

        if (isset($request->filter_type) && $request->filter_type == '1') {

            $query = 'shipment.created_at asc';

        } else {

            $query = 'shipment.created_at desc';
        }

        $data = DB::select('select shipment.*,
            users.first_name as user_first_name,users.last_name as user_last_name,
            driver.first_name as driver_first_name,driver.last_name as driver_last_name,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,info.quotation_type
            from shipment
            left join users on users.id=shipment.user_id
            left join users as driver on driver.id=shipment.driver_id
            left join users as transporter on transporter.id=shipment.transporter_id
            left join shipment_info as info on info.shipment_id=shipment.id
            where (shipment.status = "0" OR (shipment.status = "1" AND shipment.driver_id = "0" )  ) AND info.quotation_type = "2" order by ' . $query . ' ');

        return view('admin.shipment.quote_request_list', compact('data'));

    }

    public function ongoing_list(Request $request)
    {
        $query = '';
        if (isset($request->filter_type) && $request->filter_type == '1') {

            $query = 'shipment.status = "1" ';
        } else {

            $query = 'shipment.status IN ("1","2","4","5","8","9")';
        }

        $data = DB::select('select shipment.*,info.pickup_date,info.shipment_id,
            users.first_name as user_first_name,users.last_name as user_last_name,
            driver.first_name as driver_first_name,driver.last_name as driver_last_name
            from shipment
            left join users on users.id=shipment.user_id
            left join users as driver on driver.id=shipment.driver_id
            left join shipment_info as info on shipment.id=info.shipment_id
            where ' . $query . ' GROUP by info.shipment_id order by shipment.created_at desc');

        return view('admin.shipment.ongoing_list', compact('data'));

    }

    public function cancelled_list()
    {
        $data = DB::select('select shipment.*,info.pickup_date,info.shipment_id,
            users.first_name as user_first_name,users.last_name as user_last_name,
            driver.first_name as driver_first_name,driver.last_name as driver_last_name
            from shipment
            left join users on users.id=shipment.user_id
            left join users as driver on driver.id=shipment.driver_id
            left join shipment_info as info on shipment.id=info.shipment_id
            where shipment.status IN ("3") GROUP by info.shipment_id order by shipment.created_at desc');

        return view('admin.shipment.cancelled_list', compact('data'));

    }

    public function completed_list()
    {

        $data = DB::select('select shipment.*,info.pickup_date,info.arrive_pickup_date,info.arrive_drop_date,info.shipment_id,
            users.first_name as user_first_name,users.last_name as user_last_name,
            driver.first_name as driver_first_name,driver.last_name as driver_last_name,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name
            from shipment
            left join users on users.id=shipment.user_id
            left join users as driver on driver.id=shipment.driver_id
            left join users as transporter on transporter.id=shipment.transporter_id
            left join shipment_info as info on shipment.id=info.shipment_id
            where shipment.status IN ("6") GROUP by info.shipment_id order by shipment.created_at desc');

        return view('admin.shipment.completed_list', compact('data'));

    }

    public function completed_list_filter(Request $request)
    {
        $query = '';
        if ($request->from_date != null) {

            $query .= ' AND shipment.created_at >= "' . $request->from_date . '" ';
        }

        if ($request->end_date != null) {

            $query .= ' AND shipment.created_at <= date_add("' . $request->end_date . '", INTERVAL 1 DAY)';
        }

        $data = DB::select('select shipment.*,info.pickup_date,info.arrive_pickup_date,info.arrive_drop_date,info.shipment_id,
          users.first_name as user_first_name,users.last_name as user_last_name,
          driver.first_name as driver_first_name,driver.last_name as driver_last_name,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name
          from shipment
          left join users on users.id=shipment.user_id
          left join users as driver on driver.id=shipment.driver_id
          left join users as transporter on transporter.id=shipment.transporter_id
          left join shipment_info as info on shipment.id=info.shipment_id
          where shipment.status IN ("6") ' . $query . ' GROUP by info.shipment_id order by shipment.created_at desc');

        return json_encode(['success' => 1, 'msg' => 'success', 'result' => $data]);

    }

    public function reported_list()
    {
        $data = DB::select('select shipment.*,info.pickup_date,info.shipment_id,
                users.first_name as user_first_name,users.last_name as user_last_name,
                driver.first_name as driver_first_name,driver.last_name as driver_last_name
                from shipment
                left join users on users.id=shipment.user_id
                left join users as driver on driver.id=shipment.driver_id
                left join shipment_info as info on shipment.id=info.shipment_id
                where shipment.status IN ("7") GROUP by info.shipment_id order by shipment.created_at desc');

        return view('admin.shipment.reported_list', compact('data'));

    }

    public function review_list()
    {

        $data = DB::select('select review.*,
            users.first_name as user_first_name,users.last_name as user_last_name,
            driver.first_name as driver_first_name,driver.last_name as driver_last_name
            from review
            left join users on users.id=review.user_id
            left join shipment on shipment.id=review.ref_id
            left join users as driver on driver.id=shipment.driver_id
            where review.status = "1" order by shipment.created_at desc');

        return view('admin.shipment.review_list', compact('data'));

    }

    public function remove_review(Request $request)
    {
        $data = Review::find($request->id);
        $data->status = '2';
        if ($data->save()) {
            return json_encode(['success' => 1, 'msg' => trans('Review Removed Successfully'), 'result' => []]);
        }
    }

    public function payment_info_list()
    {

        $data = DB::select('select shipment.*,info.pickup_date,info.shipment_id,info.quotation_type,
          users.first_name as user_first_name,users.last_name as user_last_name,
          driver.first_name as driver_first_name,driver.last_name as driver_last_name,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,payment_info.type as commission_type,payment_info.percent as commission_percent,payment_info.admin_portion
          from shipment
          left join users on users.id=shipment.user_id
          left join users as driver on driver.id=shipment.driver_id
          left join users as transporter on transporter.id=shipment.transporter_id
          left join shipment_info as info on shipment.id=info.shipment_id
          left join payment_info as payment_info on shipment.id=payment_info.shipment_id
          where shipment.status != "0" and shipment.status != "3"
          GROUP by info.shipment_id order by shipment.created_at desc');

        $user = array();
        $transporter = array();
        $driver = array();

        $user = DB::select('select shipment.user_id,
          user.first_name as user_first_name,user.last_name as user_last_name
          from shipment
          left join users as user on user.id=shipment.user_id
          where shipment.status != "0" and shipment.status != "3" group by shipment.user_id order by shipment.created_at desc ');

        $transporter = DB::select('select shipment.user_id,
          user.first_name as user_first_name,user.last_name as user_last_name
          from shipment
          left join users as user on user.id=shipment.transporter_id
          where shipment.status != "0" and shipment.status != "3" AND shipment.transporter_id != "0" group by shipment.transporter_id order by shipment.created_at desc ');

        $driver = DB::select('select shipment.user_id,
          user.first_name as user_first_name,user.last_name as user_last_name
          from shipment
          left join users as user on user.id=shipment.driver_id
          where shipment.status != "0" and shipment.status != "3" AND shipment.driver_id != "0" group by shipment.driver_id order by shipment.created_at desc ');

        if ($transporter != null) {
            $user = array_merge($user, $transporter);
        }

        if ($driver != null) {
            $user = array_merge($user, $driver);
        }

        return view('admin.payment_info.list', compact('data', 'user'));

    }

    public function payment_info_filter(Request $request)
    {

        $query = '';
        $user_type = '';
        $select_user = '';

        if ($request->user_type != null) {

            if (count($request->user_type) < 2) {

                $user_type = $request['user_type'][0];
                if ($user_type == '3') {

                    $query .= ' AND shipment.transporter_id != "0" ';

                } else {

                    $query .= ' AND shipment.transporter_id = "0" ';
                }
            }

        }

        if ($request->search_user != null) {

            $query .= '  AND ( (LOWER(CAST(users.first_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%' . $request->search_user . '%")  OR (LOWER(CAST(users.last_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%' . $request->search_user . '%") OR (LOWER(CAST(driver.first_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%' . $request->search_user . '%")  OR (LOWER(CAST(driver.last_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%' . $request->search_user . '%") OR  (LOWER(CAST(transporter.first_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%' . $request->search_user . '%")  OR (LOWER(CAST(transporter.last_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%' . $request->search_user . '%")   )';
        }

        if ($request->from_date != null) {

            $query .= ' AND shipment.created_at >= "' . $request->from_date . '" ';
        }

        if ($request->end_date != null) {

            $query .= ' AND shipment.created_at <= date_add("' . $request->end_date . '", INTERVAL 1 DAY)';
        }

        if ($request->payment_status != null) {

            $query .= ' AND shipment.payment_status = "' . $request->payment_status . '" ';
        }

        if ($request->payment_type != null) {

            $query .= ' AND shipment.payment_type = "' . $request->payment_type . '" ';
        }

        if ($request->quotation_type != null) {

            $query .= ' AND info.quotation_type = "' . $request->quotation_type . '" ';
        }

        $data = DB::select('select shipment.*,info.pickup_date,info.shipment_id,info.quotation_type,
          users.first_name as user_first_name,users.last_name as user_last_name,
          driver.first_name as driver_first_name,driver.last_name as driver_last_name,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,payment_info.type as commission_type,payment_info.percent as commission_percent,payment_info.admin_portion
          from shipment
          left join users on users.id=shipment.user_id
          left join users as driver on driver.id=shipment.driver_id
          left join users as transporter on transporter.id=shipment.transporter_id
          left join shipment_info as info on shipment.id=info.shipment_id
          left join payment_info as payment_info on shipment.id=payment_info.shipment_id
          where shipment.status != "0" and shipment.status != "3" ' . $query . ' GROUP by info.shipment_id order by shipment.created_at desc ');

        return json_encode(['success' => 1, 'msg' => 'success', 'result' => $data]);
    }

    public function users_payment_info_list(Request $request)
    {
        if (isset($request->user_id) && $request->user_id != '' && $request->user_id != null && $request->user_id != '0') {

            $user_id = $request->user_id;

            $data = DB::select('select shipment.*,info.pickup_date,info.shipment_id,info.quotation_type,
          users.first_name as user_first_name,users.last_name as user_last_name,
          driver.first_name as driver_first_name,driver.last_name as driver_last_name,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,payment_info.type as commission_type,payment_info.percent as commission_percent,payment_info.admin_portion
          from shipment
          left join users on users.id=shipment.user_id
          left join users as driver on driver.id=shipment.driver_id
          left join users as transporter on transporter.id=shipment.transporter_id
          left join shipment_info as info on shipment.id=info.shipment_id
          left join payment_info as payment_info on shipment.id=payment_info.shipment_id
          where shipment.status != "0" and shipment.status != "3" and (shipment.transporter_id = ' . $user_id . ' OR shipment.driver_id = ' . $user_id . '  )
          GROUP by info.shipment_id order by shipment.created_at desc');

            return view('admin.payment_info.users_payment_list', compact('data', 'user_id'));
        } else {
            return view('user.link_expire');
        }

    }

    public function users_payment_info_filter(Request $request)
    {
        if ($request->user_id != '0' && $request->user_id != '' && $request->user_id != null) {

            $query = '';
            $user_id = $request->user_id;

            if ($request->from_date != null) {

                $query .= ' AND shipment.created_at >= "' . $request->from_date . '" ';
            }

            if ($request->end_date != null) {

                $query .= ' AND shipment.created_at <= date_add("' . $request->end_date . '", INTERVAL 1 DAY)';
            }

            if ($request->payment_status != null) {

                $query .= ' AND shipment.payment_status = "' . $request->payment_status . '" ';
            }

            if ($request->payment_type != null) {

                $query .= ' AND shipment.payment_type = "' . $request->payment_type . '" ';
            }

            if ($request->quotation_type != null) {

                $query .= ' AND info.quotation_type = "' . $request->quotation_type . '" ';
            }

            $data = DB::select('select shipment.*,info.pickup_date,info.shipment_id,info.quotation_type,
              users.first_name as user_first_name,users.last_name as user_last_name,
              driver.first_name as driver_first_name,driver.last_name as driver_last_name,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,payment_info.type as commission_type,payment_info.percent as commission_percent,payment_info.admin_portion
              from shipment
              left join users on users.id=shipment.user_id
              left join users as driver on driver.id=shipment.driver_id
              left join users as transporter on transporter.id=shipment.transporter_id
              left join shipment_info as info on shipment.id=info.shipment_id
              left join payment_info as payment_info on shipment.id=payment_info.shipment_id
              where shipment.status != "0" and shipment.status != "3" and (shipment.transporter_id = ' . $user_id . ' OR shipment.driver_id = ' . $user_id . '  ) ' . $query . ' GROUP by info.shipment_id order by shipment.created_at desc ');

            return json_encode(['success' => 1, 'msg' => 'success', 'result' => $data]);
        } else {
            return json_encode(['success' => 0, 'msg' => 'user not found', 'result' => []]);
        }
    }

    // performance report
    public function performance_report_list(Request $request)
    {
        try {

            $query = '';
            $user_type_name = '';
            /*if($request->user_type != null){

            if($request->user_type == '3'){*/
            $query = 'transporter_id != "0" ';
            $user_type_name = 'transporter_id';
            /*}else{
            $query = 'transporter_id = "0" and driver_id != "0" ';
            $user_type_name = 'driver_id';
            }
            }*/

            $get_user_ids = DB::select('SELECT GROUP_CONCAT(' . $user_type_name . ') as user_ids FROM `shipment` where ' . $query . ' ');

            $data = array();

            if ($get_user_ids[0]->user_ids != null) {

                $select_users = DB::select('SELECT users.id as user_id,users.first_name,users.last_name,users.user_type FROM `users` where id in (' . $get_user_ids[0]->user_ids . ') group by users.id ');

                if ($select_users != []) {

                    foreach ($select_users as $key => $value) {

                        $get_total_accepted_shipment = DB::select('select count(id) as accepted_shipment_count
                  from shipment
                  where shipment.status != "0" and shipment.status != "3" and shipment.' . $user_type_name . ' = ' . $value->user_id . ' and ' . $query . ' ');

                        $get_total_rejected_shipment = DB::select('select count(id) as rejected_shipment_count
                  from shipment
                  where shipment.status = "3" and shipment.' . $user_type_name . ' = ' . $value->user_id . ' and ' . $query . ' ');

                        $data1 = array();
                        $data1['user_id'] = $value->user_id;
                        $data1['user_first_name'] = is_null($value->first_name) ? '' : $value->first_name;
                        $data1['user_last_name'] = is_null($value->last_name) ? '' : $value->last_name;
                        $data1['user_type'] = $value->user_type;
                        $data1['accepted_shipment_count'] = $get_total_accepted_shipment[0]->accepted_shipment_count;
                        $data1['rejected_shipment_count'] = $get_total_rejected_shipment[0]->rejected_shipment_count;
                        array_push($data, $data1);
                    }
                }
            }

            return view('admin.performance_report.list', compact('data'));

        } catch (Exception $ex) {

            return back()->with('alert-warning', $ex->getMessage());
        }
    }

    public function performance_report_filter(Request $request)
    {
        try {

            $query = '';
            $user_type_name = '';

            if ($request->user_type != null) {

                if ($request->user_type == '3') {
                    $query = 'transporter_id != "0" ';
                    $user_type_name = 'transporter_id';
                } else if ($request->user_type == '4') {
                    $query = 'transporter_id = "0" and driver_id != "0" ';
                    $user_type_name = 'driver_id';
                }
            }

            $get_user_ids = DB::select('SELECT GROUP_CONCAT(' . $user_type_name . ') as user_ids FROM `shipment` where ' . $query . ' ');

            if ($request->from_date != null) {

                $query .= ' AND shipment.created_at >= "' . $request->from_date . '" ';
            }

            if ($request->end_date != null) {

                $query .= ' AND shipment.created_at <= date_add("' . $request->end_date . '", INTERVAL 1 DAY)';
            }

            $data = array();

            if ($get_user_ids[0]->user_ids != null) {

                $select_users = DB::select('SELECT users.id as user_id,users.first_name,users.last_name,users.user_type FROM `users` where id in (' . $get_user_ids[0]->user_ids . ') group by users.id ');

                if ($select_users != []) {

                    foreach ($select_users as $key => $value) {

                        $get_total_accepted_shipment = DB::select('select count(id) as accepted_shipment_count
                  from shipment
                  where shipment.status != "0" and shipment.status != "3" and shipment.' . $user_type_name . ' = ' . $value->user_id . ' and ' . $query . ' ');

                        $get_total_rejected_shipment = DB::select('select count(id) as rejected_shipment_count
                  from shipment
                  where shipment.status = "3" and shipment.' . $user_type_name . ' = ' . $value->user_id . ' and ' . $query . ' ');

                        $data1 = array();
                        $data1['user_id'] = $value->user_id;
                        $data1['user_first_name'] = is_null($value->first_name) ? '' : $value->first_name;
                        $data1['user_last_name'] = is_null($value->last_name) ? '' : $value->last_name;
                        $data1['user_type'] = $value->user_type;
                        $data1['accepted_shipment_count'] = $get_total_accepted_shipment[0]->accepted_shipment_count;
                        $data1['rejected_shipment_count'] = $get_total_rejected_shipment[0]->rejected_shipment_count;
                        array_push($data, $data1);
                    }
                }
            }

            return json_encode(['success' => 1, 'msg' => 'success', 'result' => $data]);

        } catch (Exception $ex) {

            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage()]);
        }
    }

    public function show_active_shipment_details($id)
    {
        $this->timezone = Auth::guard('admin')->user()->timezone;

        $user_id = Auth::guard('admin')->user()->id;

        $details = array();

        $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.mobile_no as shipper_mobile,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,transporter.profile_pic as transporter_profile_pic ,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,transporter.mobile_no as transporter_mobile, truck.truck_img, truck.truck_name,info.sender_first_name, info.sender_last_name, info.sender_mobile, info.receiver_first_name, info.receiver_last_name, info.receiver_mobile
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.user_id
              left join users as driver on driver.id=shipment.driver_id
              left join users as transporter on transporter.id=shipment.transporter_id
              left join truck on truck.id=shipment.vehicle_id
             WHERE shipment.id = ' . $id . ' AND shipment.status IN ("1","2","4","5","8") ');

        if ($select_shipment != null) {

            foreach ($select_shipment as $key => $value) {

                $select_bidder = Shipment_bid::where('shipment_id', $value->id)->first();

                $data1 = array();

                $total_amount = $value->amount;

                if ($value->discount_amount != '0') {
                    $total_amount = $total_amount - $value->discount_amount;
                }

                $goods = array();

                if ($value->goods_type != '' && $value->goods_type != null) {

                    $goods = explode(",", $value->goods_type);
                }

                $goods_type_name = '';

                foreach ($goods as $key => $goods_value) {

                    if ($key != '0') {
                        $goods_type_name .= ', ';
                    }

                    $goods = Goods_type::find($goods_value);

                    if ($goods != null) {
                        $goods_type_name .= $goods->goods_type_name;
                    }
                }

                $data1['shipment_id'] = $value->id;
                $data1['shipper_id'] = $value->user_id;
                $data1['driver_id'] = $value->driver_id;
                $data1['transporter_id'] = $value->transporter_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name) ? '' : $value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name) ? '' : $value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic) ? '' : $value->shipper_profile_pic;
                $data1['shipper_mobile'] = is_null($value->shipper_mobile) ? '' : $value->shipper_mobile;
                $data1['driver_first_name'] = is_null($value->driver_first_name) ? '' : $value->driver_first_name;
                $data1['driver_last_name'] = is_null($value->driver_last_name) ? '' : $value->driver_last_name;
                $data1['driver_profile_pic'] = is_null($value->driver_profile_pic) ? '' : $value->driver_profile_pic;
                $data1['driver_mobile'] = is_null($value->driver_mobile) ? '' : $value->driver_mobile;
                $data1['transporter_first_name'] = is_null($value->transporter_first_name) ? '' : $value->transporter_first_name;
                $data1['transporter_last_name'] = is_null($value->transporter_last_name) ? '' : $value->transporter_last_name;
                $data1['transporter_profile_pic'] = is_null($value->transporter_profile_pic) ? '' : $value->transporter_profile_pic;
                $data1['transporter_mobile'] = is_null($value->transporter_mobile) ? '' : $value->transporter_mobile;
                $data1['sender_first_name'] = is_null($value->sender_first_name) ? '' : $value->sender_first_name;
                $data1['sender_last_name'] = is_null($value->sender_last_name) ? '' : $value->sender_last_name;
                $data1['sender_mobile'] = is_null($value->sender_mobile) ? '' : $value->sender_mobile;

                $data1['receiver_first_name'] = is_null($value->receiver_first_name) ? '' : $value->receiver_first_name;
                $data1['receiver_last_name'] = is_null($value->receiver_last_name) ? '' : $value->receiver_last_name;
                $data1['receiver_mobile'] = is_null($value->receiver_mobile) ? '' : $value->receiver_mobile;

                $data1['quotation_type'] = $value->quotation_type;
                $data1['quotation_amount'] = is_null($value->quotation_amount) ? 0 : $value->quotation_amount;
                $data1['pickup'] = is_null($value->pickup) ? '' : $value->pickup;
                $data1['pickup_date'] = date('jS F Y H:i', strtotime($value->pickup_date));
                $data1['drop'] = is_null($value->drop) ? '' : $value->drop;
                $data1['service_type'] = '';
                $data1['goods_type_name'] = $goods_type_name;
                $data1['weight'] = is_null($value->weight) ? 0 : $value->weight;
                $data1['weight_type'] = is_null($value->weight_type) ? 0 : $value->weight_type;
                $data1['no_of_vehicle'] = is_null($value->total_vehicle) ? 0 : $value->total_vehicle;
                $data1['info'] = is_null($value->info) ? '' : $value->info;
                $data1['truck_name'] = is_null($value->truck_name) ? '' : $value->truck_name;
                $data1['truck_img'] = is_null($value->truck_img) ? '' : $value->truck_img;
                $data1['base_fare'] = is_null($value->amount) ? '0' : $value->amount;
                $data1['amount'] = is_null($value->amount) ? '0' : $value->amount;
                $data1['total_amount'] = $total_amount;
                $data1['tax_per'] = is_null($value->tax_per) ? '0' : $value->tax_per;
                $data1['tax_amount'] = is_null($value->tax_amount) ? '0' : $value->tax_amount;
                $data1['discount_per'] = is_null($value->discount_per) ? '0' : $value->discount_per;
                $data1['discount_amount'] = is_null($value->discount_amount) ? '0' : $value->discount_amount;

                $get_kmiou_charges = Payment_info::where('shipment_id', $value->id)->first();

                if ($get_kmiou_charges != null) {

                    $data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent) ? '0 %' : $get_kmiou_charges->percent . '(%)';
                    $data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion) ? '0 DA' : $get_kmiou_charges->admin_portion . ' DA';

                    $base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
                    $data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0') ? '' : $base_fare;

                } else {

                    $data1['kmiou_charges_per'] = '0 %';
                    $data1['kmiou_charges_amount'] = '0 DA';
                }
                $data1['bidder_count'] = is_null($select_bidder) ? '0' : '1';
                $data1['document'] = is_null($value->document) ? '' : $value->document;
                $data1['status'] = is_null($value->status) ? '' : $value->status;
                $data1['bid_status'] = is_null($value->bid_status) ? '' : $value->bid_status;
                $data1['payment_type'] = is_null($value->payment_type) ? 0 : $value->payment_type;
                $data1['payment_status'] = is_null($value->payment_status) ? '' : $value->payment_status;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);

                array_push($details, $data1);
            }
        } else {

            return redirect()->route('shipmentApprovedList');
        }

        $doc = array();

        if ($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null) {

            $str = $select_shipment[0]->document;

            $doc = explode("#####", $str);
        }

        $bid = array();

        $bid = DB::select('Select shipment_bid.*,users.first_name as user_first_name,users.last_name as user_last_name,users.profile_pic as user_profile_pic,users.total_rate_count as total_rate_count,users.avg_rating as avg_rating from shipment_bid left join users on users.id=shipment_bid.user_id where shipment_id = ' . $id . ' ');

        return view('admin.shipment.active_shipment_details', compact('details', 'doc', 'bid'));
    }

    public function show_shipment_request_details($id)
    {
        $this->timezone = Auth::guard('admin')->user()->timezone;

        $user_id = Auth::guard('admin')->user()->id;

        $details = array();

        $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.mobile_no as shipper_mobile,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,transporter.profile_pic as transporter_profile_pic ,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,transporter.mobile_no as transporter_mobile, truck.truck_img, truck.truck_name
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.user_id
              left join users as driver on driver.id=shipment.driver_id
              left join users as transporter on transporter.id=shipment.transporter_id
              left join truck on truck.id=shipment.vehicle_id
             WHERE shipment.id = ' . $id . ' AND shipment.status = "0" ');

        if ($select_shipment != null) {

            foreach ($select_shipment as $key => $value) {

                $select_bidder = Shipment_bid::where('shipment_id', $value->id)->first();

                $data1 = array();

                $total_amount = $value->amount;

                if ($value->discount_amount != '0') {
                    $total_amount = $total_amount - $value->discount_amount;
                }

                $goods = array();

                if ($value->goods_type != '' && $value->goods_type != null) {

                    $goods = explode(",", $value->goods_type);
                }

                $goods_type_name = '';

                foreach ($goods as $key => $goods_value) {

                    if ($key != '0') {
                        $goods_type_name .= ', ';
                    }

                    $goods = Goods_type::find($goods_value);

                    if ($goods != null) {
                        $goods_type_name .= $goods->goods_type_name;
                    }
                }

                $data1['shipment_id'] = $value->id;
                $data1['shipper_id'] = $value->user_id;
                $data1['driver_id'] = $value->driver_id;
                $data1['transporter_id'] = $value->transporter_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name) ? '' : $value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name) ? '' : $value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic) ? '' : $value->shipper_profile_pic;
                $data1['shipper_mobile'] = is_null($value->shipper_mobile) ? '' : $value->shipper_mobile;
                $data1['driver_first_name'] = is_null($value->driver_first_name) ? '' : $value->driver_first_name;
                $data1['driver_last_name'] = is_null($value->driver_last_name) ? '' : $value->driver_last_name;
                $data1['driver_profile_pic'] = is_null($value->driver_profile_pic) ? '' : $value->driver_profile_pic;
                $data1['driver_mobile'] = is_null($value->driver_mobile) ? '' : $value->driver_mobile;
                $data1['transporter_first_name'] = is_null($value->transporter_first_name) ? '' : $value->transporter_first_name;
                $data1['transporter_last_name'] = is_null($value->transporter_last_name) ? '' : $value->transporter_last_name;
                $data1['transporter_profile_pic'] = is_null($value->transporter_profile_pic) ? '' : $value->transporter_profile_pic;
                $data1['transporter_mobile'] = is_null($value->transporter_mobile) ? '' : $value->transporter_mobile;
                $data1['quotation_type'] = $value->quotation_type;
                $data1['quotation_amount'] = is_null($value->quotation_amount) ? 0 : $value->quotation_amount;
                $data1['pickup'] = is_null($value->pickup) ? '' : $value->pickup;
                $data1['pickup_date'] = date('jS F Y H:i', strtotime($value->pickup_date));
                $data1['drop'] = is_null($value->drop) ? '' : $value->drop;
                $data1['service_type'] = '';
                $data1['goods_type_name'] = $goods_type_name;
                $data1['weight'] = is_null($value->weight) ? 0 : $value->weight;
                $data1['weight_type'] = is_null($value->weight_type) ? 0 : $value->weight_type;
                $data1['no_of_vehicle'] = is_null($value->total_vehicle) ? 0 : $value->total_vehicle;
                $data1['truck_name'] = is_null($value->truck_name) ? '' : $value->truck_name;
                $data1['truck_img'] = is_null($value->truck_img) ? '' : $value->truck_img;
                $data1['info'] = is_null($value->info) ? '' : $value->info;
                $data1['base_fare'] = is_null($value->amount) ? '0' : $value->amount;
                $data1['amount'] = is_null($value->amount) ? '' : $value->amount;
                $data1['total_amount'] = $total_amount;
                $data1['tax_per'] = is_null($value->tax_per) ? '0' : $value->tax_per;
                $data1['tax_amount'] = is_null($value->tax_amount) ? '0' : $value->tax_amount;
                $data1['discount_per'] = is_null($value->discount_per) ? '0' : $value->discount_per;
                $data1['discount_amount'] = is_null($value->discount_amount) ? '0' : $value->discount_amount;

                $get_kmiou_charges = Payment_info::where('shipment_id', $value->id)->first();

                if ($get_kmiou_charges != null) {

                    $data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent) ? '0 %' : $get_kmiou_charges->percent . '(%)';
                    $data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion) ? '0 DA' : $get_kmiou_charges->admin_portion . ' DA';

                    $base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
                    $data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0') ? '' : $base_fare;

                } else {

                    $data1['kmiou_charges_per'] = '0 %';
                    $data1['kmiou_charges_amount'] = '0 DA';
                }

                $data1['document'] = is_null($value->document) ? '' : $value->document;
                $data1['bidder_count'] = is_null($select_bidder) ? '0' : '1';
                $data1['status'] = is_null($value->status) ? '' : $value->status;
                $data1['payment_type'] = is_null($value->payment_type) ? 0 : $value->payment_type;
                $data1['payment_status'] = is_null($value->payment_status) ? '' : $value->payment_status;
                $data1['bid_status'] = is_null($value->bid_status) ? '' : $value->bid_status;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);

                array_push($details, $data1);
            }
        } else {

            return redirect()->route('shipmentRequestList');
        }

        $doc = array();

        if ($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null) {

            $str = $select_shipment[0]->document;

            $doc = explode("#####", $str);
        }

        $bid = DB::select('Select shipment_bid.*,users.first_name as user_first_name,users.last_name as user_last_name,users.profile_pic as user_profile_pic from shipment_bid left join users on users.id=shipment_bid.user_id where shipment_id = ' . $id . ' ');

        return view('admin.shipment.shipment_request_details', compact('details', 'doc', 'bid'));
    }

    public function set_shipment_amount(Request $request)
    {
        $this->timezone = Auth::guard('admin')->user()->timezone;

        $user_id = Auth::guard('admin')->user()->id;

        $shipment = Shipment::find($request->shipment_id);

        if ($shipment && $shipment->amount == '0') {

            $shipment->amount = $request->shipment_amount;

            $shipment->save();
        }

        session()->flash('alert-success', 'Shipment Amount Updated');

        return json_encode(['success' => 1, 'msg' => 'Success', 'result' => []]);
    }

    public function show_cancelled_shipment_details($id)
    {
        $this->timezone = Auth::guard('admin')->user()->timezone;

        $user_id = Auth::guard('admin')->user()->id;

        $details = array();

        $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.person_name,info.no_of_vehicle,info.total_vehicle,info.pickup_date,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.mobile_no as shipper_mobile,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,transporter.profile_pic as transporter_profile_pic ,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,transporter.mobile_no as transporter_mobile, truck.truck_img, truck.truck_name
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.user_id
              left join users as driver on driver.id=shipment.driver_id
              left join users as transporter on transporter.id=shipment.transporter_id
              left join truck on truck.id=shipment.vehicle_id
             WHERE shipment.id = ' . $id . ' AND shipment.status = "3" ');

        if ($select_shipment != null) {

            foreach ($select_shipment as $key => $value) {

                $data1 = array();

                $total_amount = $value->amount;

                if ($value->discount_amount != '0') {
                    $total_amount = $total_amount - $value->discount_amount;
                }

                $goods = array();

                if ($value->goods_type != '' && $value->goods_type != null) {

                    $goods = explode(",", $value->goods_type);
                }

                $goods_type_name = '';

                foreach ($goods as $key => $goods_value) {

                    if ($key != '0') {
                        $goods_type_name .= ', ';
                    }

                    $goods = Goods_type::find($goods_value);

                    if ($goods != null) {
                        $goods_type_name .= $goods->goods_type_name;
                    }
                }

                $data1['shipment_id'] = $value->id;
                $data1['shipper_id'] = $value->user_id;
                $data1['driver_id'] = $value->driver_id;
                $data1['transporter_id'] = $value->transporter_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name) ? '' : $value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name) ? '' : $value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic) ? '' : $value->shipper_profile_pic;
                $data1['shipper_mobile'] = is_null($value->shipper_mobile) ? '' : $value->shipper_mobile;
                $data1['driver_first_name'] = is_null($value->driver_first_name) ? '' : $value->driver_first_name;
                $data1['driver_last_name'] = is_null($value->driver_last_name) ? '' : $value->driver_last_name;
                $data1['driver_profile_pic'] = is_null($value->driver_profile_pic) ? '' : $value->driver_profile_pic;
                $data1['driver_mobile'] = is_null($value->driver_mobile) ? '' : $value->driver_mobile;
                $data1['transporter_first_name'] = is_null($value->transporter_first_name) ? '' : $value->transporter_first_name;
                $data1['transporter_last_name'] = is_null($value->transporter_last_name) ? '' : $value->transporter_last_name;
                $data1['transporter_profile_pic'] = is_null($value->transporter_profile_pic) ? '' : $value->transporter_profile_pic;
                $data1['transporter_mobile'] = is_null($value->transporter_mobile) ? '' : $value->transporter_mobile;
                $data1['quotation_type'] = $value->quotation_type;
                $data1['quotation_amount'] = is_null($value->quotation_amount) ? 0 : $value->quotation_amount;
                $data1['pickup'] = is_null($value->pickup) ? '' : $value->pickup;
                $data1['pickup_date'] = date('jS F Y H:i', strtotime($value->pickup_date));
                $data1['drop'] = is_null($value->drop) ? '' : $value->drop;
                $data1['service_type'] = '';
                $data1['goods_type_name'] = $goods_type_name;
                $data1['weight'] = is_null($value->weight) ? 0 : $value->weight;
                $data1['weight_type'] = is_null($value->weight_type) ? 0 : $value->weight_type;
                $data1['no_of_vehicle'] = is_null($value->total_vehicle) ? 0 : $value->total_vehicle;
                $data1['info'] = is_null($value->info) ? '' : $value->info;
                $data1['truck_name'] = is_null($value->truck_name) ? '' : $value->truck_name;
                $data1['truck_img'] = is_null($value->truck_img) ? '' : $value->truck_img;
                $data1['person_name'] = is_null($value->person_name) ? '' : $value->person_name;
                $data1['amount'] = is_null($value->amount) ? '' : $value->amount;
                $data1['base_fare'] = is_null($value->amount) ? '0' : $value->amount;
                $data1['total_amount'] = $total_amount;
                $data1['tax_per'] = is_null($value->tax_per) ? '0' : $value->tax_per;
                $data1['tax_amount'] = is_null($value->tax_amount) ? '0' : $value->tax_amount;
                $data1['discount_per'] = is_null($value->discount_per) ? '0' : $value->discount_per;
                $data1['discount_amount'] = is_null($value->discount_amount) ? '0' : $value->discount_amount;

                $get_kmiou_charges = Payment_info::where('shipment_id', $value->id)->first();

                if ($get_kmiou_charges != null) {

                    $data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent) ? '0 %' : $get_kmiou_charges->percent . '(%)';
                    $data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion) ? '0 DA' : $get_kmiou_charges->admin_portion . ' DA';

                    $base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
                    $data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0') ? '' : $base_fare;

                } else {

                    $data1['kmiou_charges_per'] = '0 %';
                    $data1['kmiou_charges_amount'] = '0 DA';
                }

                $data1['cancel_reason'] = is_null($value->cancel_reason) ? '0' : $value->cancel_reason;
                $data1['cancel_comment'] = is_null($value->cancel_comment) ? '' : $value->cancel_comment;
                $data1['document'] = is_null($value->document) ? '' : $value->document;
                $data1['status'] = is_null($value->status) ? '' : $value->status;
                $data1['payment_type'] = is_null($value->payment_type) ? 0 : $value->payment_type;
                $data1['payment_status'] = is_null($value->payment_status) ? '' : $value->payment_status;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);

                array_push($details, $data1);
            }
        } else {

            return redirect()->route('shipmentRequestList');
        }

        $doc = array();

        if ($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null) {

            $str = $select_shipment[0]->document;

            $doc = explode("#####", $str);
        }

        return view('admin.shipment.cancelled_shipment_details', compact('details', 'doc'));

    }

    public function show_reported_shipment_details($id)
    {

        $this->timezone = Auth::guard('admin')->user()->timezone;

        $user_id = Auth::guard('admin')->user()->id;

        $details = array();

        $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.mobile_no as shipper_mobile,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,transporter.profile_pic as transporter_profile_pic ,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,transporter.mobile_no as transporter_mobile, truck.truck_img, truck.truck_name
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.user_id
              left join users as driver on driver.id=shipment.driver_id
              left join users as transporter on transporter.id=shipment.transporter_id
              left join truck on truck.id=shipment.vehicle_id
             WHERE shipment.id = ' . $id . ' AND shipment.status = "7" ');

        if ($select_shipment != null) {

            foreach ($select_shipment as $key => $value) {

                $data1 = array();

                $total_amount = $value->amount;

                if ($value->discount_amount != '0') {
                    $total_amount = $total_amount - $value->discount_amount;
                }

                $goods = array();

                if ($value->goods_type != '' && $value->goods_type != null) {

                    $goods = explode(",", $value->goods_type);
                }

                $goods_type_name = '';

                foreach ($goods as $key => $goods_value) {

                    if ($key != '0') {
                        $goods_type_name .= ', ';
                    }

                    $goods = Goods_type::find($goods_value);

                    if ($goods != null) {
                        $goods_type_name .= $goods->goods_type_name;
                    }
                }

                $data1['shipment_id'] = $value->id;
                $data1['shipper_id'] = $value->user_id;
                $data1['driver_id'] = $value->driver_id;
                $data1['transporter_id'] = $value->transporter_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name) ? '' : $value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name) ? '' : $value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic) ? '' : $value->shipper_profile_pic;
                $data1['shipper_mobile'] = is_null($value->shipper_mobile) ? '' : $value->shipper_mobile;
                $data1['driver_first_name'] = is_null($value->driver_first_name) ? '' : $value->driver_first_name;
                $data1['driver_last_name'] = is_null($value->driver_last_name) ? '' : $value->driver_last_name;
                $data1['driver_profile_pic'] = is_null($value->driver_profile_pic) ? '' : $value->driver_profile_pic;
                $data1['driver_mobile'] = is_null($value->driver_mobile) ? '' : $value->driver_mobile;
                $data1['transporter_first_name'] = is_null($value->transporter_first_name) ? '' : $value->transporter_first_name;
                $data1['transporter_last_name'] = is_null($value->transporter_last_name) ? '' : $value->transporter_last_name;
                $data1['transporter_profile_pic'] = is_null($value->transporter_profile_pic) ? '' : $value->transporter_profile_pic;
                $data1['transporter_mobile'] = is_null($value->transporter_mobile) ? '' : $value->transporter_mobile;
                $data1['quotation_type'] = $value->quotation_type;
                $data1['quotation_amount'] = is_null($value->quotation_amount) ? 0 : $value->quotation_amount;
                $data1['pickup'] = is_null($value->pickup) ? '' : $value->pickup;
                $data1['pickup_date'] = date('jS F Y H:i', strtotime($value->pickup_date));
                $data1['drop'] = is_null($value->drop) ? '' : $value->drop;
                $data1['service_type'] = '';
                $data1['goods_type_name'] = $goods_type_name;
                $data1['weight'] = is_null($value->weight) ? 0 : $value->weight;
                $data1['weight_type'] = is_null($value->weight_type) ? 0 : $value->weight_type;
                $data1['no_of_vehicle'] = is_null($value->total_vehicle) ? 0 : $value->total_vehicle;
                $data1['info'] = is_null($value->info) ? '' : $value->info;
                $data1['truck_name'] = is_null($value->truck_name) ? '' : $value->truck_name;
                $data1['truck_img'] = is_null($value->truck_img) ? '' : $value->truck_img;
                $data1['amount'] = is_null($value->amount) ? '' : $value->amount;
                $data1['base_fare'] = is_null($value->amount) ? '0' : $value->amount;
                $data1['total_amount'] = $total_amount;
                $data1['tax_per'] = is_null($value->tax_per) ? '0' : $value->tax_per;
                $data1['tax_amount'] = is_null($value->tax_amount) ? '0' : $value->tax_amount;
                $data1['discount_per'] = is_null($value->discount_per) ? '0' : $value->discount_per;
                $data1['discount_amount'] = is_null($value->discount_amount) ? '0' : $value->discount_amount;

                $get_kmiou_charges = Payment_info::where('shipment_id', $value->id)->first();

                if ($get_kmiou_charges != null) {

                    $data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent) ? '0 %' : $get_kmiou_charges->percent . '(%)';
                    $data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion) ? '0 DA' : $get_kmiou_charges->admin_portion . ' DA';

                    $base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
                    $data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0') ? '' : $base_fare;

                } else {

                    $data1['kmiou_charges_per'] = '0 %';
                    $data1['kmiou_charges_amount'] = '0 DA';
                }

                $data1['report_emergency'] = is_null($value->report_emergency) ? '0' : $value->report_emergency;
                $data1['report_comment'] = is_null($value->report_comment) ? '' : $value->report_comment;
                $data1['document'] = is_null($value->document) ? '' : $value->document;
                $data1['status'] = is_null($value->status) ? '' : $value->status;
                $data1['payment_type'] = is_null($value->payment_type) ? 0 : $value->payment_type;
                $data1['payment_status'] = is_null($value->payment_status) ? '' : $value->payment_status;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);

                array_push($details, $data1);
            }
        } else {

            return redirect()->route('shipmentApprovedList');
        }

        $doc = array();

        if ($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null) {

            $str = $select_shipment[0]->document;

            $doc = explode("#####", $str);
        }

        $comment = array();

        if ($select_shipment && $select_shipment[0]->report_comment != '' && $select_shipment[0]->report_comment != null) {

            $str = $select_shipment[0]->report_comment;

            $comment = explode("#####", $str);
        }

        return view('admin.shipment.reported_shipment_details', compact('details', 'doc', 'comment'));

    }

    public function show_past_shipment_details($id)
    {
        $this->timezone = Auth::guard('admin')->user()->timezone;

        $user_id = Auth::guard('admin')->user()->id;

        $details = array();

        $select_shipment = DB::select('SELECT shipment.*,info.document,info.quotation_type,info.quotation_amount,info.weight,info.weight_type,info.goods_type,info.info,info.no_of_vehicle,info.total_vehicle,info.pickup_date,info.person_name,info.id_proof_image,info.signature_image,users.profile_pic as shipper_profile_pic ,users.first_name as shipper_first_name,users.last_name as shipper_last_name,users.mobile_no as shipper_mobile,driver.profile_pic as driver_profile_pic ,driver.first_name as driver_first_name,driver.last_name as driver_last_name,driver.mobile_no as driver_mobile,transporter.profile_pic as transporter_profile_pic ,transporter.first_name as transporter_first_name,transporter.last_name as transporter_last_name,transporter.mobile_no as transporter_mobile, truck.truck_img, truck.truck_name
              FROM shipment
              left join shipment_info as info on info.shipment_id=shipment.id
              left join users on users.id=shipment.user_id
              left join users as driver on driver.id=shipment.driver_id
              left join users as transporter on transporter.id=shipment.transporter_id
              left join truck on truck.id=shipment.vehicle_id
             WHERE shipment.id = ' . $id . ' AND shipment.status = "6" ');

        if ($select_shipment != null) {

            foreach ($select_shipment as $key => $value) {

                $data1 = array();

                $total_amount = $value->amount;

                if ($value->discount_amount != '0') {
                    $total_amount = $total_amount - $value->discount_amount;
                }

                $goods = array();

                if ($value->goods_type != '' && $value->goods_type != null) {

                    $goods = explode(",", $value->goods_type);
                }

                $goods_type_name = '';

                foreach ($goods as $key => $goods_value) {

                    if ($key != '0') {
                        $goods_type_name .= ', ';
                    }

                    $goods = Goods_type::find($goods_value);

                    if ($goods != null) {
                        $goods_type_name .= $goods->goods_type_name;
                    }
                }

                $data1['shipment_id'] = $value->id;
                $data1['shipper_id'] = $value->user_id;
                $data1['driver_id'] = $value->driver_id;
                $data1['transporter_id'] = $value->transporter_id;
                $data1['shipper_first_name'] = is_null($value->shipper_first_name) ? '' : $value->shipper_first_name;
                $data1['shipper_last_name'] = is_null($value->shipper_last_name) ? '' : $value->shipper_last_name;
                $data1['shipper_profile_pic'] = is_null($value->shipper_profile_pic) ? '' : $value->shipper_profile_pic;
                $data1['shipper_mobile'] = is_null($value->shipper_mobile) ? '' : $value->shipper_mobile;
                $data1['driver_first_name'] = is_null($value->driver_first_name) ? '' : $value->driver_first_name;
                $data1['driver_last_name'] = is_null($value->driver_last_name) ? '' : $value->driver_last_name;
                $data1['driver_profile_pic'] = is_null($value->driver_profile_pic) ? '' : $value->driver_profile_pic;
                $data1['driver_mobile'] = is_null($value->driver_mobile) ? '' : $value->driver_mobile;
                $data1['transporter_first_name'] = is_null($value->transporter_first_name) ? '' : $value->transporter_first_name;
                $data1['transporter_last_name'] = is_null($value->transporter_last_name) ? '' : $value->transporter_last_name;
                $data1['transporter_profile_pic'] = is_null($value->transporter_profile_pic) ? '' : $value->transporter_profile_pic;
                $data1['transporter_mobile'] = is_null($value->transporter_mobile) ? '' : $value->transporter_mobile;
                $data1['quotation_type'] = $value->quotation_type;
                $data1['quotation_amount'] = is_null($value->quotation_amount) ? 0 : $value->quotation_amount;
                $data1['pickup'] = is_null($value->pickup) ? '' : $value->pickup;
                $data1['pickup_date'] = date('jS F Y H:i', strtotime($value->pickup_date));
                $data1['drop'] = is_null($value->drop) ? '' : $value->drop;
                $data1['service_type'] = '';
                $data1['goods_type_name'] = $goods_type_name;
                $data1['weight'] = is_null($value->weight) ? 0 : $value->weight;
                $data1['weight_type'] = is_null($value->weight_type) ? 0 : $value->weight_type;
                $data1['no_of_vehicle'] = is_null($value->total_vehicle) ? 0 : $value->total_vehicle;
                $data1['info'] = is_null($value->info) ? '' : $value->info;
                $data1['truck_name'] = is_null($value->truck_name) ? '' : $value->truck_name;
                $data1['truck_img'] = is_null($value->truck_img) ? '' : $value->truck_img;
                $data1['amount'] = is_null($value->amount) ? '' : $value->amount;
                $data1['base_fare'] = is_null($value->amount) ? '0' : $value->amount;
                $data1['tax_per'] = is_null($value->tax_per) ? '0' : $value->tax_per;
                $data1['tax_amount'] = is_null($value->tax_amount) ? '0' : $value->tax_amount;
                $data1['discount_per'] = is_null($value->discount_per) ? '0' : $value->discount_per;
                $data1['discount_amount'] = is_null($value->discount_amount) ? '0' : $value->discount_amount;

                $get_kmiou_charges = Payment_info::where('shipment_id', $value->id)->first();

                if ($get_kmiou_charges != null) {

                    $data1['kmiou_charges_per'] = is_null($get_kmiou_charges->percent) ? '0 %' : $get_kmiou_charges->percent . '(%)';
                    $data1['kmiou_charges_amount'] = is_null($get_kmiou_charges->admin_portion) ? '0 DA' : $get_kmiou_charges->admin_portion . ' DA';

                    $base_fare = ($value->amount - $get_kmiou_charges->admin_portion);
                    $data1['base_fare'] = ($base_fare == null || $base_fare == '' || $base_fare == '0') ? '' : $base_fare;

                } else {

                    $data1['kmiou_charges_per'] = '0 %';
                    $data1['kmiou_charges_amount'] = '0 DA';
                }

                $data1['total_amount'] = $total_amount;
                $data1['document'] = is_null($value->document) ? '' : $value->document;
                $data1['person_name'] = is_null($value->person_name) ? '' : $value->person_name;
                $data1['id_proof_image'] = is_null($value->id_proof_image) ? '' : $value->id_proof_image;
                $data1['signature_image'] = is_null($value->signature_image) ? '' : $value->signature_image;
                $data1['status'] = is_null($value->status) ? '' : $value->status;
                $data1['bid_status'] = is_null($value->bid_status) ? '' : $value->bid_status;
                $data1['payment_type'] = is_null($value->payment_type) ? 0 : $value->payment_type;
                $data1['payment_status'] = is_null($value->payment_status) ? '' : $value->payment_status;
                $data1['created_at'] = Helper::convertTimestampWithTimezone($value->created_at, 'Y-m-d H:i:s', $this->timezone);

                array_push($details, $data1);
            }
        } else {

            return redirect()->route('shipmentApprovedList');
        }

        $doc = array();

        if ($select_shipment && $select_shipment[0]->document != '' && $select_shipment[0]->document != null) {

            $str = $select_shipment[0]->document;

            $doc = explode("#####", $str);
        }

        $bid = array();

        $bid = DB::select('Select shipment_bid.*,users.first_name as user_first_name,users.last_name as user_last_name,users.profile_pic as user_profile_pic,users.total_rate_count as total_rate_count,users.avg_rating as avg_rating from shipment_bid left join users on users.id=shipment_bid.user_id where shipment_id = ' . $id . ' ');

        return view('admin.shipment.past_shipment_details', compact('details', 'doc', 'bid'));

    }

    public function track_shipment(Request $request)
    {

        try {
            $this->timezone = Auth::guard('admin')->user()->timezone;

            $user_id = Auth::guard('admin')->user()->id;

            $shipment = Shipment::find($request->shipment_id);

            $response = array();

            if ($shipment != null && $shipment->status != "3" && $shipment->status != "7") {

                $track = Track_shipment::where('shipment_id', $request->shipment_id)->get();

                $count = 1;

                if ($track != null && $track != '[]') {

                    foreach ($track as $key => $value) {

                        $data1['step'] = $count;
                        $data1['date'] = Helper::convertTimestampWithTimezone($value->created_at, 'Y-m-d H:i', $this->timezone);
                        $data1['status'] = '1';

                        array_push($response, $data1);

                        $count++;
                    }

                }

                if ($count < 8) {

                    for ($i = $count; $i < 8; $i++) {

                        $data1['step'] = $i;
                        $data1['date'] = '';
                        $data1['status'] = '0';

                        array_push($response, $data1);
                    }

                }

                return json_encode(['success' => 1, 'msg' => 'Success', 'result' => $response]);

            } else {
                if ($shipment == null) {

                    $msg = trans('Shipment not Found');

                } else if ($shipment->status == "3") {

                    $msg = trans('This Shipment Cancelled By Driver');

                } else if ($shipment->status == "7") {

                    $msg = trans('Your Shipment Driver Reported Emergency');
                }

                session()->flash('alert-warning', $msg);

                return json_encode(['success' => 0, 'msg' => $msg, 'result' => []]);
            }

        } catch (Exception $ex) {

            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage()]);
        }
    }

    public function transporter_list(Request $request)
    {

        try {

            $user_id = Auth::guard('admin')->user()->id;

            $query = '';

            if ($request->search_string != null && $request->search_string != '') {

                $query .= '  AND ( (LOWER(CAST(users.first_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%' . $request->search_string . '%")  OR (LOWER(CAST(users.last_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%' . $request->search_string . '%") )';
            }

            $get_transporter = DB::select('select * from users where user_type = "3" AND is_verify = "1" AND status = "1" AND approve = "1" ' . $query . ' order by created_at desc ');

            $transporter_list = array();

            if ($get_transporter != null) {

                foreach ($get_transporter as $key => $value) {

                    $data1['user_id'] = $value->id;
                    $data1['user_name'] = ($value->first_name == null) ? '' : $value->first_name . ' ' . (($value->last_name == null) ? '' : $value->last_name);
                    $data1['profile_pic'] = ($value->profile_pic == null) ? '' : $value->profile_pic;
                    $data1['email'] = ($value->email == null) ? '' : $value->email;
                    $data1['mobile'] = ($value->mobile_no == null) ? '' : $value->mobile_no;

                    array_push($transporter_list, $data1);
                }
            }

            return json_encode(['success' => 1, 'msg' => 'Success', 'result' => $transporter_list]);

        } catch (Exception $ex) {

            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage()]);
        }
    }

    public function driver_list(Request $request)
    {

        try {

            $user_id = Auth::guard('admin')->user()->id;

            $query = '';

            if ($request->search_string != null && $request->search_string != '') {

                $query .= '  AND ( (LOWER(CAST(users.first_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%' . $request->search_string . '%")  OR (LOWER(CAST(users.last_name AS CHAR(1000000) CHARACTER SET utf8)) LIKE "%' . $request->search_string . '%") )';
            }

            $check_driver = DB::select('select driver.*,users.first_name as first_name,users.last_name as last_name
             from driver
            left join users on users.id = driver.driver_id
            where driver.transporter_id=' . $request->transporter_id . ' AND driver.status = "1" ' . $query . ' order by driver.created_at desc ');

            $response = array();

            if ($check_driver != null) {

                foreach ($check_driver as $key => $value) {

                    $data1 = array();

                    $select_user = User::find($value->driver_id);

                    if ($select_user != null) {

                        $data1['user_id'] = $select_user->id;
                        $data1['user_name'] = ($select_user->first_name == null) ? '' : $select_user->first_name . ' ' . (($select_user->last_name == null) ? '' : $select_user->last_name);
                        $data1['profile_pic'] = ($select_user->profile_pic == null) ? '' : $select_user->profile_pic;
                        $data1['email'] = ($select_user->email == null) ? '' : $select_user->email;
                        $data1['mobile'] = ($select_user->mobile_no == null) ? '' : $select_user->mobile_no;

                        array_push($response, $data1);
                    }
                }
            }

            return json_encode(['success' => 1, 'msg' => 'Success', 'result' => $response]);

        } catch (Exception $ex) {

            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage()]);
        }
    }

    public function assign_transporter(Request $request)
    {
        try {

            $user_id = Auth::guard('admin')->user()->id;

            $shipment = Shipment::find($request->shipment_id);

            if ($shipment != null && $shipment->status == '0') {

                if ($shipment->transporter_id == '0') {

                    $shipment->transporter_id = $request->transporter_id;

                    $shipment->save();

                    $get_truck_no = Shipment_info::where('shipment_id', $request->shipment_id)->first();

                    // send notification
                    Helper::send_push_notification($user_id, $request->transporter_id, 'Assign Transporter', 'assign you shipment Truck No. ' . $get_truck_no->no_of_vehicle . ' Order No. #' . $shipment->unique_id, '12', $shipment->id);

                }

                return json_encode(['success' => 1, 'msg' => 'Transporter Assigned Successfully', 'result' => []]);

            } else {

                $msg = 'Shipment Not Found';
                return json_encode(['success' => 0, 'msg' => $msg, 'result' => []]);
            }

        } catch (Exception $ex) {

            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage()]);
        }
    }

    public function assign_driver(Request $request)
    {
        try {

            $user_id = Auth::guard('admin')->user()->id;

            $shipment = Shipment::find($request->shipment_id);

            if ($shipment != null && $shipment->status == '0') {

                if ($shipment->driver_id == '0') {

                    $shipment->driver_id = $request->driver_id;

                    $shipment->save();

                    $get_truck_no = Shipment_info::where('shipment_id', $request->shipment_id)->first();

                    // send notification
                    Helper::send_push_notification($user_id, $request->driver_id, 'Assign Driver', 'assign you shipment Truck No. ' . $get_truck_no->no_of_vehicle . ' Order No. #' . $shipment->unique_id, '4', $shipment->id);

                }

                return json_encode(['success' => 1, 'msg' => 'Driver Assigned Successfully', 'result' => []]);

            } else {

                $msg = 'Shipment Not Found';
                return json_encode(['success' => 0, 'msg' => $msg, 'result' => []]);
            }

        } catch (Exception $ex) {

            Helper::logError($_POST, __FILE__, __LINE__, $ex->getLine(), $ex->getMessage());
            return json_encode(['success' => 0, 'msg' => trans('word.Technical Issue'), 'result' => $ex->getMessage()]);
        }
    }

    public function coupon_list()
    {
        $data = DB::select('select coupon.*
              from coupon
              where coupon.status != "2" order by created_at desc ');

        return view('admin.coupon.coupon_list', compact('data'));

    }

    public function show_add_new_coupon()
    {
        return view('admin.coupon.new_coupon');
    }

    public function add_new_coupon(Request $request)
    {

        /* add new coupon */

        $coupon = new Coupon;
        $coupon->coupon_code = $request->coupon_code;
        $coupon->title = $request->title;
        $coupon->discount = $request->discount;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->status = '0';
        $coupon->save();

        session()->flash('alert-success', 'New Coupon Added Successfully');
        return redirect("admin/coupon/list");
    }

    public function show_edit_coupon($id)
    {
        $coupon = Coupon::find($id);

        return view('admin.coupon.edit_coupon', compact('coupon'));
    }

    public function update_coupon(Request $request)
    {

        /* update new coupon */

        $coupon = Coupon::find($request->coupon_id);
        $coupon->coupon_code = $request->coupon_code;
        $coupon->title = $request->title;
        $coupon->discount = $request->discount;
        $coupon->start_date = $request->start_date;
        $coupon->end_date = $request->end_date;
        $coupon->save();

        session()->flash('alert-success', 'Coupon Updated Successfully');
        return redirect("admin/coupon/list");
    }

    public function remove_coupon(Request $request)
    {

        /* remove coupon */

        $coupon = Coupon::find($request->coupon_id);
        $coupon->status = '2';
        $coupon->save();

        session()->flash('alert-success', 'Coupon Removed Successfully');
        return json_encode(['success' => 1, 'msg' => 'Success', 'result' => []]);
    }

    public function change_coupon_status(Request $request)
    {

        $check_coupon = Coupon::find($request->coupon_id);

        if ($check_coupon != null) {

            $check_coupon->status = $request->status;

            $check_coupon->save();

            return json_encode(['success' => 1, 'msg' => 'Coupon Status Changed', 'result' => '[]']);
        }
    }

    /*truck functions start*/

    public function truck_list()
    {
        $data = DB::select('select truck.*
              from truck
              where truck.status != "2" order by created_at desc ');
        // $data = DB::select('select truck.* from truck  LEFT JOIN truck_capacity as truckc ON truckc.truck_id = truck.id  where truck.status != "2" order by created_at desc');

        foreach ($data as $key => $value) {
            $truckid = $value->id;
            $get_capacity = DB::select('select * from truck_capacity where truck_id= ' . $truckid . '');

            $truckcapacity = '';
            if ($get_capacity != []) {

                foreach ($get_capacity as $gkey => $gvalue) {
                    if ($gkey != '0') {
                        $truckcapacity .= ', ';
                    }
                    $truckcapacity .= $gvalue->truck_capacity . ' ' . (($gvalue->weight_type == '0') ? 'Kg' : 'Ton');
                }

                $value->truck_cap_str = $truckcapacity;

            } else {
                $value->truck_cap_str = $value->capacity . ' ' . (($value->weight_type == '0') ? 'Kg' : 'Ton');
            }
        }

        return view('admin.truck.truck_list', compact('data'));

    }

    public function show_add_new_truck()
    {
        return view('admin.truck.new_truck');
    }

    public function add_new_truck(Request $request)
    {

        /* add new truck */
        //  dd($request);
        $truck_img_url = null;

        if ($request->hasFile('truck_img')) {

            $validator = Validator::make($request->all(), [
                'truck_img' => 'required',
                'truck_img.*.file' => 'image|mimes:jpg,jpeg,png',
            ]);

            /*foreach ($request->file('truck_img') as $key => $request_doc) {  */

            if ($validator->fails()) {
                return back()->with('alert-warning', 'Only image files allowed !!');
            } else {
                $pro_pic_url = null;
                $pro_pic = $request->file('truck_img');
                $name = time() . '.' . $pro_pic->getClientOriginalExtension();

                $destinationPath = public_path('images/doc');
                $pro_pic->move($destinationPath, $name);

                $pro_pic_url = asset('public/images/doc') . '/' . $name;

                /*if($key == '0'){*/
                $truck_img_url .= $pro_pic_url;
                /*}else{
                $truck_img_url .= '#####'.$pro_pic_url;
                }*/

                //}
            }
        }

        $truck = new Truck;

        // app()->setLocale('ar');
        // $msg_en = trans('requesttruck_name');
        // $truck->truck_name = $msg_en;
        // dd($msg_en);

        // app()->setLocale('fr');
        // $msg_fr = $request->truck_name;
        // $truck->truck_name_fr = $msg_fr;

        // app()->setLocale('ar');
        // $msg_ar = $request->truck_name;
        //  $truck->truck_name_ar = $msg_ar;

        $truck->truck_name = $request->truck_name;
        $truck->truck_name_fr = $request->truck_name_fr;
        $truck->truck_name_ar = $request->truck_name_ar;

        // $truck->capacity = $truck_capacity->id;
        $truck->truck_img = $truck_img_url;
        $truck->truck_desc = $request->truck_desc;
        // $truck->weight_type = $request->weight_type;
        $truck->status = '1';
        $truck->save();

        $to_do_list_arr = array();

        if (isset($request->capacity) && $request->capacity[0] != null) {
            foreach ($request->capacity as $key => $value) {

                $truck_capacity = new Truck_capacity;
                $truck_capacity->truck_id = $truck->id;
                $truck_capacity->truck_capacity = $value;
                $truck_capacity->weight_type = $request->weight_type[$key];
                $truck_capacity->save();

            }
        }

        // $truck_capacity->truck_id = $truck_id;
        // $to_do_list_str = null;
        // if($to_do_list_arr != []){
        //     $to_do_list_str = json_encode($to_do_list_arr);
        // }

        session()->flash('alert-success', 'New Truck Added Successfully');
        return redirect("admin/truck/list");
    }

    public function show_edit_truck($id)
    {
        $data = Truck::find($id);

        $doc = array();

        if ($data && $data->truck_img != '' && $data->truck_img != null) {

            $str = $data->truck_img;

            $doc = explode("#####", $str);
        }

        $truck_capacity = DB::select('select * from truck_capacity where truck_id= ' . $id . '');

        return view('admin.truck.edit_truck', compact('data', 'doc', 'truck_capacity'));
    }

    public function update_truck(Request $request)
    {

        /* update new truck */

        // dd($request);

        $truck = Truck::find($request->truck_id);

        $truck_img_url = $truck->truck_img;

        if ($request->hasFile('truck_img')) {

            $validator = Validator::make($request->all(), [
                'truck_img' => 'required',
                'truck_img.*.file' => 'image|mimes:jpg,jpeg,png',
            ]);

            /*
            foreach ($request->file('truck_img') as $key => $request_doc) {  */
            if ($validator->fails()) {
                return back()->with('alert-warning', 'Only image files allowed !!');
            } else {
                $pro_pic_url = null;
                $pro_pic = $request->file('truck_img');
                $name = time() . '.' . $pro_pic->getClientOriginalExtension();

                $destinationPath = public_path('images/doc');
                $pro_pic->move($destinationPath, $name);

                $pro_pic_url = asset('public/images/doc') . '/' . $name;

                /*if($truck->truck_img == null || $truck->truck_img == ''){*/
                $truck_img_url = $pro_pic_url;
                /*}else{
                $truck_img_url .= '#####'.$pro_pic_url;
                }*/

                //}
            }
        }

        $truck->truck_name = $request->truck_name;
        $truck->truck_name_fr = $request->truck_name_fr;
        $truck->truck_name_ar = $request->truck_name_ar;
        // $truck->capacity = $request->capacity;
        $truck->truck_img = $truck_img_url;
        $truck->truck_desc = $request->truck_desc;
        // $truck->weight_type = $request->weight_type;

        if (isset($request->truck_capacity) && $request->truck_capacity[0] != null) {
            DB::table('truck_capacity')->where('truck_id', $request->truck_id)->delete();
            foreach ($request->truck_capacity as $key => $value) {

                $truck_capacity = new Truck_capacity;
                $truck_capacity->truck_id = $truck->id;
                $truck_capacity->truck_capacity = $value;
                $truck_capacity->weight_type = $request->weight_type[$key];
                $truck_capacity->save();

            }

        }
        $truck->save();

        session()->flash('alert-success', 'Truck Updated Successfully');
        return redirect("admin/truck/list");
    }

    public function remove_truck(Request $request)
    {

        /* remove truck */

        $truck = Truck::find($request->truck_id);
        $truck->status = '2';
        $truck->save();

        session()->flash('alert-success', 'Truck Removed Successfully');
        return json_encode(['success' => 1, 'msg' => 'Success', 'result' => []]);
    }

    public function change_truck_status(Request $request)
    {

        $check_truck = Truck::find($request->truck_id);

        if ($check_truck != null) {

            $check_truck->status = $request->status;

            $check_truck->save();

            return json_encode(['success' => 1, 'msg' => 'Truck Status Changed', 'result' => '[]']);
        }
    }

    /*truck functions end*/

    /*goods type functions start*/

    public function goods_type_list()
    {
        $data = DB::select('select goods_type.*
              from goods_type
              where goods_type.status != "2" order by created_at desc ');

        return view('admin.goods_type.goods_type_list', compact('data'));
    }

    public function show_add_new_goods_type()
    {
        return view('admin.goods_type.new_goods_type');
    }

    public function add_new_goods_type(Request $request)
    {
        $goods_type = new Goods_type;
        $goods_type->goods_type_name = $request->goods_type_name;
        $goods_type->status = '1';
        $goods_type->save();

        session()->flash('alert-success', 'New Goods Type Added Successfully');
        return redirect("admin/goods_type/list");
    }

    public function show_edit_goods_type($id)
    {
        $data = Goods_type::find($id);

        return view('admin.goods_type.edit_goods_type', compact('data'));
    }

    public function update_goods_type(Request $request)
    {

        /* update new goods_type */

        $goods_type = Goods_type::find($request->truck_id);

        $goods_type->goods_type_name = $request->goods_type_name;

        $goods_type->save();

        session()->flash('alert-success', 'Goods Type Updated Successfully');
        return redirect("admin/goods_type/list");
    }

    public function remove_goods_type(Request $request)
    {

        /* remove goods_type */

        $goods_type = Goods_type::find($request->truck_id);
        $goods_type->status = '2';
        $goods_type->save();

        session()->flash('alert-success', 'Goods Type Removed Successfully');
        return json_encode(['success' => 1, 'msg' => 'Success', 'result' => []]);
    }

    public function change_goods_type_status(Request $request)
    {

        $goods_type = Goods_type::find($request->truck_id);

        if ($goods_type != null) {

            $goods_type->status = $request->status;

            $goods_type->save();

            return json_encode(['success' => 1, 'msg' => 'Goods Type Status Changed', 'result' => '[]']);
        }
    }

    /*goods type functions end*/

    /*Surge Price functions start*/

    public function surge_price_list()
    {
        $data = DB::select('select surge_price.*
              from surge_price
              where surge_price.status != "2" order by created_at desc ');

        return view('admin.surge_price.surge_price_list', compact('data'));
    }

    public function show_add_new_surge_price()
    {
        return view('admin.surge_price.new_surge_price');
    }

    public function add_new_surge_price(Request $request)
    {
        $surge_price = new Surge_price;
        $surge_price->total_diff_hours = $request->total_diff_hours;
        $surge_price->price_per_hour = $request->price_per_hour;
        $surge_price->type = $request->type;
        $surge_price->status = '1';
        $surge_price->save();

        session()->flash('alert-success', 'New Surge Price Added Successfully');
        return redirect("admin/surge_price/list");
    }

    public function show_edit_surge_price($id)
    {
        $data = Surge_price::find($id);

        return view('admin.surge_price.edit_surge_price', compact('data'));
    }

    public function update_surge_price(Request $request)
    {

        /* update new surge_price */

        $surge_price = Surge_price::find($request->price_id);

        $surge_price->total_diff_hours = $request->total_diff_hours;
        $surge_price->price_per_hour = $request->price_per_hour;
        $surge_price->type = $request->type;

        $surge_price->save();

        session()->flash('alert-success', 'Surge Price Updated Successfully');
        return redirect("admin/surge_price/list");
    }

    public function remove_surge_price(Request $request)
    {

        /* remove surge_price */

        $surge_price = Surge_price::find($request->price_id);
        $surge_price->status = '2';
        $surge_price->save();

        session()->flash('alert-success', 'Surge_price Removed Successfully');
        return json_encode(['success' => 1, 'msg' => 'Success', 'result' => []]);
    }

    public function change_surge_price_status(Request $request)
    {

        $surge_price = Surge_price::find($request->price_id);

        if ($surge_price != null) {

            $surge_price->status = $request->status;

            $surge_price->save();

            return json_encode(['success' => 1, 'msg' => 'Surge Price Status Changed', 'result' => '[]']);
        }
    }

    /*surge price functions end*/

    /*commission functions start*/

    public function commission_list()
    {
        $data = DB::select('select commission.*,users.first_name,users.last_name,users.email,users.mobile_no
              from commission left join users on users.id=commission.user_id
              where commission.status != "2" order by created_at desc ');

        return view('admin.commission.commission_list', compact('data'));
    }

    public function show_add_new_commission(Request $request)
    {
        $type = is_null($request->type) ? '0' : $request->type;
        if ($type == '0') {

            $user = User::where('status', '1')->where('user_type', '3')->where('is_commission', '0')->orderBy('created_at', 'desc')->get();

        } else if ($type == '1') {

            $user = User::where('status', '1')->where('user_type', '4')->where('is_commission', '0')->where('ref_id', '0')->orderBy('created_at', 'desc')->get();

        } else if ($type == '2') {

            $user = User::where('status', '1')->where('user_type', '2')->where('is_commission', '0')->orderBy('created_at', 'desc')->get();
        }

        return view('admin.commission.new_commission', compact('user', 'type'));
    }

    public function add_new_commission(Request $request)
    {
        if ($request->user_id[0] != null) {

            foreach ($request->user_id as $key => $user_id) {

                $user = User::find($user_id);
                $user->is_commission = '1';
                $user->commission_percent = $request->admin_percent;
                $user->save();

                if ($user->user_type == '2') {
                    $type = '0';

                } else if ($user->user_type == '3') {
                    $type = '1';
                } else {
                    $type = '2';
                }

                $commission = new Commission;
                $commission->user_id = $user_id;
                $commission->admin_percent = $request->admin_percent;
                $commission->type = $type;
                $commission->status = '1';
                $commission->save();

            }
            session()->flash('alert-success', 'New Commission Percentage Added Successfully');
            return redirect("admin/commission/list");

        } else {
            return back()->with('alert-warning', 'Please Select User First');
        }
    }

    public function show_edit_commission($id)
    {
        $data = Commission::find($id);
        $user = User::find($data->user_id);

        return view('admin.commission.edit_commission', compact('data', 'user'));
    }

    public function update_commission(Request $request)
    {

        /* update new Commission */

        $commission = Commission::find($request->commission_id);

        $commission->admin_percent = $request->admin_percent;
        $commission->save();

        $user = User::find($commission->user_id);
        $user->is_commission = '1';
        $user->commission_percent = $request->admin_percent;
        $user->save();

        session()->flash('alert-success', 'Commission Updated Successfully');
        return redirect("admin/commission/list");
    }

    public function remove_commission(Request $request)
    {

        /* remove Commission */

        $Commission = Commission::find($request->commission_id);
        $Commission->status = '2';
        $Commission->save();

        $user = User::find($Commission->user_id);
        $user->is_commission = '0';
        $user->commission_percent = '0';
        $user->save();

        session()->flash('alert-success', 'Commission Removed Successfully');
        return json_encode(['success' => 1, 'msg' => 'Success', 'result' => []]);
    }

    /*public function change_commission_status(Request $request)
    {

    $Commission = Commission::find($request->commission_id);

    if($Commission != null){

    $Commission->status = $request->status;

    $Commission->save();

    return json_encode(['success' => 1, 'msg' => 'Commission Status Changed','result' => '[]']);
    }
    }*/

    /*commission price functions end*/

    /*Surge Price Reqeust List functions start*/

    public function shipment_surge_price_list()
    {
        $data = DB::select('select shipment_surge_price.*,users.first_name as shipper_first_name,users.last_name as shipper_last_name
              from shipment_surge_price
              left join shipment on shipment.id = shipment_surge_price.shipment_id
              left join users on users.id = shipment.user_id order by shipment_surge_price.created_at desc ');

        return view('admin.shipment_surge_price.shipment_surge_price_list', compact('data'));
    }

    public function change_shipment_surge_price_status(Request $request)
    {

        $this->timezone = Auth::guard('admin')->user()->timezone;

        $user_id = Auth::guard('admin')->user()->id;

        $shipment_surge_price = Shipment_surge_price::find($request->surge_price_id);

        if ($shipment_surge_price != null) {

            $shipment = Shipment::find($shipment_surge_price->shipment_id);

            $shipment_surge_price->status = $request->status;

            $main_msg = '';

            if ($request->status == '2') {

                $msg = 'Shipment Surge Price Request Rejected';
                $main_msg = 'Rejected';

                $shipment_surge_price->reject_comment = $request->reject_comment;

                $get_truck_no = Shipment_info::where('shipment_id', $shipment->id)->first();

                //send notification
                Helper::send_push_notification($user_id, $shipment->driver_id, 'Detention Request Rejected', ' Rejected Your Detention Request Order Truck No. ' . $get_truck_no->no_of_vehicle . ' Order No. #' . $shipment->unique_id, '15', $shipment->id);

            } else {

                $msg = 'Shipment Surge Price Request Accepted';
                $main_msg = 'Accepted';

                $get_truck_no = Shipment_info::where('shipment_id', $shipment->id)->first();

                //send notification
                Helper::send_push_notification($user_id, $shipment->driver_id, 'Detention Request Accepted', ' Accepted Your Detention Request Order Truck No. ' . $get_truck_no->no_of_vehicle . ' Order No. #' . $shipment->unique_id, '14', $shipment->id);
            }

            $user = User::find($shipment->driver_id);

            // send mail
            $user_detail = array();
            $user_detail['name'] = is_null($user->first_name) ? '' : $user->first_name . ' ' . (is_null($user->last_name) ? '' : $user->last_name);
            $user_detail['emails'] = $user->email;
            $user_detail['message'] = 'Admin ' . $main_msg . ' Your Detention Request Order No. #' . $shipment->id;

            Mail::send('emails.detention_info', ['user' => (object) $user_detail, 'send_request' => (object) $shipment_surge_price], function ($message) use ($user) {
                $message->from(env('MAIL_USERNAME'), 'KMIOU');
                $message->to($user->email);
                $message->subject('KMIOU Surge Price Info');
            });

            $shipment_surge_price->save();

            session()->flash('alert-success', $msg);

            return json_encode(['success' => 1, 'msg' => $msg, 'result' => '[]']);

        }
    }

    public function support_number_list()
    {

        $data = DB::select('select * from support_numbers');
        // $data= DB::table('support_numbers')->get();

        // dd($data);
        // $shipper_transporter  = $request->input('shipper_transporter');
        // $driver = $request->input('driver');
        // $data = DB::update('update support_numbers set shipper_transporter = ?,
        // driver = ? where id = ?',[$shipper_transporter,$driver]);
        return view('admin/support_numberlist', compact('data'));

        // return redirect()->route('supportnumberlist');
    }

    /*Support Number*/
    public function update_support_number(Request $request)
    {

        // dd($request);
        $id = $request->input('id');
        $shipper_transporter = $request->input('shipper_transporter');
        $driver = $request->input('driver');
        $data = DB::update('update support_numbers set shipper_transporter = ?,
      driver = ? where id = ?', [$shipper_transporter, $driver, $id]);

        // return redirect()->route('updatesupportnumber')->with('message','Support number update Successfully!');
        // return view('admin/support_numberlist',compact('data'));
        return redirect()->back();
    }
    /*Surge Price Reqeust List functions end*/

    private $styleArrayborder = [
        'borders' => [
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['argb' => '#000000'],

            ],
        ], 'down' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
            'color' => ['argb' => '#000000'],

        ],
    ];

    private $styleArrayfont = [
        'font' => [
            'name' => 'Helvetica Neue',
            'size' => '10',
        ],
    ];

    private $styleArrayalign = ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, 'textRotation' => 0, 'wrapText' => true];

    public function download_excel(Request $request)
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->createSheet(0); //Setting index when creating
        $sheet->setTitle('Track Shipment Details'); //Setting index when creating

        $sheet->getStyle('A1:A9')->getFont()->setBold(true);
        $sheet->getStyle('B1')->getFont()->setBold(true);

        $sheet->SetCellValue('A1', 'TITLE');
        $sheet->SetCellValue('A2', 'Shipment ID');
        $sheet->SetCellValue('A3', 'Ordered');
        $sheet->SetCellValue('A4', 'Accepted');
        $sheet->SetCellValue('A5', 'Arrived At Pickup Location');
        $sheet->SetCellValue('A6', 'Start Shipment');
        $sheet->SetCellValue('A7', 'Truck On The Way');
        $sheet->SetCellValue('A8', 'Arrived At Drop Off Location');
        $sheet->SetCellValue('A9', 'Delivered');

        $sheet->SetCellValue('B1', 'VALUE');
        $sheet->SetCellValue('B2', $request->shipment_id);
        $sheet->SetCellValue('B3', $request->orderd);
        $sheet->SetCellValue('B4', $request->accepted);
        $sheet->SetCellValue('B5', $request->arrived_at_pickup);
        $sheet->SetCellValue('B6', $request->start_shipment);
        $sheet->SetCellValue('B7', $request->on_the_way);
        $sheet->SetCellValue('B8', $request->arrived_at_drop);
        $sheet->SetCellValue('B9', $request->delivered);

        $sheet->getStyle('A1:A9')->getAlignment()->applyFromArray($this->styleArrayalign);
        $sheet->getStyle('B1:B9')->getAlignment()->applyFromArray($this->styleArrayalign);
        $sheet->getStyle('A1:B1')->applyFromArray($this->styleArrayborder);
        $sheet->getStyle('A1:A9')->applyFromArray($this->styleArrayborder);
        $sheet->getStyle('B1:B9')->applyFromArray($this->styleArrayborder);
        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(40);

        //genrate xlsx
        $file = base_path() . '/public/track_excel/track_report_' . $request->shipment_id . '.xlsx';
        $link = url('/public/track_excel/track_report_' . $request->shipment_id . '.xlsx');

        $writer = new Xlsx($spreadsheet);
        $writer->save($file);

        return json_encode(['success' => 1, 'msg' => 'Succss', 'link' => $link, 'result' => '[]']);

        /*if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
    }*/
    }

// end controller function
}
