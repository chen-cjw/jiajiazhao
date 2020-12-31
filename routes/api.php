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

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
    'middleware' => ['serializer:array']
], function ($api) {

    $api->get('auth','AuthController@index')->name('api.auth.index');
//    $api->post('auth','AuthController@store')->name('api.auth.store');

    // 获取openid
    $api->post('/auth/ml_openid_store','AuthController@mlOpenidStore')->name('api.auth.mlOpenidStore');
    // 获取手机号
    $api->post('/auth/phone_store','AuthController@phoneStore')->name('api.auth.phone_store');

    // 个人信息
    $api->get('/meShow','AuthController@meShow')->name('api.auth.meShow');

    // 退出
    $api->delete('/auth/current', 'AuthController@destroy')->name('api.auth.destroy');

    // 公告
    $api->get('/notice','NoticeController@index')->name('api.auth.index');
    $api->get('/notice/{id}','NoticeController@show')->name('api.auth.show');


    // 本地拼车
    $api->get('/local_carpooling','LocalCarpoolingController@index')->name('api.local_carpooling.index');

    // 必须登陆以后才有的操作
    $api->group(['middleware' => ['auth:api']], function ($api) {

        $api->post('/local_carpooling', 'LocalCarpoolingController@store')->name('api.local_carpooling.store'); // 发布
        $api->put('/local_carpooling/{id}', 'LocalCarpoolingController@update')->name('api.local_carpooling.update'); // 确认发车

        $api->get('/driver_certification', 'DriverCertificationController@index')->name('api.driver_certification.index'); // 查看认证
        $api->post('/driver_certification', 'DriverCertificationController@store')->name('api.driver_certification.store'); // 认证


        $api->get('/convenient_information', 'ConvenientInformationController@index')->name('api.convenient_information.index'); // 认证
        $api->post('/convenient_information', 'ConvenientInformationController@store')->name('api.convenient_information.index'); // 认证
        $api->get('/convenient_information/{id}', 'ConvenientInformationController@show')->name('api.convenient_information.index'); // 认证

        // 入住
        $api->post('/shop', 'ShopController@store')->name('api.shop.store'); // 认证
        $api->post('/shop_upload_img', 'ShopController@uploadImg')->name('api.shop.uploadImg'); // 单图片上传
        // 分类
        $api->get('/abbr_category', 'AbbrCategoryController@index')->name('api.abbr_category.index');

    });
    $api->get('/shop', 'ShopController@index')->name('api.shop.index'); // 认证

    $api->get('/card_category', 'CardCategoryController@index')->name('api.card_category.index'); // 认证



});
