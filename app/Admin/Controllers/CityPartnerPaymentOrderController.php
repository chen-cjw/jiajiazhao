<?php

namespace App\Admin\Controllers;

use App\Model\CityPartnerPaymentOrder;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CityPartnerPaymentOrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '城市合伙人提现';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CityPartnerPaymentOrder());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'))->display(function ($userId) {
            return User::where('id',$userId)->value('nickname');
        });
        $grid->column('order_number', __('Order number'));
        $grid->column('amount', __('Amount'));
        $grid->column('payment_no', __('Payment no'));
        $grid->column('status', __('Status'));
        $grid->column('intro', __('Intro'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(CityPartnerPaymentOrder::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('order_number', __('Order number'));
        $show->field('amount', __('Amount'));
        $show->field('payment_no', __('Payment no'));
        $show->field('status', __('Status'));
        $show->field('intro', __('Intro'));
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
        $form = new Form(new CityPartnerPaymentOrder());

        $form->text('user_id', __('User id'));
        $form->text('order_number', __('Order number'));
        $form->number('amount', __('Amount'));
        $form->text('payment_no', __('Payment no'));
        $form->switch('status', __('Status'))->default(2);
        $form->textarea('intro', __('Intro'));

        return $form;
    }
}
