<?php

namespace App\Admin\Controllers;

use App\Model\Withdrawal;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WithdrawalController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '提现记录';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Withdrawal());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'))->display(function ($userId) {
            return User::where('id',$userId)->value('nickname');
        });
        $grid->column('user_phone', __('手机号'))->display(function () {
            return User::where('id',$this->user_id)->value('phone');
        });
        $grid->column('amount', __('Amount'));
        $grid->column('name', __('姓名'));
        $grid->column('bank_of_deposit', __('Bank of deposit'));
        $grid->column('bank_card_number', __('Bank card number'));
        $grid->column('image', __('Image'))->image('',50,50);
        $grid->column('is_accept', __('已打款'))->using([1 => '是', 0 => '否']);

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
        $show = new Show(Withdrawal::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('amount', __('Amount'));
        $show->field('name', __('姓名'));
        $show->field('bank_of_deposit', __('Bank of deposit'));
        $show->field('bank_card_number', __('Bank card number'));
        $show->field('image', __('Image'))->image('',50,50);
        $show->field('is_accept', __('已打款'));
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
        $form = new Form(new Withdrawal());

        $form->number('user_id', __('User id'));
        $form->decimal('amount', __('Amount'));
        $form->text('name', __('姓名'));
        $form->text('bank_of_deposit', __('Bank of deposit'));
        $form->text('bank_card_number', __('Bank card number'));
        $form->image('image', __('Image'));
//        $form->e('is_accept', __('已打款'));
        $form->select('is_accept', __('已打款'))->options(['0' => '第一部分广告', '1' => '第二部分广告']);

        return $form;
    }
}
