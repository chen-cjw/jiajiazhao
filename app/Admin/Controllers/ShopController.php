<?php

namespace App\Admin\Controllers;

use App\Model\AbbrCategory;
use App\Model\Shop;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
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
        $grid->model()->orderBy('id','desc')->where('paid_at','!=',null);

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'))->display(function ($userId) {
            return User::where('id',$userId)->value('nickname');
        });
        $grid->column('two_abbr0', __('行业'))->display(function () {
            $query = AbbrCategory::where('id',$this->two_abbr0)->value('abbr');
            if($this->two_abbr1) {
                $query = $query.'/'.AbbrCategory::where('id',$this->two_abbr1)->value('abbr');
            }
            if ($this->two_abbr2) {
                $query = $query.'/'.AbbrCategory::where('id',$this->two_abbr2)->value('abbr');
            }
            return $query;
        });
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
        $grid->column('merchant_introduction', __('Merchant introduction'))->display(function ($content) {
            return Str::limit($content, 50, '....');
        });
        $grid->column('sort', __('Sort'))->sortable()->editable();
        $grid->column('view', __('View'))->sortable();
        $grid->column('is_top', __('Is top'))->using([1 => '是', 0 => '否']);
        $grid->column('is_accept', __('Is accept'))->using([1 => '是', 0 => '否']);
        $grid->column('type', __('Type'))->display(function ($type) {
            return $type == 'one' ? '第一部分':'第二部分';
        });
        $grid->column('comment_count', __('Comment count'))->sortable();
        $grid->column('good_comment_count', __('Good comment count'))->sortable();

//        $grid->column('no', __('No'));
        $grid->column('amount', __('Amount'))->sortable();
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

            $filter->column(1/2, function ($filter) {
                $filter->like('name', '店铺名');
//                $filter->like('contact_phone',  __('Contact phone'));
                $filter->like('no',  __('No'));
                $filter->like('payment_no',  __('Payment no'));
            });
            $filter->column(1/2, function ($filter) {
                $filter->equal('is_top', __('Is top'))->select([true=>'是',false=>'否']);
                $filter->equal('is_accept', __('Is accept'))->select([true=>'是',false=>'否']);
                $filter->equal('type', __('Type'))->select(['one'=>'第一部分','two'=>'第二部分']);
            });

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
//        $show->field('one_abbr0', __('One abbr0'));
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
        $show->field('logo', __('Logo'));
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

//        $form->number('one_abbr0', __('One abbr0'));
//        $form->number('one_abbr1', __('One abbr1'));
//        $form->number('one_abbr2', __('One abbr2'));
//        $form->text('two_abbr0', __('Two abbr0'));
//        $form->text('two_abbr1', __('Two abbr1'));
//        $form->text('two_abbr2', __('Two abbr2'));
        $form->text('name', __('店铺名'));
        $form->decimal('lng', __('Lng'));
        $form->decimal('lat', __('Lat'));
        $form->text('area', __('Area'));
        $form->text('detailed_address', __('Detailed address'));
        $form->text('contact_phone', __('Contact phone'));
        $form->text('wechat', __('Wechat'));
//        $form->image('logo', __('Logo'));
        $form->text('service_price', __('Service price'));
        $form->text('merchant_introduction', __('Merchant introduction'));
        $form->number('sort', __('Sort'));
        $form->number('view', __('View'));
        $form->switch('is_top', __('Is top'));
        $form->switch('is_accept', __('Is accept'));
        $form->text('type', __('Type'));
        $form->number('comment_count', __('Comment count'));
        $form->number('good_comment_count', __('Good comment count'));
        $form->number('user_id', __('User id'));
        $form->text('no', __('No'));
        $form->decimal('amount', __('Amount'));
        $form->decimal('top_amount', __('Top amount'));
        $form->number('platform_licensing', __('Platform licensing'));
        $form->datetime('paid_at', __('Paid at'))->default(date('Y-m-d H:i:s'));
        $form->text('payment_method', __('Payment method'))->default('wechat');
        $form->text('payment_no', __('Payment no'));
        $form->datetime('due_date', __('Due date'))->default(date('Y-m-d H:i:s'));
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
        return $form;
    }
}
