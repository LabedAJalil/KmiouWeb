<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*------ front end url ------*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('about', function () {
	return view('about_us');
});

Route::get('contact', function () {
	return view('contact_us');
});

Route::get('services', function () {
	return view('services');
});

Route::get('blog', function () {
	return view('blog');
});

Route::get('blog_details', function () {
	return view('blog_details');
});

Route::get('elements', function () {
	return view('elements');
});

Route::get('privacy_policy', function () {
	return view('privacy_policy');
});

Route::get('terms_cond', function () {
	return view('terms_cond');
});


/*------ users payment info url ------*/

Route::get('payment_info/list/{user_id?}/{language?}',['uses'=>'admin\ShipmentController@users_payment_info_list','as'=>'usersPaymentInfoList']);

Route::post('payment_info/list_filter',['uses' => 'admin\ShipmentController@users_payment_info_filter','as' =>'usersPaymentInfoFilter']);

/*------ users payment info url ------*/

/*------ front end url ------*/
// user login routes
Auth::routes();

	Route::post('check_email_exists', array('uses' => 'UserController@check_email_exists','as' => 'checkEmailExists'));
	
	Route::post('show_shipment_detaintion_info', array('uses' => 'UserController@show_shipment_detaintion_info','as' => 'showShipmentDetaintionInfo'));
	
	/*------------- forgot password  --------------*/

	Route::get('forgot_password', array('uses' => 'Auth\LoginController@show_forgot_password','as' => 'showForgotPassword'));
	Route::post('forgot_password', array('uses' => 'Auth\LoginController@forgot_password','as' => 'ForgotPassword'));

	/*------------- forgot password  --------------*/

	/*------------- reset new password  --------------*/

	Route::get('reset_password/{user_id}/{verification_code}', array('uses' => 'UserController@get_reset_password','as' => 'getResetPassword'));

	Route::post('reset_new_password', array('uses' => 'UserController@reset_new_password','as' => 'resetPassword'));
	

	/*------------- reset new password  --------------*/

	
	Route::get('{user_id}/verify_user', array('uses' => 'UserController@show_verify_user','as' => 'showVerifyUser'));

	Route::post('verify_user', array('uses' => 'UserController@verify_user','as' => 'verifyUser'));

	Route::get('{user_id}/resend_code', array('uses' => 'UserController@resend_code','as' => 'resendCode'));

	Route::post('delete_notification', array('uses' => 'UserController@delete_notification','as' => 'deleteNotification'));
// users login url

Route::group(['middleware' => 'user_guest'], function(){

	Route::get('login', array('uses' => 'Auth\LoginController@showLogin','as' => 'showLogin'));

	Route::post('login', array('uses' => 'Auth\LoginController@doLogin','as' => 'doLogin'));

	Route::get('register', array('uses' => 'UserController@show_register','as' => 'showRegister'));
	
	Route::post('register', array('uses' => 'UserController@register','as' => 'register'));
	
});
	

/*admin login url */

Route::group(['middleware' => 'admin_guest'], function() {

	Route::get('admin/login', array('uses' => 'Auth\AdminLoginController@showAdminLogin','as' => 'adminLogin'));
	Route::post('admin/login', array('uses' => 'Auth\AdminLoginController@doAdminLogin','as' => 'adminDoLogin'));


});

/*---------------------- start admin routes -------------------------*/

Route::group(array('prefix' => 'admin','middleware' => 'admin_auth'), function(){

	Route::get('dashboard', array('uses' => 'admin\UserController@adminDashboard','as' => 'adminDashboard'));
	
	Route::post('dashboard_filter', array('uses' => 'admin\UserController@admin_filter_dashboard','as' => 'adminFilterDashboard'));

	Route::get('logout', array('uses' => 'Auth\AdminLoginController@doAdminLogout','as' => 'doAdminLogout'));
	

	Route::get('edit_profile', function () {
    	return view('admin/profile');
	});

	Route::get('privacy_policy', function () {
    	return view('admin/privacy_policy');
	});
	
	Route::get('contact_support', function () {
    	return view('admin/contact_support');
	});

	// Route::get('support_number', function () {
    // 	return view('admin/support_number');
	// });

	Route::get('support_number/list',['uses'=>'admin\ShipmentController@support_number_list','as'=>'supportnumberlist']);
	Route::post('support_number/update',['uses'=>'admin\ShipmentController@update_support_number','as'=>'updatesupportnumber']);
	
	Route::get('terms_cond', function () {
    	return view('admin/terms_cond');
	});
	
	Route::get('help_feedback', function () {
    	return view('admin/help_feedback');
	});
		

	/*------------------ change password url -------*/
	
	Route::get('{user_id}/change_password', array('uses' => 'Auth\AdminLoginController@show_change_password','as' => 'showChangePassword'));
	
	Route::post('change_password', array('uses' => 'Auth\AdminLoginController@change_password','as' => 'changePassword'));


	/*------------------ change password url -------*/


	/*------------------ send notification url -------*/
	
	Route::get('notification/show_notification', array('uses' => 'admin\UserController@show_notification_from_admin','as' => 'showSendNotification'));
	
	Route::post('send_notification', array('uses' => 'admin\UserController@send_notification_from_admin','as' => 'sendNotification'));

	/*------------------ send notification url -------*/


	/* ------------------Avalilable user url ----------------*/

	Route::get('user/show_add_new',['uses'=>'admin\UserController@show_add_new_user','as'=>'showAddNewUser']);
	Route::get('user/list',['uses'=>'admin\UserController@list','as'=>'userList']);
	Route::post('user/save',['uses'=>'admin\UserController@save','as'=>'addNewUser']);
	Route::get('user/edit/{id}',['uses'=>'admin\UserController@editdata','as'=>'userEdit']);
	Route::post('user/update/',['uses'=>'admin\UserController@updatedata','as'=>'userUpdate']);
	Route::post('remove',['uses'=>'admin\UserController@remove','as'=>'removeUser']);

	/* ------------------ end Avalilable user url ----------------*/
	
	/* ------------------New user url ----------------*/

	Route::get('user/new_list',['uses'=>'admin\UserController@new_user_list','as'=>'newUserList']);

	/* ------------------ end New user url ----------------*/


	/* ------------------Avalilable transporter url ----------------*/

	Route::get('transporter/show_add_new',['uses'=>'admin\UserController@show_add_new_transporter','as'=>'showAddNewTransporter']);
	Route::get('transporter/user_list/{filter_type?}/{id?}',['uses'=>'admin\UserController@transporter_list','as'=>'transporterUserList']);
	Route::post('transporter/save',['uses'=>'admin\UserController@transporter_save','as'=>'addNewTransporter']);
	Route::get('transporter/edit/{id}',['uses'=>'admin\UserController@transporter_editdata','as'=>'transporterEdit']);
	Route::post('transporter/update/',['uses'=>'admin\UserController@transporter_updatedata','as'=>'transporterUpdate']);
	Route::post('transporter/change_approve_status', array('uses' => 'admin\UserController@change_approve_status','as' => 'changeApproveStatus'));
	Route::get('transporter/users_list/{filter_type?}/{id?}',['uses'=>'admin\UserController@driver_list','as'=>'driverUserList']);
	// Route::get('transporter/users_list/{id?}',['uses' => 'UserController@approve_status','as' => 'transporterApproveList']);

	/* ------------------ end Avalilable transporter url ----------------*/



	/* ------------------ shipment url ----------------*/

	Route::get('shipment/request/{filter_type?}',['uses'=>'admin\ShipmentController@request_list','as'=>'shipmentRequestList']);
	
	Route::get('shipment/quote_request/{filter_type?}',['uses'=>'admin\ShipmentController@quote_request_list','as'=>'shipmentQuoteRequestList']);

	Route::get('shipment/request/{id}/details', array('uses' => 'admin\ShipmentController@show_shipment_request_details','as' => 'showShipmentRequestDetails'));

	Route::get('request_shipment_details', array('uses' => 'admin\ShipmentController@show_request_shipment_details','as' => 'showRequestShipmentDetails'));
	

	Route::post('set_shipment_amount', array('uses' => 'admin\ShipmentController@set_shipment_amount','as' => 'setShipmentAmount'));



	// active shipments
	Route::get('shipment/approved/{filter_type?}',['uses'=>'admin\ShipmentController@ongoing_list','as'=>'shipmentApprovedList']);

	Route::get('shipment/active/{id}/details', array('uses' => 'admin\ShipmentController@show_active_shipment_details','as' => 'showActiveShipmentDetails'));
	

	//track shipment
		Route::post('track_shipment', array('uses' => 'admin\ShipmentController@track_shipment','as' => 'trackShipment'));

	// transporter list for assign shipment
		Route::post('transporter_list', array('uses' => 'admin\ShipmentController@transporter_list','as' => 'transporterList'));
		
	// assign transporter
		Route::post('assign_transporter', array('uses' => 'admin\ShipmentController@assign_transporter','as' => 'assignTransporter'));
	
	// driver list for assign shipment
		Route::post('driver_list', array('uses' => 'admin\ShipmentController@driver_list','as' => 'driverList'));
	
	// assign transporter
		Route::post('assign_driver', array('uses' => 'admin\ShipmentController@assign_driver','as' => 'assignDriver'));

	

	// cancel shipments
	Route::get('shipment/cancelled',['uses'=>'admin\ShipmentController@cancelled_list','as'=>'shipmentCancelledList']);
	
	Route::get('shipment/cancelled/{id}/details', array('uses' => 'admin\ShipmentController@show_cancelled_shipment_details','as' => 'showCancelledShipmentDetails'));
	

	// reported shipments
	Route::get('shipment/reported',['uses'=>'admin\ShipmentController@reported_list','as'=>'shipmentReportedList']);
	
	Route::get('shipment/reported/{id}/details', array('uses' => 'admin\ShipmentController@show_reported_shipment_details','as' => 'showReportedShipmentDetails'));
	
	
	// past shipments
	Route::get('shipment/completed',['uses'=>'admin\ShipmentController@completed_list','as'=>'shipmentCompletedList']);
	Route::post('completed_list_filter',['uses'=>'admin\ShipmentController@completed_list_filter','as'=>'shipmentCompletedListFilter']);

	Route::get('shipment/past/{id}/details', array('uses' => 'admin\ShipmentController@show_past_shipment_details','as' => 'showPastShipmentDetails'));

	Route::post('download_excel', array('uses' => 'admin\ShipmentController@download_excel','as' => 'adminDownloadExcelTrackShipment'));


	/* ------------------ end shipment url ----------------*/

	// payment info
	Route::get('payment_info/list',['uses'=>'admin\ShipmentController@payment_info_list','as'=>'paymentInfoList']);

	Route::post('payment_info/list_filter',['uses' => 'admin\ShipmentController@payment_info_filter','as' =>'paymentInfoFilter']);

	Route::get('payment_info/{id}/pdfview',['uses' => 'admin\TripController@pdfview','as' =>'paymentInfoPdfview']);


	// performance report
	Route::get('performance_report/list',['uses'=>'admin\ShipmentController@performance_report_list','as'=>'performanceReportList']);

	Route::post('performance_report/list_filter',['uses' => 'admin\ShipmentController@performance_report_filter','as' =>'performanceReportFilter']);


	// coupon (promo code)
	Route::get('coupon/list',['uses'=>'admin\ShipmentController@coupon_list','as'=>'couponList']);

	Route::get('coupon/add',['uses'=>'admin\ShipmentController@show_add_new_coupon','as'=>'showAddNewCoupon']);
	
	Route::post('coupon/add_new',['uses'=>'admin\ShipmentController@add_new_coupon','as'=>'addNewCoupon']);
	
	Route::get('coupon/{id}/edit',['uses'=>'admin\ShipmentController@show_edit_coupon','as'=>'showEditCoupon']);
	
	Route::post('coupon/update',['uses'=>'admin\ShipmentController@update_coupon','as'=>'updateCoupon']);
	
	Route::post('coupon/remove',['uses'=>'admin\ShipmentController@remove_coupon','as'=>'removeCoupon']);
	
	Route::post('change_coupon_status',['uses'=>'admin\ShipmentController@change_coupon_status','as'=>'changeCouponStatus']);



	// truck
	Route::get('truck/list',['uses'=>'admin\ShipmentController@truck_list','as'=>'truckList']);

	Route::get('truck/add',['uses'=>'admin\ShipmentController@show_add_new_truck','as'=>'showAddNewTruck']);
	
	Route::post('truck/add_new',['uses'=>'admin\ShipmentController@add_new_truck','as'=>'addNewTruck']);
	
	Route::get('truck/{id}/edit',['uses'=>'admin\ShipmentController@show_edit_truck','as'=>'showEditTruck']);
	
	Route::post('truck/update',['uses'=>'admin\ShipmentController@update_truck','as'=>'updateTruck']);
	
	Route::post('change_truck_status',['uses'=>'admin\ShipmentController@change_truck_status','as'=>'changeTruckStatus']);
	
	Route::post('truck/remove',['uses'=>'admin\ShipmentController@remove_truck','as'=>'removeTruck']);



	// goods type
	Route::get('goods_type/list',['uses'=>'admin\ShipmentController@goods_type_list','as'=>'goodsTypeList']);

	Route::get('goods_type/add',['uses'=>'admin\ShipmentController@show_add_new_goods_type','as'=>'showAddNewGoodsType']);
	
	Route::post('goods_type/add_new',['uses'=>'admin\ShipmentController@add_new_goods_type','as'=>'addNewGoodsType']);
	
	Route::get('goods_type/{id}/edit',['uses'=>'admin\ShipmentController@show_edit_goods_type','as'=>'showEditGoodsType']);
	
	Route::post('goods_type/update',['uses'=>'admin\ShipmentController@update_goods_type','as'=>'updateGoodsType']);
	
	Route::post('change_goods_type_status',['uses'=>'admin\ShipmentController@change_goods_type_status','as'=>'changeGoodsTypeStatus']);
	
	Route::post('goods_type/remove',['uses'=>'admin\ShipmentController@remove_goods_type','as'=>'removeGoodsType']);


	
	// Surge Price
	Route::get('surge_price/list',['uses'=>'admin\ShipmentController@surge_price_list','as'=>'surgePriceList']);

	Route::get('surge_price/add',['uses'=>'admin\ShipmentController@show_add_new_surge_price','as'=>'showAddNewSurgePrice']);
	
	Route::post('surge_price/add_new',['uses'=>'admin\ShipmentController@add_new_surge_price','as'=>'addNewSurgePrice']);
	
	Route::get('surge_price/{id}/edit',['uses'=>'admin\ShipmentController@show_edit_surge_price','as'=>'showEditSurgePrice']);
	
	Route::post('surge_price/update',['uses'=>'admin\ShipmentController@update_surge_price','as'=>'updateSurgePrice']);
	
	Route::post('change_surge_price_status',['uses'=>'admin\ShipmentController@change_surge_price_status','as'=>'changeSurgePriceStatus']);
	
	Route::post('surge_price/remove',['uses'=>'admin\ShipmentController@remove_surge_price','as'=>'removeSurgePrice']);


	// Shipment Surge Price
	Route::get('shipment_surge_price/list',['uses'=>'admin\ShipmentController@shipment_surge_price_list','as'=>'shipmentSurgePriceList']);

	Route::post('change_shipment_surge_price_status',['uses'=>'admin\ShipmentController@change_shipment_surge_price_status','as'=>'changeShipmentSurgePriceStatus']);
	


	// Commission
	Route::get('commission/list',['uses'=>'admin\ShipmentController@commission_list','as'=>'commissionList']);

	Route::get('commission/add/{type?}',['uses'=>'admin\ShipmentController@show_add_new_commission','as'=>'showAddNewCommission']);
	
	Route::post('commission/add_new',['uses'=>'admin\ShipmentController@add_new_commission','as'=>'addNewCommission']);
	
	Route::get('commission/{id}/edit',['uses'=>'admin\ShipmentController@show_edit_commission','as'=>'showEditCommission']);
	
	Route::post('commission/update',['uses'=>'admin\ShipmentController@update_commission','as'=>'updateCommission']);
	
	/*Route::post('change_commission_status',['uses'=>'admin\ShipmentController@change_commission_status','as'=>'changeCommissionStatus']);*/
	
	Route::post('commission/remove',['uses'=>'admin\ShipmentController@remove_commission','as'=>'removeCommission']);




	//review
	Route::get('review/list',['uses'=>'admin\ShipmentController@review_list','as'=>'reviewList']);
	
	Route::post('remove_review',['uses'=>'admin\ShipmentController@remove_review','as'=>'removeReview']);


});
/*---------------------- end admin routes -------------------------*/


/*---------------------- start shipper user routes -------------------------*/

Route::group(array('prefix' => 'shipper','middleware' => 'shipper_auth'), function(){

		Route::get('dashboard', array('uses' => 'shipper\DashboardController@show_dashboard','as' => 'shipperShowDashboard'));

		Route::post('dashboard_filter', array('uses' => 'shipper\DashboardController@dashboard_filter','as' => 'shipperFilterDashboard'));
		
		Route::get('enter_book_details', array('uses' => 'shipper\DashboardController@show_enter_book_details','as' => 'shipperShowEnterBookDetails'));

		Route::get('logout', array('uses' => 'Auth\LoginController@doShipperLogout','as' => 'doShipperLogout'));
		

		Route::get('profile', array('uses' => 'shipper\DashboardController@show_profile','as' => 'shipperShowProfile'));
		
		Route::post('update_profile', array('uses' => 'shipper\DashboardController@update_profile','as' => 'shipperUpdateProfile'));

		Route::get('help_feedback', array('uses' => 'shipper\DashboardController@show_help_feedback','as' => 'shipperShowHelpFeedback'));

		/*------ change password -----*/

		Route::get('change_password', array('uses' => 'shipper\DashboardController@show_change_password','as' => 'shipperShowChangePassword'));

		Route::post('change_password', array('uses' => 'UserController@change_password','as' => 'shipperChangePassword'));

		/*------ change password -----*/

		
		/*notification list*/
		
		Route::get('notification', array('uses' => 'shipper\DashboardController@show_notification_list','as' => 'shipperShowNotificationList'));


		/*-------- Card routes --------*/

		Route::post('add_new_card', array('uses' => 'shipper\DashboardController@add_new_card','as' => 'shipperAddNewCard'));
		
		Route::post('delete_card', array('uses' => 'shipper\DashboardController@delete_card','as' => 'shipperDeleteCard'));
		

		/*-------- Card routes --------*/


		/*------------ shipment routes start ------------*/

		// booking routes
		Route::get('book_truck', array('uses' => 'shipper\DashboardController@show_book_truck','as' => 'shipperShowBookTruck'));
		
		Route::post('book_new_shipment', array('uses' => 'shipper\ShipmentController@book_new_shipment','as' => 'shipperBookNewShipment'));
		
		Route::post('select_bidder', array('uses' => 'shipper\ShipmentController@select_bidder','as' => 'shipperSelectBidder'));

		// apply promo code
		Route::post('apply_promo_code', array('uses' => 'shipper\ShipmentController@apply_promo_code','as' => 'shipperApplyPromoCode'));


		// active shipment routes
		Route::get('active_shipment/{filter_type?}', array('uses' => 'shipper\ShipmentController@show_active_shipment','as' => 'shipperShowActiveShipment'));
		
		Route::post('shipper_active_shipment_filter', array('uses' => 'shipper\ShipmentController@active_shipment_filter','as' => 'shipperActiveShipmentFilter'));
		
		Route::get('shipment/active/{id}/details', array('uses' => 'shipper\ShipmentController@show_active_shipment_detail','as' => 'shipperShowActiveShipmentDetails'));


		// track shipment
		Route::post('track_shipment', array('uses' => 'shipper\ShipmentController@track_shipment','as' => 'shipperTrackShipment'));

		
		// past shipment routes
		Route::get('past_shipment', array('uses' => 'shipper\ShipmentController@show_past_shipment','as' => 'shipperShowPastShipment'));

		Route::post('shipper_past_shipment_filter', array('uses' => 'shipper\ShipmentController@past_shipment_filter','as' => 'shipperPastShipmentListFilter'));
		
		Route::get('shipment/past/{id}/details', array('uses' => 'shipper\ShipmentController@show_past_shipment_details','as' => 'shipperShowPastShipmentDetails'));

		/*cancelled shipment urls*/
		
		Route::get('cancel_shipment', array('uses' => 'shipper\ShipmentController@show_cancel_shipment','as' => 'shipperShowCancelShipment'));
		
		Route::get('shipment/cancel/{id}/details', array('uses' => 'shipper\ShipmentController@show_cancel_shipment_detail','as' => 'shipperShowCancelShipmentDetails'));
		
		/*cancelled shipment urls*/


		/*report shipment urls*/
		
		Route::get('report_shipment', array('uses' => 'shipper\ShipmentController@show_report_shipment','as' => 'shipperShowReportShipment'));
		
		Route::get('shipment/report/{id}/details', array('uses' => 'shipper\ShipmentController@show_report_shipment_detail','as' => 'shipperShowReportShipmentDetails'));
		
		/*report shipment urls*/
		

		// rate shipment
		
		Route::post('rate_shipment', array('uses' => 'shipper\ShipmentController@rate_shipment','as' => 'shipperRateShipment'));
		

		// cancel shipment
		Route::post('cancel_shipment', array('uses' => 'shipper\ShipmentController@cancel_shipment','as' => 'shipperCancelShipment'));

		/*------------ shipment routes end ------------*/
		
});

/*---------------------- end shipper user routes -------------------------*/



/*---------------------- start transporter user routes -------------------------*/
	
Route::group(array('prefix' => 'transporter','middleware' => 'transporter_auth'), function(){

		Route::get('dashboard', array('uses' => 'transporter\DashboardController@show_dashboard','as' => 'transporterShowDashboard'));
		
		Route::post('dashboard_filter', array('uses' => 'transporter\DashboardController@dashboard_filter','as' => 'transporterFilterDashboard'));
		
		Route::get('profile', array('uses' => 'transporter\DashboardController@show_profile','as' => 'transporterShowProfile'));

		Route::post('update_profile', array('uses' => 'transporter\DashboardController@update_profile','as' => 'transporterUpdateProfile'));

		Route::get('logout', array('uses' => 'Auth\LoginController@doTransporterLogout','as' => 'doTransporterLogout'));

		Route::get('help_feedback', array('uses' => 'transporter\DashboardController@show_help_feedback','as' => 'transporterShowHelpFeedback'));

		/*------ change password -----*/

		Route::get('change_password', array('uses' => 'transporter\DashboardController@show_change_password','as' => 'transporterShowChangePassword'));

		Route::post('change_password', array('uses' => 'UserController@change_password','as' => 'transporterChangePassword'));

		/*------ change password -----*/


		/*notification list*/
		
		Route::get('notification', array('uses' => 'transporter\DashboardController@show_notification_list','as' => 'transporterShowNotificationList'));


		/*update online status*/

		Route::post('update_online_status', array('uses' => 'transporter\DashboardController@update_online_status','as' => 'transporterUpdateOnlineStatus'));

		/*------------ shipment routes start ------------*/
		
		/*shipment request urls*/
		
		Route::get('request_list/{filter_type?}', array('uses' => 'transporter\ShipmentController@show_request_list','as' => 'transporterShowRequestList'));
		
		Route::post('transporter_request_list_filter', array('uses' => 'transporter\ShipmentController@request_list_filter','as' => 'transporterRequestListFilter'));

		Route::get('shipment/request/{id}/details',array('uses' => 'transporter\ShipmentController@show_request_shipment_details','as' => 'transporterShowShipmentRequestDetails'));
		
		Route::post('accept_reject_shipment_request', array('uses' => 'transporter\ShipmentController@accept_reject_shipment_request','as' => 'transporterAccpetRejectShipment'));
		
		Route::post('place_new_bid', array('uses' => 'transporter\ShipmentController@place_new_bid','as' => 'transporterPlaceNewBid'));
		
		Route::post('edit_delete_bid', array('uses' => 'transporter\ShipmentController@edit_delete_bid','as' => 'transporterEditDeleteBid'));
		
		Route::post('assign_driver', array('uses' => 'transporter\ShipmentController@assign_driver','as' => 'transporterAssignDriver'));
		
		
		/*shipment request urls*/


		/*active shipment urls*/

		Route::get('active_shipment/{filter_type?}', array('uses' => 'transporter\ShipmentController@show_active_shipment','as' => 'transporterShowActiveShipment'));

		Route::post('transporter_active_shipment_filter', array('uses' => 'transporter\ShipmentController@active_shipment_filter','as' => 'transporterActiveShipmentFilter'));

		Route::get('shipment/active/{id}/details', array('uses' => 'transporter\ShipmentController@show_active_shipment_detail','as' => 'transporterShowActiveShipmentDetails'));
		

		// update shipment status
		Route::post('update_shipment_status', array('uses' => 'transporter\ShipmentController@update_shipment_status','as' => 'transporterUpdateShipmentStatus'));

		// cancel active shipment
		Route::post('cancel_shipment', array('uses' => 'transporter\ShipmentController@cancel_shipment','as' => 'transporterCancelShipment'));
		

		// report emergency
		Route::post('report_emergency', array('uses' => 'transporter\ShipmentController@report_emergency','as' => 'transporterReportEmergency'));

		// track shipment
		Route::post('track_shipment', array('uses' => 'transporter\ShipmentController@track_shipment','as' => 'transporterTrackShipment'));


		/*active shipment urls*/


		/*cancelled shipment urls*/
		
		Route::get('cancel_shipment', array('uses' => 'transporter\ShipmentController@show_cancel_shipment','as' => 'transporterShowCancelShipment'));
		
		Route::get('shipment/cancel/{id}/details', array('uses' => 'transporter\ShipmentController@show_cancel_shipment_detail','as' => 'transporterShowCancelShipmentDetails'));
		
		/*cancelled shipment urls*/



		/*reported shipment urls*/
		
		Route::get('report_shipment', array('uses' => 'transporter\ShipmentController@show_report_shipment','as' => 'transporterShowReportShipment'));
		
		Route::get('shipment/report/{id}/details', array('uses' => 'transporter\ShipmentController@show_report_shipment_detail','as' => 'transporterShowReportShipmentDetails'));
		
		/*reported shipment urls*/
		
		
		/*past shipment urls*/
		
		Route::get('past_shipment', array('uses' => 'transporter\ShipmentController@show_past_shipment','as' => 'transporterShowPastShipment'));

		Route::post('transporter_past_shipment_filter', array('uses' => 'transporter\ShipmentController@past_shipment_filter','as' => 'transporterPastShipmentListFilter'));

		// update pay status
		Route::post('transporter_update_pay_shipment_status', array('uses' => 'transporter\ShipmentController@transporter_update_pay_shipment_status','as' => 'transporterUpdatePayShipmentStatus'));
		
		Route::get('shipment/past/{id}/details', array('uses' => 'transporter\ShipmentController@show_past_shipment_details','as' => 'transporterShowPastShipmentDetails'));
		
		/*past shipment urls*/
		
		/*------------ shipment routes end ------------*/


		/*------------ Driver routes ------------*/
		
		Route::post('add_driver', array('uses' => 'transporter\DriverController@add_driver','as' => 'transporterAddDriver'));

		Route::get('driver_list', array('uses' => 'transporter\DriverController@show_driver_list','as' => 'transporterShowDriverList'));
		
		Route::post('driver_list_for_assign', array('uses' => 'transporter\DriverController@show_driver_list_for_assign','as' => 'transporterShowDriverListForAssign'));
		
		Route::post('update_driver_doc', array('uses' => 'transporter\DriverController@update_driver_doc','as' => 'transporterUpdateDriverDoc'));

		Route::post('remove_join_driver', array('uses' => 'transporter\DriverController@remove_join_driver','as' => 'transporterRemoveJoinDriver'));
		
		Route::post('show_truck_list_for_driver_add', array('uses' => 'transporter\DriverController@show_truck_list_for_driver_add','as' => 'transporterShowTruckListForDriverAdd'));
		

		/*------------ Driver routes ------------*/


		/*------------ Truck routes ------------*/
		
		Route::get('truck_list', array('uses' => 'transporter\DriverController@show_truck_list','as' => 'transporterShowTruckList'));
		
		Route::post('truck_list_for_add', array('uses' => 'transporter\DriverController@show_truck_list_for_add','as' => 'transporterShowTruckListForAdd'));
		
		Route::post('add_new_truck', array('uses' => 'transporter\DriverController@add_new_truck','as' => 'transporterAddTruck'));
		
		Route::post('change_truck_status', array('uses' => 'transporter\DriverController@change_truck_status','as' => 'transporterChangeTruckStatus'));
		
		Route::post('remove_added_truck', array('uses' => 'transporter\DriverController@remove_added_truck','as' => 'transporterRemoveAddedTruck'));

		/*------------ Truck routes ------------*/


});

/*---------------------- end transporter user routes -------------------------*/



/*---------------------- start driver user routes -------------------------*/
	
Route::group(array('prefix' => 'driver','middleware' => 'driver_auth'), function(){

		Route::get('dashboard', array('uses' => 'driver\DashboardController@show_dashboard','as' => 'driverShowDashboard'));
		
		Route::post('dashboard_filter', array('uses' => 'driver\DashboardController@dashboard_filter','as' => 'driverFilterDashboard'));
		
		Route::get('profile', array('uses' => 'driver\DashboardController@show_profile','as' => 'driverShowProfile'));

		Route::post('update_profile', array('uses' => 'driver\DashboardController@update_profile','as' => 'driverUpdateProfile'));

		Route::get('logout', array('uses' => 'Auth\LoginController@doDriverLogout','as' => 'doDriverLogout'));

		Route::get('help_feedback', array('uses' => 'driver\DashboardController@show_help_feedback','as' => 'driverShowHelpFeedback'));


		/*------ change password -----*/

		Route::get('change_password', array('uses' => 'driver\DashboardController@show_change_password','as' => 'driverShowChangePassword'));

		Route::post('change_password', array('uses' => 'UserController@change_password','as' => 'driverChangePassword'));

		/*------ change password -----*/


		/*notification list*/
		
		Route::get('notification', array('uses' => 'driver\DashboardController@show_notification_list','as' => 'driverShowNotificationList'));

		/*update online status*/
		
		Route::post('update_online_status', array('uses' => 'driver\DashboardController@update_online_status','as' => 'driverUpdateOnlineStatus'));
		

		/*------------ shipment routes start ------------*/


		/*shipment request urls*/
		
		Route::get('request_list', array('uses' => 'driver\ShipmentController@show_request_list','as' => 'driverShowRequestList'));

		Route::post('driver_request_list_filter', array('uses' => 'driver\ShipmentController@request_list_filter','as' => 'driverRequestListFilter'));
		
		Route::get('shipment/request/{id}/details',array('uses' => 'driver\ShipmentController@show_request_shipment_details','as' => 'driverShowShipmentRequestDetails'));
		
		Route::post('accept_reject_shipment_request', array('uses' => 'driver\ShipmentController@accept_reject_shipment_request','as' => 'driverAccpetRejectShipment'));
		
		Route::post('place_new_bid', array('uses' => 'driver\ShipmentController@place_new_bid','as' => 'driverPlaceNewBid'));

		Route::post('edit_delete_bid', array('uses' => 'driver\ShipmentController@edit_delete_bid','as' => 'driverEditDeleteBid'));
		
		
		/*shipment request urls*/


		/*active shipment urls*/

		Route::get('active_shipment', array('uses' => 'driver\ShipmentController@show_active_shipment','as' => 'driverShowActiveShipment'));

		Route::post('driver_active_shipment_filter', array('uses' => 'driver\ShipmentController@active_shipment_filter','as' => 'driverActiveShipmentFilter'));

		Route::get('shipment/active/{id}/details', array('uses' => 'driver\ShipmentController@show_active_shipment_detail','as' => 'driverShowActiveShipmentDetails'));

		// update shipment status
		Route::post('update_shipment_status', array('uses' => 'driver\ShipmentController@update_shipment_status','as' => 'driverUpdateShipmentStatus'));

		// cancel active shipment
		Route::post('cancel_shipment', array('uses' => 'driver\ShipmentController@cancel_shipment','as' => 'driverCancelShipment'));
		

		// report emergency
		Route::post('report_emergency', array('uses' => 'driver\ShipmentController@report_emergency','as' => 'driverReportEmergency'));

		// track shipment
		Route::post('track_shipment', array('uses' => 'driver\ShipmentController@track_shipment','as' => 'driverTrackShipment'));
		
		// update pay status
		Route::post('driver_update_pay_shipment_status', array('uses' => 'driver\ShipmentController@driver_update_pay_shipment_status','as' => 'driverUpdatePayShipmentStatus'));


		
		/*active shipment urls*/
		
		
		/*cancelled shipment urls*/
		
		Route::get('cancel_shipment', array('uses' => 'driver\ShipmentController@show_cancel_shipment','as' => 'driverShowCancelShipment'));
		
		Route::get('shipment/cancel/{id}/details', array('uses' => 'driver\ShipmentController@show_cancel_shipment_detail','as' => 'driverShowCancelShipmentDetails'));
		
		/*cancelled shipment urls*/


		/*Reported shipment urls*/
		
		Route::get('report_shipment', array('uses' => 'driver\ShipmentController@show_report_shipment','as' => 'driverShowReportShipment'));
		
		Route::get('shipment/report/{id}/details', array('uses' => 'driver\ShipmentController@show_report_shipment_detail','as' => 'driverShowReportShipmentDetails'));
		
		/*Reported shipment urls*/


		/*past shipment urls*/
		
		Route::get('past_shipment', array('uses' => 'driver\ShipmentController@show_past_shipment','as' => 'driverShowPastShipment'));

		Route::post('driver_past_shipment_filter', array('uses' => 'driver\ShipmentController@past_shipment_filter','as' => 'driverPastShipmentListFilter'));
		
		Route::get('shipment/past/{id}/details', array('uses' => 'driver\ShipmentController@show_past_shipment_details','as' => 'driverShowPastShipmentDetails'));
		
		/*past shipment urls*/

		/*------------ shipment routes end ------------*/


		/*------------ Transporter routes ------------*/
		

		Route::get('transporter_list', array('uses' => 'driver\TransporterController@show_transporter_list','as' => 'driverShowTransporterList'));
		
		Route::post('accept_reject_join_request', array('uses' => 'driver\TransporterController@accept_reject_join_request','as' => 'driverAccpetRejectJoinRequest'));
	

		/*------------ Transporter routes ------------*/

		/*------------ Truck routes ------------*/
		
		Route::get('truck_list', array('uses' => 'driver\TransporterController@show_truck_list','as' => 'driverShowTruckList'));
		
		Route::post('truck_list_for_add', array('uses' => 'driver\TransporterController@show_truck_list_for_add','as' => 'driverShowTruckListForAdd'));
		
		Route::post('add_new_truck', array('uses' => 'driver\TransporterController@add_new_truck','as' => 'driverAddTruck'));
		
		Route::post('change_truck_status', array('uses' => 'driver\TransporterController@change_truck_status','as' => 'driverChangeTruckStatus'));
		
		Route::post('remove_added_truck', array('uses' => 'driver\TransporterController@remove_added_truck','as' => 'driverRemoveAddedTruck'));

		/*------------ Truck routes ------------*/
		
	});

/*---------------------- end transporter user routes -------------------------*/
	
