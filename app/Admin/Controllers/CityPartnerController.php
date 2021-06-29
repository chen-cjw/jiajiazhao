<?php

namespace App\Admin\Controllers;

use App\Model\CityPartner;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CityPartnerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '城市合伙人';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CityPartner());
        $grid->model()->orderBy('id','desc');//->where('paid_at','!=',null);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('姓名'));
        $grid->column('phone', __('Phone'));
        $grid->column('IDCard', __('IDCard'));
        $grid->column('in_city', __('In city'));
        $grid->column('is_partners', __('Is partners'))->using([0 => '未付款',1 => '已付款', 2=>'审核通过',3=>'运行中']);
        $grid->column('user_id', __('User id'))->display(function ($userId) {
            return User::where('id',$userId)->value('nickname');
        });
        $grid->column('no', __('No'));
        $grid->column('amount', __('Amount'));
        $grid->column('balance', __('Balance'));
        $grid->column('total_balance', __('Total balance'));
        $grid->column('paid_at', __('Paid at'));
//        $grid->column('payment_method', __('Payment method'));
        $grid->column('payment_no', __('Payment no'));
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
        $show = new Show(CityPartner::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('姓名'));
        $show->field('phone', __('Phone'));
        $show->field('IDCard', __('IDCard'));
        $show->field('in_city', __('In city'));
        $show->field('is_partners', __('Is partners'));
        $show->field('user_id', __('User id'));
        $show->field('no', __('No'));
        $show->field('amount', __('Amount'));
        $show->field('balance', __('Balance'));
        $show->field('total_balance', __('Total balance'));
        $show->field('paid_at', __('Paid at'));
        $show->field('payment_method', __('Payment method'));
        $show->field('payment_no', __('Payment no'));
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
        $form = new Form(new CityPartner());

        $form->text('name', __('姓名'));
        $form->mobile('phone', __('Phone'));
        $form->text('IDCard', __('IDCard'));
        $form->text('in_city', __('In city'));
        $form->select('is_partners', __('Is partners'))->options([
            '0'=>'未付款',
            '1'=>'已付款',
            '2'=>'审核通过',
        ]);
        $form->number('user_id', __('User id'));
        $form->text('no', __('No'));
        $form->decimal('amount', __('Amount'));
        $form->decimal('balance', __('Balance'))->default(0.000);
        $form->decimal('total_balance', __('Total balance'))->default(0.000);
        $form->datetime('paid_at', __('Paid at'))->default(date('Y-m-d H:i:s'));
        $form->text('payment_method', __('Payment method'))->default('wechat');
        $form->text('payment_no', __('Payment no'));

        return $form;
    }
}
