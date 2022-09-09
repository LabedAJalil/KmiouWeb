<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register', 'api\ApiUserController@register');

Route::post('login', 'api\ApiUserController@login');

Route::post('forgot_password', 'api\ApiUserController@forgot_password');

Route::post('activateuser', 'api\ApiUserController@activateuser');

Route::post('resendcode', 'api\ApiUserController@resendcode');


Route::post('logout', 'api\ApiUserController@logout');

//Route::get('send_test_mail', 'api\ApiUserController@send_test_mail');


Route::group(['middleware' => 'localization'], function() {
	
	Route::post('truck_type_list', 'api\ApiUserController@truck_type_list');
	
	Route::post('update_position', 'api\ApiUserController@update_position');

	Route::post('refresh_token', 'api\ApiUserController@refresh_token');

	Route::post('change_password', 'api\ApiUserController@change_password');
	
	Route::post('get_profile', 'api\ApiUserController@get_profile');
	
	Route::post('edit_profile', 'api\ApiUserController@edit_profile');
	
	Route::post('get_setting', 'api\ApiUserController@get_setting');
	
	Route::post('set_setting', 'api\ApiUserController@set_setting');
	
	Route::post('notification_list', 'api\ApiUserController@notification_list');
	
	Route::post('delete_notification', 'api\ApiUserController@delete_notification');
	
	Route::post('book_new_shipment', 'api\ApiShipmentController@book_new_shipment');
	
	Route::post('apply_coupon', 'api\ApiShipmentController@apply_coupon');
	
	Route::post('pay_shipment', 'api\ApiShipmentController@pay_shipment');
	
	Route::post('goods_type_list', 'api\ApiUserController@goods_type_list');
	

	Route::post('driver_dashboard', 'api\ApiUserController@driver_dashboard');
	
	// send surge price request
	Route::post('send_detention_request', 'api\ApiDriverController@send_detention_request');
	
	Route::post('shipment_detention_info', 'api\ApiDriverController@shipment_detention_info');
	
	Route::post('shipment_detaintion_details', 'api\ApiDriverController@shipment_detaintion_details');
	
	/*---------- for driver and carrier (transporter) ----------- */
	
	

	/*------------ start shipment request api  -------------*/
	
	Route::post('shipment_request_list', 'api\ApiShipmentController@shipment_request_list');
	
	Route::post('shipment_request_details', 'api\ApiShipmentController@shipment_request_details');
	

	Route::post('shipment_list', 'api\ApiShipmentController@shipment_list');
	
	Route::post('shipment_details', 'api\ApiShipmentController@shipment_details');
	
	
	Route::post('assign_driver', 'api\ApiShipmentController@assign_driver');
	
	Route::post('place_new_bid', 'api\ApiShipmentController@place_new_bid');
	
	Route::post('edit_delete_bid', 'api\ApiShipmentController@edit_delete_bid');
	
	Route::post('accept_reject_shipment_request', 'api\ApiShipmentController@accept_reject_shipment_request');
	
	/*------------ end shipment request api  -------------*/
	
	
	// update shipment status
	Route::post('update_shipment_status', 'api\ApiShipmentController@update_shipment_status');

	// cancel active shipment
	Route::post('cancel_shipment', 'api\ApiShipmentController@cancel_shipment');
	

	// report emergency
	Route::post('report_emergency', 'api\ApiShipmentController@report_emergency');
	

	/*------------ end active shipment api  -------------*/
	
	
	Route::post('update_online_status', 'api\ApiUserController@update_online_status');


	/*---- Driver join Transporter Routes ----*/
		
		Route::post('add_driver', 'api\ApiDriverController@add_driver');
		
		Route::post('driver_list', 'api\ApiDriverController@driver_list');
		
		Route::post('update_driver_doc', 'api\ApiDriverController@update_driver_doc');
		
		Route::post('delete_driver', 'api\ApiDriverController@delete_driver');
		
		Route::post('truck_list', 'api\ApiDriverController@truck_list');
		

	/*---- Driver join Transporter Routes ----*/


	/*---- Truck Transporter Routes ----*/
		
		Route::post('users_truck_list', 'api\ApiDriverController@users_truck_list');
		
		Route::post('users_truck_list_for_add', 'api\ApiDriverController@users_truck_list_for_add');
		
		Route::post('add_new_truck', 'api\ApiDriverController@add_new_truck');
		
		Route::post('change_truck_status', 'api\ApiDriverController@change_truck_status');
		
		Route::post('remove_added_truck', 'api\ApiDriverController@remove_added_truck');
		

	/*---- Truck Transporter Routes ----*/

	
	/*---------------------- for shipper ----------------------*/
	
		Route::post('select_bidder', 'api\ApiShipmentController@select_bidder');
		
		Route::post('bidder_list', 'api\ApiShipmentController@bidder_list');
		
		Route::post('rate_shipment', 'api\ApiShipmentController@rate_shipment');

		Route::post('track_shipment', 'api\ApiShipmentController@track_shipment');

	/* card apis*/

		Route::post('add_new_card', 'api\ApiUserController@add_new_card');
		
		Route::post('user_card_list', 'api\ApiUserController@user_card_list');
		
		Route::post('delete_card', 'api\ApiUserController@delete_card');
		

	/* card apis*/

	/*---------------------- for shipper ----------------------*/

});
