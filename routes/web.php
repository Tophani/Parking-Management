<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

/*
|-----------------------------------------------------------
| COMMON SECTION
|-----------------------------------------------------------
*/
#authentication 
Route::get('logout', 'App\Http\Controllers\Auth\LoginController@logout');
Auth::routes();

#corn job
Route::get('sms/jobs', 'App\Http\Controllers\Admin\SmsCampaignController@jobs');
Route::get('email/jobs', 'App\Http\Controllers\Admin\EmailCampaignController@jobs');

#client contact data
Route::get('client/contact/data', 'App\Http\Controllers\Admin\ClientController@getClientContactData');
Route::get('client/contact/email', 'App\Http\Controllers\Admin\ClientController@getEmail');
Route::get('client/contact/mobile', 'App\Http\Controllers\Admin\ClientController@getMobile');

/*
|-----------------------------------------------------------
| WEBSITE SECTION
|-----------------------------------------------------------
*/
#website
Route::get('/', 'App\Http\Controllers\Website\HomeController@index');
Route::get('website', 'App\Http\Controllers\Website\HomeController@index');

Route::post('website/auth/register', 'App\Http\Controllers\Website\AuthController@register');
Route::post('website/auth/login', 'App\Http\Controllers\Website\AuthController@login');
Route::get('website/auth/logout', 'App\Http\Controllers\Website\AuthController@logout');

Route::get('website/auth/account_confirmation', 'App\Http\Controllers\Website\AuthController@accountConfirmation');
// Route::post('website/auth/forgot_password', 'App\Http\Controllers\Website\AuthController@forgotPassword');
 
Route::post('website/mail/send', 'App\Http\Controllers\Website\MailController@send');
Route::get('website/language/{name}', 'App\Http\Controllers\Website\LanguageController@switchLanguage');
Route::get('website/home/parking/prices', 'App\Http\Controllers\Website\HomeController@getPrices');

Route::group(["middleware" => ["roles:client"]], function() {

	Route::get('website/profile', 'App\Http\Controllers\Website\AuthController@profile');
	Route::get('website/profile/edit', 'App\Http\Controllers\Website\AuthController@profileEdit');
	Route::post('website/profile/edit', 'App\Http\Controllers\Website\AuthController@profileUpdate');
	Route::post('website/vehicle', 'App\Http\Controllers\Website\AuthController@newVehicle');
	Route::get('website/vehicle/status/{status}/{id}', 'App\Http\Controllers\Website\AuthController@statusVehicle');

	// Booking
	Route::get('website/booking', 'App\Http\Controllers\Website\BookingController@showForm');
	Route::post('website/booking/place_order', 'App\Http\Controllers\Website\BookingController@placeOrder');
	Route::get('website/booking/status', 'App\Http\Controllers\Website\BookingController@paymentStatus');

	Route::post('website/booking/period', 'App\Http\Controllers\Website\BookingController@getZoneAndVehicleWisePriceList');
	Route::post('website/booking/show-schedule', 'App\Http\Controllers\Website\BookingController@findScheduleAndPrice');
	Route::post('website/booking/promocode', 'App\Http\Controllers\Website\BookingController@getDiscount');

	Route::get('website/history', 'App\Http\Controllers\Website\BookingController@history');
	Route::get('website/booking/history-data', 'App\Http\Controllers\Website\BookingController@historyData');
	Route::get('website/booking/invoice', 'App\Http\Controllers\Website\BookingController@invoice');
});

	
/*
|-----------------------------------------------------------
| SUPER ADMIN & ADMIN SECTION
|-----------------------------------------------------------
*/
Route::group(["middleware" => ["auth", "roles:superadmin,admin"]], function() {

	# dashboard
	Route::get('dashboard', 'App\Http\Controllers\Admin\DashboardController@index');
	Route::get('admin/dashboard', 'App\Http\Controllers\Admin\DashboardController@index');

	# client
	Route::get('admin/client/new', 'App\Http\Controllers\Admin\ClientController@form');
	Route::post('admin/client/new', 'App\Http\Controllers\Admin\ClientController@create');
	Route::get('admin/client/list', 'App\Http\Controllers\Admin\ClientController@list');
	Route::get('admin/client/edit/{id}', 'App\Http\Controllers\Admin\ClientController@edit');
	Route::post('admin/client/edit', 'App\Http\Controllers\Admin\ClientController@update');
	Route::get('admin/client/delete/{id}', 'App\Http\Controllers\Admin\ClientController@delete');
	Route::get('admin/client/data', 'App\Http\Controllers\Admin\ClientController@getClientData');
	Route::get('admin/client/profile/{id}', 'App\Http\Controllers\Admin\ClientController@profile');

	Route::post('admin/client/vehicle', 'App\Http\Controllers\Admin\ClientController@newVehicle');
	Route::post('admin/client/vehicle/edit/{id}', 'App\Http\Controllers\Admin\ClientController@updateVehicle');
	Route::get('admin/client/vehicle/status/{status}/{id}', 'App\Http\Controllers\Admin\ClientController@statusVehicle');

	# user list
	Route::get('admin/vehicle_type/new', 'App\Http\Controllers\Admin\VehicleTypeController@form');
	Route::post('admin/vehicle_type/new', 'App\Http\Controllers\Admin\VehicleTypeController@create');
	Route::get('admin/vehicle_type/list', 'App\Http\Controllers\Admin\VehicleTypeController@show');
	Route::get('admin/vehicle_type/edit/{id}', 'App\Http\Controllers\Admin\VehicleTypeController@editForm');
	Route::post('admin/vehicle_type/edit', 'App\Http\Controllers\Admin\VehicleTypeController@update');
	Route::get('admin/vehicle_type/delete/{id}', 'App\Http\Controllers\Admin\VehicleTypeController@delete');
	Route::get('admin/vehicle_type/data', 'App\Http\Controllers\Admin\VehicleTypeController@getAdminData');

	# place or park location
	Route::get('admin/place/new', 'App\Http\Controllers\Admin\PlaceController@form');
	Route::post('admin/place/new', 'App\Http\Controllers\Admin\PlaceController@create');
	Route::get('admin/place/list', 'App\Http\Controllers\Admin\PlaceController@list');
	Route::get('admin/place/edit/{id}', 'App\Http\Controllers\Admin\PlaceController@edit');
	Route::post('admin/place/edit', 'App\Http\Controllers\Admin\PlaceController@update');
	Route::get('admin/place/delete/{id}', 'App\Http\Controllers\Admin\PlaceController@delete');
	Route::get('admin/place/show/{id}', 'App\Http\Controllers\Admin\PlaceController@show');
	Route::get('admin/place/data', 'App\Http\Controllers\Admin\PlaceController@getListData');

	# price
	Route::get('admin/price/list', 'App\Http\Controllers\Admin\PriceController@list');
	Route::get('admin/price/data', 'App\Http\Controllers\Admin\PriceController@getPriceData');
	Route::get('admin/price/new', 'App\Http\Controllers\Admin\PriceController@form');
	Route::post('admin/price/new', 'App\Http\Controllers\Admin\PriceController@create');
	Route::get('admin/price/edit/{p_id}', 'App\Http\Controllers\Admin\PriceController@edit');
	Route::post('admin/price/edit', 'App\Http\Controllers\Admin\PriceController@update');
	Route::get('admin/price/delete/{p_id}', 'App\Http\Controllers\Admin\PriceController@delete');
	Route::get('admin/price/show/{p_id}', 'App\Http\Controllers\Admin\PriceController@show');

	# promocode
	Route::get('admin/promocode/new', 'App\Http\Controllers\Admin\PromocodeController@form');
	Route::post('admin/promocode/new', 'App\Http\Controllers\Admin\PromocodeController@create');
	Route::get('admin/promocode/edit/{id}', 'App\Http\Controllers\Admin\PromocodeController@edit');
	Route::post('admin/promocode/edit', 'App\Http\Controllers\Admin\PromocodeController@update');
	Route::get('admin/promocode/list', 'App\Http\Controllers\Admin\PromocodeController@show');
	Route::get('admin/promocode/data', 'App\Http\Controllers\Admin\PromocodeController@getPromocodeData');
	Route::get('admin/promocode/delete/{id}', 'App\Http\Controllers\Admin\PromocodeController@delete');

	# email
	Route::get('admin/email/new', 'App\Http\Controllers\Admin\EmailCampaignController@form');
	Route::post('admin/email/new', 'App\Http\Controllers\Admin\EmailCampaignController@send');
	Route::get('admin/email/list', 'App\Http\Controllers\Admin\EmailCampaignController@show');
	Route::get('admin/email/delete/{id}', 'App\Http\Controllers\Admin\EmailCampaignController@delete');
	Route::get('admin/email/data', 'App\Http\Controllers\Admin\EmailCampaignController@getCampaignData');
	Route::get('admin/email/setting', 'App\Http\Controllers\Admin\EmailCampaignController@setting');
	Route::post('admin/email/setting', 'App\Http\Controllers\Admin\EmailCampaignController@updateSetting');
	Route::get('admin/email/bulk', 'App\Http\Controllers\Admin\EmailCampaignController@bulk');
	Route::post('admin/email/bulk', 'App\Http\Controllers\Admin\EmailCampaignController@sendBulk');

	# sms
	Route::get('admin/sms/new', 'App\Http\Controllers\Admin\SmsCampaignController@form');
	Route::post('admin/sms/new', 'App\Http\Controllers\Admin\SmsCampaignController@send');
	Route::get('admin/sms/list', 'App\Http\Controllers\Admin\SmsCampaignController@show');
	Route::get('admin/sms/delete/{id}', 'App\Http\Controllers\Admin\SmsCampaignController@delete');
	Route::get('admin/sms/data', 'App\Http\Controllers\Admin\SmsCampaignController@getData');
	Route::get('admin/sms/setting', 'App\Http\Controllers\Admin\SmsCampaignController@setting');
	Route::post('admin/sms/setting', 'App\Http\Controllers\Admin\SmsCampaignController@updateSetting');

	# booking
	Route::get('admin/booking/form', 'App\Http\Controllers\Admin\BookingController@form');
	Route::post('admin/booking/place_order', 'App\Http\Controllers\Admin\BookingController@placeOrder');
	Route::get('admin/booking/invoice', 'App\Http\Controllers\Admin\BookingController@invoice');
	Route::get('admin/booking/release', 'App\Http\Controllers\Admin\BookingController@release');
	Route::get('admin/booking/fine', 'App\Http\Controllers\Admin\BookingController@fine');
	Route::get('admin/booking/payment_status', 'App\Http\Controllers\Admin\BookingController@paid');
	Route::get('admin/booking/{type}', 'App\Http\Controllers\Admin\BookingController@show');
	Route::get('admin/booking/get-data/{type}', 'App\Http\Controllers\Admin\BookingController@getData');
	Route::get('admin/booking/delete/{id_no}', 'App\Http\Controllers\Admin\BookingController@delete');

	Route::post('admin/booking/getZoneAndVehicleWisePriceList', 'App\Http\Controllers\Admin\BookingController@getZoneAndVehicleWisePriceList');
	Route::post('admin/booking/findScheduleAndPrice', 'App\Http\Controllers\Admin\BookingController@findScheduleAndPrice');
	Route::post('admin/booking/getPriceList', 'App\Http\Controllers\Admin\BookingController@getPriceList');
	Route::post('admin/booking/getDiscount', 'App\Http\Controllers\Admin\BookingController@getDiscount');
	Route::post('admin/booking/checkClientID', 'App\Http\Controllers\Admin\BookingController@checkClientID');
	Route::post('admin/booking/createClient', 'App\Http\Controllers\Admin\BookingController@createClient');

	# report
	Route::get('admin/report', 'App\Http\Controllers\Admin\ReportController@index');

	#message  
	Route::get('admin/message/new', 'App\Http\Controllers\Admin\MessageController@form'); 
	Route::post('admin/message/new', 'App\Http\Controllers\Admin\MessageController@new'); 
	Route::get('admin/message/inbox', 'App\Http\Controllers\Admin\MessageController@inbox'); 
	Route::get('admin/message/inbox/data', 'App\Http\Controllers\Admin\MessageController@getInboxData');
	Route::get('admin/message/sent', 'App\Http\Controllers\Admin\MessageController@sent'); 
	Route::get('admin/message/sent/data', 'App\Http\Controllers\Admin\MessageController@getSentData'); 
	Route::get('admin/message/details/{id}/{type}', 'App\Http\Controllers\Admin\MessageController@details'); 
	Route::get('admin/message/delete/{id}/{type}', 'App\Http\Controllers\Admin\MessageController@delete');
	Route::get('admin/message/notify', 'App\Http\Controllers\Admin\MessageController@notify'); 

	#Language
	Route::get('admin/language/setting', 'App\Http\Controllers\Admin\LanguageController@setting');
	Route::post('admin/language/add', 'App\Http\Controllers\Admin\LanguageController@addLanguage');
	Route::get('admin/language/default/{name}', 'App\Http\Controllers\Admin\LanguageController@defaultLanguage');
	Route::get('admin/language/delete/{name}', 'App\Http\Controllers\Admin\LanguageController@deleteLanguage');
	Route::get('admin/language/label', 'App\Http\Controllers\Admin\LanguageController@label');
	Route::get('admin/language/label/delete/{id}', 'App\Http\Controllers\Admin\LanguageController@deleteLabel');
	Route::post('admin/language/label/add', 'App\Http\Controllers\Admin\LanguageController@addLabel');
	Route::post('admin/language/label/update', 'App\Http\Controllers\Admin\LanguageController@updateLabel');

	# user list
	Route::get('admin/user/new', 'App\Http\Controllers\Admin\UserController@form');
	Route::post('admin/user/new', 'App\Http\Controllers\Admin\UserController@create');
	Route::get('admin/user/list', 'App\Http\Controllers\Admin\UserController@show');
	Route::get('admin/user/edit/{id}', 'App\Http\Controllers\Admin\UserController@editForm');
	Route::post('admin/user/edit', 'App\Http\Controllers\Admin\UserController@update');
	Route::get('admin/user/delete/{id}', 'App\Http\Controllers\Admin\UserController@delete');
	Route::get('admin/user/data', 'App\Http\Controllers\Admin\UserController@getAdminData');

	#profile
	Route::get('admin/setting/profile', 'App\Http\Controllers\Admin\ProfileController@profile');
	Route::post('admin/setting/profile', 'App\Http\Controllers\Admin\ProfileController@profileUpdate');

	# setting
	Route::get('admin/setting/app', 'App\Http\Controllers\Admin\SettingController@app');
	Route::post('admin/setting/app', 'App\Http\Controllers\Admin\SettingController@updateApp');
	Route::post('admin/setting/map', 'App\Http\Controllers\Admin\SettingController@updateMap');
	Route::post('admin/setting/price', 'App\Http\Controllers\Admin\SettingController@updatePrice');
	Route::post('admin/setting/paypal', 'App\Http\Controllers\Admin\SettingController@updatePayPal');
	Route::post('admin/setting/notification', 'App\Http\Controllers\Admin\SettingController@updateNotification');
	Route::post('admin/setting/website', 'App\Http\Controllers\Admin\SettingController@website');


});
 
/*
|-----------------------------------------------------------
| OPERATOR SECTION
|-----------------------------------------------------------
*/

Route::group(["middleware" => ["auth","roles:operator"]], function() {
	# dashboard
	Route::get('dashboard', 'App\Http\Controllers\Operator\DashboardController@index');
	Route::get('operator/dashboard', 'App\Http\Controllers\Operator\DashboardController@index');

	#profile
	Route::get('operator/setting/profile', 'App\Http\Controllers\Operator\ProfileController@profile');
	Route::post('operator/setting/profile', 'App\Http\Controllers\Operator\ProfileController@profileUpdate'); 

	# client
	Route::get('operator/client/new', 'App\Http\Controllers\Operator\ClientController@form');
	Route::post('operator/client/new', 'App\Http\Controllers\Operator\ClientController@create');
	Route::get('operator/client/list', 'App\Http\Controllers\Operator\ClientController@list');
	Route::get('operator/client/data', 'App\Http\Controllers\Operator\ClientController@getClientData');
	Route::get('operator/client/profile/{id}', 'App\Http\Controllers\Operator\ClientController@profile');

	# parking zone info
	Route::get('operator/parking_zone', 'App\Http\Controllers\Operator\ZoneInfoController@parking_zone');
	Route::get('operator/parking_zone/{id}', 'App\Http\Controllers\Operator\ZoneInfoController@parkingZoneDetails');

	# email  
	Route::get('operator/email/new', 'App\Http\Controllers\Operator\EmailCampaignController@form');
	Route::post('operator/email/new', 'App\Http\Controllers\Operator\EmailCampaignController@send');
	Route::get('operator/email/list', 'App\Http\Controllers\Operator\EmailCampaignController@show');
	Route::get('operator/email/data', 'App\Http\Controllers\Operator\EmailCampaignController@getData');

	# sms 
	Route::get('operator/sms/new', 'App\Http\Controllers\Operator\SmsCampaignController@form');
	Route::post('operator/sms/new', 'App\Http\Controllers\Operator\SmsCampaignController@send');
	Route::get('operator/sms/list', 'App\Http\Controllers\Operator\SmsCampaignController@show');
	Route::get('operator/sms/data', 'App\Http\Controllers\Operator\SmsCampaignController@getData');

	# booking
	Route::get('operator/booking/form', 'App\Http\Controllers\Operator\BookingController@form');
	Route::post('operator/booking/place_order', 'App\Http\Controllers\Operator\BookingController@placeOrder');
	Route::get('operator/booking/invoice', 'App\Http\Controllers\Operator\BookingController@invoice');
	Route::get('operator/booking/release', 'App\Http\Controllers\Operator\BookingController@release');
	Route::get('operator/booking/fine', 'App\Http\Controllers\Operator\BookingController@fine');
	Route::get('operator/booking/payment_status', 'App\Http\Controllers\Operator\BookingController@paid');
	Route::get('operator/booking/{type}', 'App\Http\Controllers\Operator\BookingController@show');
	Route::get('operator/booking/get-data/{type}', 'App\Http\Controllers\Operator\BookingController@getData');

	Route::post('operator/booking/getZoneAndVehicleWisePriceList', 'App\Http\Controllers\Operator\BookingController@getZoneAndVehicleWisePriceList');
	Route::post('operator/booking/findScheduleAndPrice', 'App\Http\Controllers\Operator\BookingController@findScheduleAndPrice');
	Route::post('operator/booking/getPriceList', 'App\Http\Controllers\Operator\BookingController@getPriceList');
	Route::post('operator/booking/getDiscount', 'App\Http\Controllers\Operator\BookingController@getDiscount');
	Route::post('operator/booking/checkClientID', 'App\Http\Controllers\Operator\BookingController@checkClientID');
	Route::post('operator/booking/createClient', 'App\Http\Controllers\Operator\BookingController@createClient');

	# report
	Route::get('operator/report', 'App\Http\Controllers\Operator\ReportController@index');

	#message  
	Route::get('operator/message/new', 'App\Http\Controllers\Operator\MessageController@form');
	Route::post('operator/message/new', 'App\Http\Controllers\Operator\MessageController@new');
	Route::get('operator/message/inbox', 'App\Http\Controllers\Operator\MessageController@inbox'); 
	Route::get('operator/message/inbox/data', 'App\Http\Controllers\Operator\MessageController@getInboxData');
	Route::get('operator/message/sent', 'App\Http\Controllers\Operator\MessageController@sent'); 
	Route::get('operator/message/sent/data', 'App\Http\Controllers\Operator\MessageController@getSentData'); 
	Route::get('operator/message/details/{id}/{type}', 'App\Http\Controllers\Operator\MessageController@details'); 
	Route::get('operator/message/delete/{id}/{type}', 'App\Http\Controllers\Operator\MessageController@delete');
	Route::get('operator/message/notify', 'App\Http\Controllers\Operator\MessageController@notify'); 

	 
});
