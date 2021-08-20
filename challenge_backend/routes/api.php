<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Mail;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// admin routes
Route::post('admin/register','App\Http\Controllers\AdminController@register');
Route::post('admin/login','App\Http\Controllers\AdminController@login');
Route::post('admin/logout','App\Http\Controllers\AdminController@logout');

// user routes
Route::post('user/register','App\Http\Controllers\UserAuthController@register');
//Route::post('user/login','App\Http\Controllers\UserAuthController@login')->middleware('assign.guard:admins');
Route::post('user/logout','App\Http\Controllers\UserAuthController@logout');
Route::get('user/get-all-users','App\Http\Controllers\CustomerController@getCustomerByNumber');
Route::get('user/searchBy','App\Http\Controllers\CustomerController@retrieve');
Route::get('user/get-users-after-date','App\Http\Controllers\CustomerController@getUsersAfterDate');

Route::get("send-mail", function () {
    $details = [
        "title" => "Mail from ali@test.com",
        "body" => "This is for testing email using mailgun"
    ];

    \Illuminate\Support\Facades\Mail::to("ali-meng@outlook.com")->send(new App\Mail\email($details));

    dd("Email is sent");

});


Route::group(['prefix' => 'admin', 'middleware' => ['assign.guard:admins','jwt.auth']],function ()
{
    Route::get('/demo','AdminController@demo');
    Route::post('user/login','App\Http\Controllers\UserAuthController@login');


});


Route::group(['prefix' => 'user','middleware' => ['assign.guard:users','jwt.auth']],function ()
{
    Route::get('/demo','UserController@demo');
});
