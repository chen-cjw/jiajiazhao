<?php

namespace App\Admin\Controllers;

use App\Model\PaymentOrder;
use App\Admin\Actions\Post\PaymentOrder as Pay;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PaymentOrderController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '提现到零钱';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PaymentOrder());
        $grid->model()->orderBy('id','desc');//->whereNull('parent_id')

        $grid->column('id', __('Id'));
//        $grid->column('user_id', __('User id'));
        $grid->column('user_id', __('User id'))->display(function ($userId) {
            return User::where('id',$userId)->value('nickname');
        });
        $grid->column('order_number', __('Order number'));
        $grid->column('amount', __('Amount'));
        $grid->column('payment_no', __('Payment no'));
        $grid->column('status', __('Status'))->using([1 => '付款成功', 2 => '待付款',3 => '付款失败']);//     // 提现到零钱 1付款成功,2待付款,3付款失败
//        $grid->column('intro', __('Intro'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
            $filter->like('payment_no', __('Payment no'));
            $filter->equal('status', __('Status'))->select([1 => '付款成功', 2 => '待付款',3 => '付款失败']);
            $filter->where(function ($query) {
                $input = $this->input;
                $query->whereHas('user', function ($query) use ($input) {
                    $query->where('nickname', 'like', "%$input%");
                });
            }, '用户名');
        });

        $grid->actions(function ($actions) {
            $actions->add(new Pay());
            $actions->disableDelete();
//            $actions->disableEdit();
        });
        $grid->disableCreateButton();
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
        $show = new Show(PaymentOrder::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('order_number', __('Order number'));
        $show->field('amount', __('Amount'));
        $show->field('payment_no', __('Payment no'));
        $show->field('status', __('Status'));
//        $show->field('intro', __('Intro'));
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
        $form = new Form(new PaymentOrder());

//        $form->text('user_id', __('User id'));
//        $form->text('order_number', __('Order number'));
//        $form->number('amount', __('Amount'));
//        $form->text('payment_no', __('Payment no'));
        $form->select('status', __('Status'))->options([1 => '付款成功', 2 => '待付款',3 => '付款失败']);
//        $form->textarea('intro', __('Intro'));

        return $form;
    }
}
