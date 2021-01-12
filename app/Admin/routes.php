<?php

use Illuminate\Routing\Router;
use App\Admin\Controllers;
Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');
    $router->resource('users', 'UserController');
    $router->resource('abbr_category', 'AbbrCategoryController');// 行业分类
    $router->resource('banners', 'BannerController');// 轮播图
    $router->resource('card_category', 'CardCategoryController');//便民信息的分类
    $router->resource('carpooling', 'CarpoolingController');// 拼车协议
    $router->resource('information', 'ConvenientInformationController');// 便民信息
    $router->resource('driver_certifications', 'DriverCertificationController');// 司机身份认证
    $router->resource('local_carpooling', 'LocalCarpoolingController');// 本地拼车
    $router->resource('notices', 'NoticeController');// 公告
    $router->resource('settings', 'SettingController');// 设置
    $router->resource('settlement_agreements', 'SettlementAgreementController');// 入住协议
    $router->resource('shops', 'ShopController');// 商户
    $router->resource('suggestions', 'SuggestionsController'); // 投诉建议
//    $router->resource('users', \App\Admin\Controllers\UserController::class);

});
