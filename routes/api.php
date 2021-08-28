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
    // 四大收益
    $api->get('city_partner_profit', 'CityPartnerProfitController@index')->name('api.city_partner_profit.index');
    // 城市合伙人常见问题
    $api->get('city_partner_question', 'CityPartnerQuestionController@index')->name('api.city_partner_question.index');
    $api->get('city_partner_agreement', 'CityPartnerAgreementController@index')->name('api.city_partner_question.index');
    $api->get('city_partner_payment_order_all', 'CityPartnerPaymentOrderController@allIndex')->name('api.city_partner_payment_order.allIndex');

    $api->any('/partner_wechat_notify', 'CityPartnerController@wechatNotify')->name('api.city_partner.wechatNotify'); // 合伙人支付回调


    // 合伙人
    $api->group(['middleware' => ['auth:api']], function ($api) {

        $api->get('information_history', 'CityPartnerController@informationHistory')->name('api.city_partner.informationHistory');// 发帖抽成明细
        $api->get('shop_history', 'CityPartnerController@shopHistory')->name('api.city_partner.shopHistory');// 商户抽成明细
        $api->get('city_partner', 'CityPartnerController@index')->name('api.city_partner.index');// 城市合伙人信息
        $api->post('city_partner', 'CityPartnerController@store')->name('api.city_partner.store');// 入住
        $api->get('/city_partner/pay_by_wechat/{id}', 'CityPartnerController@payByWechat')->name('api.city_partner.payByWechat'); // 唤起支付

        $api->post('city_partner/update_partner', 'CityPartnerController@updatePartner')->name('api.city_partner.update_partner');// 入住
       // 提现记录
        $api->get('city_partner_payment_order', 'CityPartnerPaymentOrderController@index')->name('api.city_partner_payment_order.index');
        $api->post('city_partner_payment_order', 'CityPartnerPaymentOrderController@store')->name('api.city_partner_payment_order.store');

        // 商户抽成
        $api->get('shop_index', 'CityPartnerController@shopIndex')->name('api.city_partner.shop_index');

        // 发帖抽成 -- 这个不是合伙人的是团长的
        $api->get('information_index', 'CityPartnerController@informationIndex')->name('api.city_partner.information_index');// 入住

        // 交易流水抽成 -- todo暂时未开发

        // 商铺置顶
        $api->post('shop_top/{id}', 'ShopTopController@store')->name('api.shop_top.store');
    });

    //

    $api->get('partner_banner', 'PartnerBannerController@index')->name('api.partner_banner.index');


    $api->group(['middleware' => ['auth:api']], function ($api) {
        // 提现
        $api->post('pay', 'PayController@payment')->name('api.pay.payment');
        // 提现到零钱
        $api->get('/wechat/pay', 'PayController@index')->name('api.pay.index');
        $api->post('/wechat/pay', 'PayController@store')->name('api.pay.store');
    });
    $api->post('make_hai_bao','MakeQrCodeController@makeHaiBao')->name('api.auth.makeHaiBao');
    $api->get('auth','AuthController@index')->name('api.auth.index');
    $api->post('test','AuthController@createTestUser')->name('api.auth.createTestUser');
//    $api->group(['middleware' => ['wechat.oauth']], function ($api) {
        // 获取openid
        $api->any('/auth/ml_openid_store', 'AuthController@mlOpenidStore')->name('api.auth.mlOpenidStore');
//    });


    // 退出
    $api->delete('/auth/current', 'AuthController@destroy')->name('api.auth.destroy');

    // 公告
    $api->get('/notice','NoticeController@index')->name('api.auth.index');
    $api->get('/notice/{id}','NoticeController@show')->name('api.auth.show');

    // 帖子详情下轮播图
    $api->get('/banner_information_show','BannerInformationShowController@index')->name('api.banner_information_show.index');
    // 个人中心轮播图
    $api->get('/personal/banner','PersonalController@banner')->name('api.personal.banner');
    $api->get('/banner_local','BannerLocalController@index')->name('api.banner_local.index');

    // 发帖轮播图 BannerPostInformationController
    $api->get('/banner_post_shop','BannerPostShopController@index')->name('api.banner_post_shop.index');
    $api->get('/banner_post_information','BannerPostInformationController@index')->name('api.banner_post_information.index');
    $api->get('/banner_shop_show','BannerShopShowController@index')->name('api.Banner_shop_show.index');

    // 商户入住协议
    $api->get('/merchant_entering_agreement','MerchantEnteringAgreementController@index')->name('api.personal.index');
    $api->get('/merchant_privacy_agreement','MerchantPrivacyAgreementController@index')->name('api.personal.index');// 隐私

    // 本地拼车
    $api->get('/local_carpooling','LocalCarpoolingController@index')->name('api.local_carpooling.index');
    // 回调通知
    $api->any('/wechat_notify', 'LocalCarpoolingController@wechatNotify')->name('api.local_carpooling.wechatNotify'); // 发布
    $api->any('/shop_wechat_notify', 'ShopController@wechatNotify')->name('api.shop_wechat_notify.wechatNotify'); // 发布
    $api->any('/information_wechat_notify', 'ConvenientInformationController@wechatNotify')->name('api.information_wechat_notify.wechatNotify'); // 发布
    $api->any('/information_wechat_notify', 'ConvenientInformationController@wechatNotify')->name('api.information_wechat_notify.wechatNotify'); // 发布
    $api->any('shop_top_wechat_notify', 'ShopTopController@wechatNotify')->name('api.shop_top_wechat_notify.wechatNotify');

    // AbbrCategoryController
    $api->get('/search_two_cate', 'AbbrCategoryController@searchTwoCate')->name('api.abbrCategory.searchTwoCate'); // 发起支付


    $api->group(['middleware' => ['refreshtoken']], function ($api) {
        // refresh
        $api->post('/auth/refresh','AuthController@refresh')->name('api.auth.refresh');

    });
    $api->get('make_back','MakeQrCodeController@makeBack')->name('api.qrcode.makeBack');// 分享


    // makeBack

        $api->post('make_share','MakeQrCodeController@makeShare')->name('api.qrcode.makeShare');// 分享
        $api->post('share_friend','MakeQrCodeController@shareFriend')->name('api.qrcode.shareFriend');// 分享到朋友圈

        // 多图片上传
//        $api->post('upload','ShopController@upload')->name('api.multiUpload.upload');

        // 首页
        $api->get('/index', 'IndexController@index')->name('api.index.index'); // 发起支付


        // 发帖提示
        $api->get('/post_tip', 'PostTipController@index')->name('api.post_tip.index'); // 认证
        $api->get('/post_description', 'PostDescriptionController@index')->name('api.post_description.index'); // 认证


        $api->get('/convenient_information', 'ConvenientInformationController@index')->name('api.convenient_information.index'); // 认证
        //$api->post('/convenient_information', 'ConvenientInformationController@store')->name('api.convenient_information.store'); // 认证



        // 分类
        $api->get('/abbr_category', 'AbbrCategoryController@index')->name('api.abbr_category.index');

        // 我的投诉
        $api->get('/suggestion', 'SuggestionController@index')->name('api.suggestion.index');

        // ShopCommentController
    // 必须登陆以后才有的操作&&手机要授权以后
    $api->group(['middleware' => ['auth:api','user_display']], function ($api) {


        // 获取手机号
        $api->post('/auth/phone_store','AuthController@phoneStore')->name('api.auth.phone_store');

        // 获取用户信息
        $api->post('/auth/user_info', 'AuthController@userInfo')->name('api.auth.userInfo');

        // 个人信息
        $api->get('/meShow','AuthController@meShow')->name('api.auth.meShow');
        // 入住
        $api->get('/shop/{id}', 'ShopController@show')->name('api.shop.show'); // 商户详情

        $api->get('/shop/pay_by_wechat/{id}', 'ShopController@payByWechat')->name('api.shop.payByWechat'); // 唤起支付
        $api->get('/convenient_information/{id}', 'ConvenientInformationController@show')->name('api.convenient_information.show'); // 认证
        // 发布信息唤起支付页面
        $api->get('/convenient_information/pay_by_wechat/{id}', 'ConvenientInformationController@payByWechat')->name('api.convenient_information.payByWechat'); // 发布
        $api->get('/shop_top/pay_by_wechat/{id}', 'ShopTopController@payByWechat')->name('api.shop_top.payByWechat');

        // 要授权手机号
        $api->group(['middleware' => ['phone.verify']], function ($api) {
            $api->group(['middleware' => ['wx_username']], function ($api) {
                $api->post('/auth/update', 'AuthController@update')->name('api.auth.update');
            });
            $api->post('xufei/{id}','ShopController@xufei')->name('api.shop.xufei');

            $api->put('/local_carpooling/{id}', 'LocalCarpoolingController@update')->name('api.local_carpooling.update'); // 确认发车

            $api->get('/pay_by_wechat/{id}', 'LocalCarpoolingController@payByWechat')->name('api.local_carpooling.payByWechat'); // 发起支付



            $api->get('/driver_certification', 'DriverCertificationController@index')->name('api.driver_certification.index'); // 查看认证
            $api->post('/driver_certification', 'DriverCertificationController@store')->name('api.driver_certification.store'); // 认证
            $api->post('/driver_certification/{id}', 'DriverCertificationController@update')->name('api.driver_certification.update'); // 认证


            $api->post('/shop/{id}', 'ShopController@update')->name('api.shop.update'); // 商户编辑
            $api->post('/shop_upload_img', 'ShopController@uploadImg')->name('api.shop.uploadImg'); // 单图片上传

            $api->post('/user_favorite_shop/{id}', 'PersonalController@userFavoriteShop')->name('api.personal.userFavoriteShop'); // 收藏店铺
            $api->post('/shop_del', 'PersonalController@shopDel')->name('api.personal.shop_del'); // 删除收藏

            $api->post('/user_favorite_card/{id}', 'PersonalController@userFavoriteCard')->name('api.personal.userFavoriteCard'); // 收藏帖子
            $api->post('/card_del', 'PersonalController@cardDel')->name('api.personal.cardDel'); // 删除收藏帖子

            // 我的浏览 historyDel
            $api->get('/history', 'PersonalController@historyIndex')->name('api.personal.historyIndex'); // 浏览列表
            $api->post('/history_del', 'PersonalController@historyDel')->name('api.personal.historyDel'); // 浏览管理

            // 我邀请的用户 refUser
            $api->get('/ref_user', 'PersonalController@refUser')->name('api.personal.refUser'); // 我的业绩
            $api->get('/information_commission', 'PersonalController@informationCommission')->name('api.personal.informationCommission'); // 发帖抽成

            // 商铺管理
            $api->get('/shop_manage', 'PersonalController@shopManage')->name('api.personal.shopManage'); // 删除收藏

            // 我发布本地拼车-管理(删除) localCarpoolIndex
            $api->get('/local_carpool_index/', 'PersonalController@localCarpoolIndex')->name('api.personal.localCarpoolIndex');

            //// 我收藏帖子列表
            $api->get('/user_favorite_card_index', 'PersonalController@userFavoriteCardIndex')->name('api.personal.userFavoriteCardIndex');
            // 我收藏商户列表
            $api->get('/user_favorite_shop_index', 'PersonalController@userFavoriteShopIndex')->name('api.personal.userFavoriteShopIndex');
            // 我发布帖子
            $api->post('/user_card_del', 'PersonalController@userCardDel')->name('api.personal.userCardDel');

            $api->get('/user_card', 'PersonalController@userCard')->name('api.personal.userCard');
            // 提现
            $api->get('/user_withdrawal', 'PersonalController@userWithdrawalIndex')->name('api.user_withdrawal.index');
            $api->post('/user_withdrawal', 'PersonalController@userWithdrawal')->name('api.user_withdrawal.store');


            //我拨打的号码
            $api->get('/dialing', 'DialingController@index')->name('api.dialing.index');
            $api->post('/dialing', 'DialingController@store')->name('api.dialing.store');
            $api->post('/dialing/delete', 'DialingController@delete')->name('api.dialing.delete');
            $api->post('/suggestion', 'SuggestionController@store')->name('api.suggestion.store');
            // 添加一个微信验证
            $api->group(['middleware' => ['wx_msg']], function ($api) {
                $api->post('/shop/{id}/shop_comment', 'ShopCommentController@store')->name('api.shop_comment.store'); // 入住
            });

        });

//        $api->group(['middleware' => ['phone.verify']], function ($api) {
        $api->group(['middleware' => ['userInfo.verify','phone.verify']], function ($api) {
            // 评论
            $api->group(['middleware' => ['wx_msg']], function ($api) {
                $api->post('/comment', 'CommentController@store')->name('api.comment.store'); // 认证
            });
            $api->post('/local_carpooling', 'LocalCarpoolingController@store')->name('api.local_carpooling.store'); // 发布拼车

            // 操作之前要获取用户信息
            $api->post('/shop', 'ShopController@store')->name('api.shop.store'); // 入住
            // 我发布本地拼车列表 localCarpool
            $api->post('/local_carpool', 'PersonalController@localCarpool')->name('api.personal.localCarpool');
            $api->group(['middleware' => ['wx_msg','wx_msg_title']], function ($api) {

                $api->post('/convenient_information', 'ConvenientInformationController@store')->name('api.convenient_information.store'); // 认证
            });
        });

    });
    $api->get('/setting', 'SettingController@index')->name('api.setting.index'); // 默认配置
    $api->get('/shop', 'ShopController@index')->name('api.shop.index'); // 商户
    $api->get('/carpooling', 'CarpoolingController@index')->name('api.carpooling.index'); // 拼车协议

    $api->get('/card_category', 'CardCategoryController@index')->name('api.card_category.index'); // 帖子分类
    $api->get('/card_category/{id}/convenient_information', 'CardCategoryController@cardInformation')->name('api.card_category.cardInformation'); // 帖子分类
    // city_partner_payment_order
    $api->get('/search_information', 'ConvenientInformationController@searchInformation')->name('api.search_information.searchInformation'); // 发布
    $api->get('/share_home', 'ShareHomeController@index')->name('api.share_home.index'); // 分享页面
    $api->get('/shop_share_index', 'ShareHomeController@shopShareIndex')->name('api.share_home.shopShareIndex'); // 商铺分享页
    $api->get('/information_share_index', 'ShareHomeController@informationShareIndex')->name('api.share_home.informationShareIndex'); // 信息分享页面
    $api->get('/local_share_index', 'ShareHomeController@LocalShareIndex')->name('api.share_home.LocalShareIndex'); // 拼车
    $api->get('/all_share_index', 'ShareHomeController@AllShareIndex')->name('api.share_home.AllShareIndex'); // 拼车
    $api->get('/city_partner_index', 'ShareHomeController@CityPartnerIndex')->name('api.share_home.CityPartnerIndex'); // 拼车


});
// 商城
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1\Shop',
    'middleware' => ['serializer:array']
], function ($api) {
    $api->get('/own_banner', 'OwnBannerIndexController@index')->name('api.own_banner.index');    // 首页轮播图
    // 收获地址
    $api->group(['middleware' => ['auth:api']], function ($api) {

        $api->get('/own_user_address', 'OwnUserAddressController@index')->name('api.own_banner.index'); // 我的地址
        $api->post('/own_user_address', 'OwnUserAddressController@store')->name('api.own_banner.store');// 添加地址
        $api->put('/own_user_address/{id}', 'OwnUserAddressController@update')->name('api.own_banner.update');// 修改地址
        $api->delete('/own_user_address/{id}', 'OwnUserAddressController@destroy')->name('api.own_banner.destroy');// 删除
        $api->put('/own_user_address/{id}/default', 'OwnUserAddressController@updateDefault')->name('api.own_banner.updateDefault');// 设置默认

        $api->post('own_product/{own_product}/favorite', 'OwnProductController@favor')->name('api.own_products.favor'); // 收藏
        $api->delete('own_product/{own_product}/favorite', 'OwnProductController@disfavor')->name('api.own_products.disfavor'); // 取消收藏

        $api->post('own_cart', 'OwnCartController@add')->name('api.own_cart.add');// 加入购物车
        $api->get('own_cart', 'OwnCartController@index')->name('api.own_cart.index');// 我的购物车
        $api->delete('own_cart/{sku}', 'OwnCartController@remove')->name('cart.remove');// 移除购物车
    });
    $api->get('/own_product', 'OwnProductController@index')->name('api.own_product.index');// 商品
    $api->get('/own_product/favorites', 'OwnProductController@favorites')->name('api.own_product.favorites');//我的收藏
    $api->get('/own_product/{id}', 'OwnProductController@show')->name('api.own_product.show');// 商品详情



});
