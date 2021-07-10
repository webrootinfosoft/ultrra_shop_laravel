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

Route::group(['namespace' => 'Api', 'middleware' => 'auth:sanctum'], function () {
    Route::get('dashboard', 'HomeController@dashboard');
    Route::get('dashboard-additional-data', 'HomeController@dashboardAdditionalData');
    Route::get('leaderboard', 'HomeController@leaderboard');
    Route::get('travel-leaderboard', 'HomeController@travelLeaderboard');
    Route::get('match-bonus-tracker/{date}/{i}', 'HomeController@matching_bonus_tracker');
    Route::get('renewal-days', 'HomeController@renewalDays');
    Route::get('user-orders', 'OrderController@userOrders');
    Route::get('shipping-methods', 'ShippingMethodController@index');
    Route::get('all-products', 'ProductController@allProducts');
    Route::get('product/{id}', 'ProductController@show');
//    Route::get('all-countries', 'CountryController@allCountries');
//    Route::get('states-by-country/{country_id}', 'StateController@statesByCountry');
    Route::get('country/{id}', 'CountryController@show');
    Route::get('product-categories-by-product-type/{product_type_id}', 'ProductCategoryController@productCategoriesByProductType');
//    Route::get('product-categories', 'ProductCategoryController@index');
    Route::get('product-type', function () {
        return response()->json(['data' => \App\ProductType::all()]);
    });
    Route::get(' product-category', function () {
        return response()->json(['data' => \App\ProductCategory::all()]);
    });
    Route::get('user-credit-cards', 'CreditCardController@userCreditCards');
    Route::get('delete-credit-card/{id}', 'CreditCardController@deleteCreditCard');
    Route::post('change-password', 'AuthController@changePassword');
    Route::post('change-username', 'AuthController@changeUsername');
    Route::put('update-profile/{id}', 'UserController@updateProfile');
    Route::get('sponsor/{id}', 'UserController@sponsor');
    Route::get('user-addresses', 'AddressController@userAddresses');
    Route::post('add-address', 'AddressController@addAddress');
    Route::put('update-address/{id}', 'AddressController@updateAddress');
    Route::get('delete-address/{id}', 'AddressController@deleteAddress');
    Route::get('downline-report', 'ReportController@downlineReport');
    Route::get('downline-users-report', 'ReportController@downlineUsersReport');
    Route::get('global-share-report', 'ReportController@globalShareReport');
    Route::get('new-rank-report', 'ReportController@newRankReport');
    Route::get('top-enrollers-report', 'ReportController@topEnrollersReport');
    Route::get('rank-history-report', 'ReportController@rankHistoryReport');
    Route::get('sponsor-report', 'ReportController@sponsorReport');
    Route::get('tc-history-report', 'ReportController@tcHistoryReport');
    Route::post('travel-destination-reservation-request', 'ReportController@travelDestinationReservationRequest');
    Route::get('ewallet-balance', 'EwalletController@ewalletBalance');
    Route::get('ewallet-log', 'EwalletController@ewalletLog');
    Route::post('fund-transfer', 'EwalletController@fundTransfer');
    Route::get('payout-history', 'EwalletController@requestPayoutHistory');
    Route::post('payout-request', 'EwalletController@payoutRequest');
    Route::get('sponsor-tree/{id?}', 'TreeController@sponsorTree');
    Route::get('bingo-tree/{id?}', 'TreeController@bingoTree');
    Route::get('bingo-tree-user-data/{id?}', 'TreeController@getBingoTreeUserData');
    Route::get('bingo-tree-last-user/{id}/{leg}', 'TreeController@getLastTreeUser');
    Route::get('commission-monthly', 'EwalletController@commissionMonthly');
    Route::get('commission-weekly', 'EwalletController@commissionWeekly');
    Route::get('weekly-details', 'EwalletController@weeklyDetails');
    Route::get('paylution-account', 'EwalletController@paylutionAccount');
    Route::post('create-paylution-account', 'EwalletController@createPaylutionAccount');
    Route::get('autoships', 'AutoshipController@index');
    Route::get('autoship-create', 'AutoshipController@create');
    Route::get('autoship/{id}', 'AutoshipController@show');
    Route::put('autoship-update/{id}', 'AutoshipController@update');
    Route::put('autoship-item-add/{id}', 'AutoshipController@addItem');
    Route::put('autoship-item-increment/{autoship_id}/{product_id}', 'AutoshipController@incrementItem');
    Route::put('autoship-item-decrement/{autoship_id}/{product_id}', 'AutoshipController@decrementItem');
    Route::get('user-autoship-cart', 'AutoshipController@userCart');
    Route::post('autoship-cart-add', 'AutoshipController@addCart');
    Route::put('autoship-cart-item-add/{id}', 'AutoshipController@addCartItem');
    Route::put('autoship-cart-item-increment/{cart_id}/{product_id}', 'AutoshipController@incrementCartItem');
    Route::put('autoship-cart-item-decrement/{cart_id}/{product_id}', 'AutoshipController@decrementCartItem');
    Route::get('autoship-cart-empty/{cart_id}', 'AutoshipController@emptyCart');
    Route::post('autoship-cart-submit', 'AutoshipController@submit');
    Route::get('get-shipping-rates', 'ShippingMethodController@getShippingRates');
    Route::get('show-shipping-rate/{id}', 'ShippingMethodController@showShippingRate');
    Route::get('renewal-history', 'MembershipRenewalController@renewalHistory');
    Route::post('renew-membership', 'MembershipRenewalController@renewMembership');
    Route::get('user-cart/{user_id}', 'CartController@userCart');
});
Route::apiResource('cart', 'Api\CartController');
Route::get('product-categories', 'Api\ProductCategoryController@index');
Route::get('get-shipping-rates', 'Api\ShippingMethodController@getShippingRates');
Route::get('waiting-room-users/{user_id}', 'Api\UserController@waitingRoomUsers');
Route::post('place-waiting-room', 'Api\UserController@placeWaitingRoom');
Route::get('all-products', 'Api\ProductController@allProducts');
Route::get('product-categories-by-product-type/{product_type_id}', 'Api\ProductCategoryController@productCategoriesByProductType');
Route::post('user', 'Api\UserController@store');
Route::get('user/{id}', 'Api\UserController@show');
Route::put('user/{id}', 'Api\UserController@update');
Route::get('autocomplete', 'Api\UserController@autocomplete');
Route::get('all-countries', 'Api\CountryController@allCountries');
Route::get('user-addresses-by-id/{user_id}', 'Api\AddressController@addressesByUserId');
Route::get('address/{id}', 'Api\AddressController@show');
Route::get('user-orders-by-id/{user_id}', 'Api\OrderController@ordersByUserId');
Route::get('check-sponsor/{id}', 'Api\UserController@checkSponsor');
Route::get('check-user', 'Api\UserController@checkUser');
Route::get('get-placement/{id}', 'Api\UserController@getPlacement');
Route::get('get-placement-info/{id}', 'Api\UserController@getPlacementInfo');
Route::get('get-business-center/{id}', 'Api\UserController@getBusinessCenter');
Route::get('enroll/{sponsor_id}/{placement_user_id}/{business_center_id}/{leg}', 'Api\UserController@enroll');
Route::get('states-by-country/{country_id}', 'StateController@statesByCountry');
Route::apiResource('address', 'AddressController');
Route::apiResource('credit-card', 'CreditCardController');
Route::post('place-order', 'OrderController@placeOrder');
Route::post('place-order-new', 'OrderController@placeOrderNew');
Route::get('tax-and-charges', 'Api\TaxController@chargeTaxes');
Route::get('product/{id}', 'Api\ProductController@show');
Route::post('contact-us', 'Api\HomeController@contactUs');
Route::get('/invoice/{id}', 'Api\HomeController@invoice');
Route::get('check-sponsor-office/{username}', function ($username) {
    $user = \Illuminate\Support\Facades\DB::connection('mysql2')->table('users')
        ->leftJoin('profile_infos', 'profile_infos.user_id', 'users.id')
        ->where('users.username', $username)
        ->where('users.usertype', 'dc')
        ->select('users.name','users.lastname', 'profile_infos.country', 'profile_infos.image', 'users.username', 'profile_infos.mobile', 'users.email')
        ->first();

    $data = [
        'status' => 1,
        'name' => $user->name." ".$user->lastname,
        'country' => $user->country,
        'username' => $user->username,
        'mobile' =>  $user->mobile,
        'email' => $user->email,
        'image' => 'https://office.ultrra.com/img/cache/profile/'.$user->image
    ];

    return response()->json($data);
});
Route::get('get-ip', function () {
    return request()->ip();
});
Route::get('all-users-username/{id}', function ($id) {
    $usernames = \App\User::where('id', '!=', $id)->whereNotNull('username')->pluck('username');
    return response()->json($usernames);
});
//On Unauthorized Login
Route::get('error', function() {
    return response()->json(['error' => 'Invalid Token'], 401);
})->name('login');
Route::get('test', function () {
    return \Illuminate\Support\Facades\Hash::make('demo#123');
});
