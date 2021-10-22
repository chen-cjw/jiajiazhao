<?php

namespace App\Admin\Controllers;

use App\Admin\Filters\TimestampBetween;
use App\Model\AbbrCategory;
use App\Model\AdminShop;
use App\Model\AdminUser;
use App\Shop;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商铺';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Shop());

        $grid->model()->orderBy('paid_at','desc');//->where('paid_at','!=',null);
        if (!Admin::user()->can('Administrator')) {
            //
            $shopID = AdminShop::where('admin_id',Admin::user()->id)->get('shop_id');
            $grid->model()->whereIn('id',$shopID);
        }
        $grid->column('id', __('Id'));

        $grid->column('admin_user', __('管理员'))->display(function ($adminUser) {
            $adminShop = AdminShop::where('shop_id',$this->id)->value('admin_id');
            return $adminShop ? AdminUser::where('id',$adminShop)->value('username') : "";
        });

        $grid->column('user_id', __('User id'))->display(function ($userId) {
            return User::where('id',$userId)->value('nickname');
        });
        $grid->column('user_parent', __('邀请人'))->display(function ($userId) {
            $thisUser=User::where('id',$this->user_id)->value('parent_id');
                return User::where('id',$thisUser)->value('nickname');
        });

        $grid->column('one_abbr0', __('一级分类'));
        $grid->column('two_abbr0', __('二级分类'));//->display(function () {
//            $query = AbbrCategory::where('id',$this->two_abbr0)->value('abbr');
//            if($this->two_abbr1) {
//                $query = $query.'/'.AbbrCategory::where('id',$this->two_abbr1)->value('abbr');
//            }
//            if ($this->two_abbr2) {
//                $query = $query.'/'.AbbrCategory::where('id',$this->two_abbr2)->value('abbr');
//            }
//            return $query;
//        });
//        $grid->column('two_abbr1', __('Two abbr1'));
//        $grid->column('two_abbr2', __('Two abbr2'));
        $grid->column('name', __('店铺名'));
//        $grid->column('lng', __('Lng'));
//        $grid->column('lat', __('Lat'));
        $grid->column('area', __('Area'));
//        $grid->column('detailed_address', __('Detailed address'));
        $grid->column('contact_phone', __('Contact phone'));
//        $grid->column('wechat', __('Wechat'));

        $grid->column('logo', __('Logo'))->image('',50,50);

        $grid->column('service_price', __('Service price'))->image('',25,25);
//        $grid->column('merchant_introduction', __('Merchant introduction'))->display(function ($content) {
//            return Str::limit($content, 50, '....');
//        });
        $grid->column('sort', __('Sort'))->sortable()->editable();
        $grid->column('is_top', __('Is top'))->using([1 => '是', 0 => '否']);
        $grid->column('is_accept', __('Is accept'))->using([1 => '是', 0 => '否']);
//        $grid->column('type', __('Type'))->display(function ($type) {
//            return $type == 'one' ? '第一部分':'第二部分';
//        });
        $grid->column('comment_count', __('Comment count'))->sortable();
        $grid->column('good_comment_count', __('Good comment count'))->sortable();

//        $grid->column('no', __('No'));
        $grid->column('amount', __('Amount'))->sortable();
        $grid->column('view', __('View'))->sortable()->editable();

        $grid->column('top_amount', __('Top amount'))->sortable();
        $grid->column('platform_licensing', __('Platform licensing'));
        $grid->column('paid_at', __('Paid at'))->sortable();
//        $grid->column('payment_method', __('Payment method'));
        $grid->column('payment_no', __('Payment no'));
        $grid->column('due_date', __('Due date'));
//        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
//            $filter->where(function ($query) {
//                return $query->whereHas('adminShops', function ($query) {
//                    return $query->whereHas('adminUser', function ($query) {
//                        return $query->where('username', 'like',"%$this->input%");
//                    });
//                });
//            }, '管理员')->select((array_merge(['超级管理员'=>'超级管理员'],json_decode(AdminUser::pluck('username','username'),true))));

            $filter->where(function ($query) {

                $input = $this->input;

//                dd($input);
                if ($input == '超级管理员') {
                    $query->where('payment_no','like','42'.'%')->orWhere('payment_no',null);
//                    $query->whereHas('AdminUser',function ($query) use ($input) {
//                        $query->where('id',$input);
//                    });
//
//                    $query->whereNotIn('id',AdminShop::pluck('shop_id'));
                }else {
//                    $adminUserId = AdminUser::where('username',$input)->value('id');
//
//                    $adminShopId = AdminShop::where('admin_id',$adminUserId)->pluck('shop_id');
//                    $query->whereIn('id',$adminShopId);
                        return $query->whereHas('adminShops', function ($query) {
                            return $query->whereHas('adminUser', function ($query) {
                                return $query->where('username',$this->input);
                            });
                        });
                }

                }, '管理员')->select((array_merge(['超级管理员'=>'超级管理员'],json_decode(AdminUser::pluck('username','username'),true))));
            $filter->where(function ($query) {

                return $query->whereHas('user', function ($query) {
                    return $query->whereHas('parentUser', function ($query) {
                        return $query->where('phone',$this->input);
                    });
                });

            }, '邀请人(输入手机号码)');//->select(json_decode(AdminUser::pluck('username','username'),true));


            $filter->where(function ($query) {
                $input = $this->input;
                $query->whereHas('user', function ($query) use ($input) {
                    $query->where('nickname', 'like', "%$input%");
                });
            }, '用户名');
//            $filter->between('due_date', __('Due date'))->datetime();
//            $filter->use(new TimestampBetween('due_date', __('Due date')))->datetime();
            $filter->column(1/2, function ($filter) {
                $filter->like('contact_phone', __('Contact phone'));
                $filter->like('name', '店铺名');
//                $filter->like('contact_phone',  __('Contact phone'));
                $filter->like('no',  __('No'));
                $filter->like('payment_no',  __('Payment no'));

            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('is_top', __('Is top'))->select([true=>'是',false=>'否']);
                $filter->equal('is_accept', __('Is accept'))->select([true=>'是',false=>'否']);
                $filter->equal('type', __('Type'))->select(['one'=>'第一部分','two'=>'第二部分']);
                $filter->like('area', '地址');
                $filter->lt('due_date', '过期时间查询')->datetime();
                $filter->between('paid_at', '支付时间查询')->datetime();

            });

        });
        if (!Admin::user()->can('Administrator')) {
            $grid->disableExport();
        }
        $grid->actions(function ($actions) {

            // 没有`delete-image`权限的角色不显示删除按钮
            if (!Admin::user()->can('Administrator')) {
                $actions->disableDelete();
            }
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Shop::findOrFail($id));

        $show->field('id', __('Id'));
//        $show->field('one_abbr0', __('One abbr0'),function ($one_abbr0) {
//            return AbbrCategory::where('id',$one_abbr0)->value('abbr');
//        });
//        $show->field('two_abbr0', __('One abbr0'),function ($one_abbr0) {
//            return AbbrCategory::where('id',$one_abbr0)->value('abbr');
//        });
//        $show->field('one_abbr1', __('One abbr1'));
//        $show->field('one_abbr2', __('One abbr2'));
//        $show->field('two_abbr0', __('Two abbr0'));
//        $show->field('two_abbr1', __('Two abbr1'));
//        $show->field('two_abbr2', __('Two abbr2'));
        $show->field('name', __('店铺名'));
        $show->field('lng', __('Lng'));
        $show->field('lat', __('Lat'));
        $show->field('area', __('Area'));
        $show->field('detailed_address', __('Detailed address'));
        $show->field('contact_phone', __('Contact phone'));
        $show->field('wechat', __('Wechat'));
        $show->field('logo', __('Logo'))->image('',300,  200);
        $show->field('service_price', __('Service price'));
        $show->field('merchant_introduction', __('Merchant introduction'));
        $show->field('sort', __('Sort'));
        $show->field('view', __('View'));
        $show->field('is_top', __('Is top'));
        $show->field('is_accept', __('Is accept'));
        $show->field('type', __('Type'));
        $show->field('comment_count', __('Comment count'));
        $show->field('good_comment_count', __('Good comment count'));
//        $show->field('user_id', __('User id'));
        $show->field('no', __('No'));
        $show->field('amount', __('Amount'));
        $show->field('top_amount', __('Top amount'));
        $show->field('platform_licensing', __('Platform licensing'));
        $show->field('paid_at', __('Paid at'));
        $show->field('payment_method', __('Payment method'));
        $show->field('payment_no', __('Payment no'));
        $show->field('due_date', __('Due date'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->panel()
            ->tools(function ($tools) {
                $tools->disableDelete();
            });;
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Shop());
        if(request()->route('shop')) {
            $form->display('one_abbr0', __('一级分类'));
            $form->display('two_abbr0', __('二级分类'));

        }else {
            $form->select('one_abbr0', __('一级分类'))->options(AbbrCategory::where('parent_id',null)->pluck('abbr','id'))->load(
                'two_abbr0','/abbr'
            // AbbrCategory::where('parent_id',request('one_abbr0'))->get(['abbr','id'])
            );
            $form->select('two_abbr0', __('二级分类'))->options(AbbrCategory::where('parent_id','!=',null)->pluck('abbr','id'));

        }

//        $form->number('one_abbr1', __('One abbr1'));
//        $form->number('one_abbr2', __('One abbr2'));
//        $form->text('two_abbr0', __('Two abbr0'));
//        $form->text('two_abbr1', __('Two abbr1'));
//        $form->text('two_abbr2', __('Two abbr2'));
        $form->text('name', __('店铺名'))->rules('required');
        $form->decimal('lng', __('Lat'))->rules('required');
//        $form->decimal('lng', __('Lng'))->rules('required');
//        $form->decimal('lat', __('Lat'))->rules('required');
        $form->decimal('lat', __('Lng'))->rules('required');
        $form->text('area', __('Area'))->rules('required');
//        $form->text('detailed_address', __('Detailed address'));
        $form->text('contact_phone', __('Contact phone'))->rules('required');
//        $form->text('wechat', __('Wechat'));
        if(request()->route('shop')) {
            $form->multipleImage('logo', __('商铺资料'))->disable();//->removable();

        };

//        $form->image('store_logo', __('门店照/个人照'))->rules('required');//商户认证必填


        if(request()->route('shop')) {
            $form->image('store_logo', __('门店照/个人照'));//商户认证必填

            $form->image('with_iD_card', __('持身份证照'));//持身份证照必传
            $form->image('service_price', __('Service price'));

            $form->text('merchant_introduction', __('Merchant introduction'));


            if (Admin::user()->can('Administrator')) {
                $form->display('paid_at', __('Paid at'))->default(date('Y-m-d H:i:s'));
                $form->display('payment_method', __('Payment method'))->default('wechat');
                $form->display('payment_no', __('Payment no'))->default('jp'.time().rand(1111,9999));
                $form->number('sort', __('Sort'))->default(0);
//                $form->number('view', __('View'))->default(1);
                $form->switch('is_top', __('Is top'));
                $form->switch('is_accept', __('Is accept'))->default(1);
//            $form->text('type', __('Type'))->default('one');
                $form->number('comment_count', __('Comment count'))->default(0);
                $form->number('good_comment_count', __('Good comment count'))->default(0);
                $form->display('user_id', __('User id'))->default(1);
                $form->display('no', __('No'))->default('j'.time().rand(1,10).rand(1,10).rand(1,10));
                $form->display('amount', __('Amount'))->default(299);
                $form->display('top_amount', __('Top amount'))->default(0);
                $form->display('platform_licensing', __('Platform licensing'))->default(0);
            }


        }else {
            $form->image('store_logo', __('门店照/个人照'))->rules('required');//商户认证必填
            $form->image('with_iD_card', __('持身份证照'))->rules('required');//持身份证照必传

            $form->hidden('logo', __('Logo'));

            $form->hidden('merchant_introduction', __('Merchant introduction'));

            $form->hidden('is_top', __('Is top'))->default(0);
            $form->hidden('is_accept', __('Is accept'))->default(1);
            $form->hidden('comment_count', __('Comment count'))->default(0);
            $form->hidden('good_comment_count', __('Good comment count'))->default(0);
            $form->hidden('user_id', __('User id'))->default(1);
            $form->hidden('no', __('No'))->default('j' . time());
            $form->hidden('amount', __('Amount'))->default(299);
            $form->hidden('top_amount', __('Top amount'))->default(0);
            $form->hidden('platform_licensing', __('Platform licensing'))->default(0);
            $form->hidden('paid_at', __('Paid at'))->default(date('Y-m-d H:i:s'));
            $form->hidden('payment_method', __('Payment method'))->default('wechat');
            $form->hidden('payment_no', __('Payment no'))->default('jp' . time().rand(1,10).rand(1,10).rand(1,10));
//            $form->datetime('due_date', __('Due date'))->default(date('Y-m-d H:i:s', strtotime("+1year", time())));
            $form->hidden('sort', __('Sort'))->default(0);
//            $form->hidden('view', __('View'))->default(1);
        }
        $form->select('type', __('Type'))->default('one')->options(['one'=>'第一部份','two'=>'第二部分']);
        $form->datetime('due_date', __('Due date'))->default(date('Y-m-d H:i:s',strtotime("+1year",time())));
        $form->number('view', __('人气'))->default(rand(10,500));


        if (request()->isMethod('PUT')) {
            $form->saving(function (Form $form) {

                if (!Admin::user()->can('Administrator')) {

                        // 不是超级管理员，就判断
                    if(AdminShop::where('shop_id',$form->model()->id)->value('admin_id') == Admin::user()->id ) {

                    }else {
                        throw new \Exception('请不要随意修改数据！');
                    }


                }else {

                }
//            dd($form) ;
                $data = [];
                if(request('store_logo')) {
                    $file = request('store_logo');
                    $path = \Storage::disk('public')->putFile(date('Ymd') , $file);
                    // 如果 image 字段本身就已经是完整的 url 就直接返回
                    if (Str::startsWith($path, ['http://', 'https://'])) {
                        return $path;
                    }
                    $store_logo = \Storage::disk('public')->url($path);
                    $data['logo']['store_logo']=$store_logo;//request('store_logo');
                }else {
                    $data['logo']['store_logo']=$form->model()->logo['store_logo'];//request('with_iD_card');
//                $data['logo']['store_logo']=$form->model()->logo['with_iD_card'];//request('with_iD_card');
                }
                if(request('with_iD_card')) {
                    $file = request('with_iD_card');
                    $path = \Storage::disk('public')->putFile(date('Ymd') , $file);
                    // 如果 image 字段本身就已经是完整的 url 就直接返回
                    if (Str::startsWith($path, ['http://', 'https://'])) {
                        return $path;
                    }
                    $with_iD_card = \Storage::disk('public')->url($path);
                    $data['logo']['with_iD_card']=$with_iD_card;//request('with_iD_card');
                }else {
                    $data['logo']['with_iD_card']=$form->model()->logo['with_iD_card'];//request('with_iD_card');
                }
                if (isset($form->model()->logo['business_license'])) {
                    $data['logo']['business_license']=$form->model()->logo['business_license'];//request('with_iD_card');
                }
                if (isset($form->model()->logo['professional_qualification'])) {
                    $data['logo']['professional_qualification']=$form->model()->logo['professional_qualification'];//request('with_iD_card');
                }

//            $form->logo = json_encode($data['logo']);
                $data['logo'] = json_encode($data['logo']);
                //            dd($form);
//            $form->logo = ($data['logo']);

//            dd($form);
////            dd($form);
//            if (request()->isMethod('PUT')) {
                Shop::where('id',$form->model()->id)->update($data);
//            }else {
////                dd(($data['logo']));
//                $form->logo = ($data['logo']);
//            }


            });

        }else {
            $form->saving(function (Form $form) {
                $data = [];

                $file = request('store_logo');
                $path = \Storage::disk('public')->putFile(date('Ymd') , $file);
                // 如果 image 字段本身就已经是完整的 url 就直接返回
                if (Str::startsWith($path, ['http://', 'https://'])) {
                    return $path;
                }
                $store_logo = \Storage::disk('public')->url($path);
                $data['logo']['store_logo']=$store_logo;

                $file = request('with_iD_card');
                $path = \Storage::disk('public')->putFile(date('Ymd') , $file);
                // 如果 image 字段本身就已经是完整的 url 就直接返回
                if (Str::startsWith($path, ['http://', 'https://'])) {
                    return $path;
                }
                $with_iD_card = \Storage::disk('public')->url($path);
                $data['logo']['with_iD_card']=$with_iD_card;//request('with_iD_card');
                $form->logo = json_encode($data['logo']);

            });
        }


        $form->footer(function ($footer) {

            // 去掉`重置`按钮
            $footer->disableReset();
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();


        });
        $form->tools(function (Form\Tools $tools) {

            // 去掉`删除`按钮
            $tools->disableDelete();

        });

        $form->ignore(['store_logo', 'with_iD_card']);
        // -- 创建之后添加关联
        if (request()->isMethod('POST')) {

            $form->saved(function (Form $form) {
                AdminShop::create([
                    'admin_id' => Auth::guard('admin')->id(),
                    'shop_id' => $form->model()->id,
                ]);
            });
        };
        return $form;
    }
}
