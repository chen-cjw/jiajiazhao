<?php

namespace App\Admin\Controllers\Shop;

use App\Model\Shop\OwnCouponCode;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OwnCouponCodesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '优惠券管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OwnCouponCode());


        $grid->model()->orderBy('created_at', 'desc');
        $grid->id('ID')->sortable();
        $grid->name('名称');
        $grid->code('优惠码');
        $grid->description('描述');
        $grid->column('usage', '用量')->display(function ($value) {
            return "{$this->used} / {$this->total}";
        });
        $grid->enabled('是否启用')->display(function ($value) {
            return $value ? '是' : '否';
        });
        $grid->created_at('创建时间');
        $grid->actions(function ($actions) {
            $actions->disableView();
        });


        return $grid;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OwnCouponCode());

        $form->display('id', 'ID');
        $form->text('name', '名称')->rules('required');
        $form->text('code', '优惠码')->rules(function($form) {
            // 如果 $form->model()->id 不为空，代表是编辑操作
            if ($id = $form->model()->id) {
                return 'nullable|unique:own_coupon_codes,code,'.$id.',id';
            } else {
                return 'nullable|unique:own_coupon_codes';
            }
        });
        $form->radio('type', '类型')->options(OwnCouponCode::$typeMap)->rules('required')->default(OwnCouponCode::TYPE_FIXED);
        $form->text('value', '折扣')->rules(function ($form) {
            if (request()->input('type') === OwnCouponCode::TYPE_PERCENT) {
                // 如果选择了百分比折扣类型，那么折扣范围只能是 1 ~ 99
                return 'required|numeric|between:1,99';
            } else {
                // 否则只要大等于 0.01 即可
                return 'required|numeric|min:0.01';
            }
        });
        $form->text('total', '总量')->rules('required|numeric|min:0');
        $form->text('min_amount', '最低金额')->rules('required|numeric|min:0');
        $form->datetime('not_before', '开始时间');
        $form->datetime('not_after', '结束时间');
        $form->radio('enabled', '启用')->options(['1' => '是', '0' => '否']);

        $form->saving(function (Form $form) {
            if (!$form->code) {
                $form->code = OwnCouponCode::findAvailableCode();
            }
        });
        return $form;
    }
}
