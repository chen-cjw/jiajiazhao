<?php

namespace App\Admin\Controllers;

use App\Model\LocalCarpooling;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LocalCarpoolingController extends AdminController
{
    /**
     * Title for current resource.
     * 本地拼车
     * @var string
     */
    protected $title = 'App\Model\LocalCarpooling';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new LocalCarpooling());

        $grid->column('id', __('Id'));
        $grid->column('phone', __('Phone'));
        $grid->column('name_car', __('Name car'));
        $grid->column('capacity', __('Capacity'));
        $grid->column('go', __('Go'));
        $grid->column('end', __('End'));
        $grid->column('departure_time', __('Departure time'));
        $grid->column('seat', __('Seat'));
        $grid->column('other_need', __('Other need'));
        $grid->column('is_go', __('Is go'));
        $grid->column('type', __('Type'));
        $grid->column('lng', __('Lng'));
        $grid->column('lat', __('Lat'));
        $grid->column('area', __('Area'));
        $grid->column('no', __('No'));
        $grid->column('amount', __('Amount'));
        $grid->column('paid_at', __('Paid at'));
        $grid->column('payment_method', __('Payment method'));
        $grid->column('payment_no', __('Payment no'));
        $grid->column('closed', __('Closed'));
        $grid->column('user_id', __('User id'));
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
        $show = new Show(LocalCarpooling::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('phone', __('Phone'));
        $show->field('name_car', __('Name car'));
        $show->field('capacity', __('Capacity'));
        $show->field('go', __('Go'));
        $show->field('end', __('End'));
        $show->field('departure_time', __('Departure time'));
        $show->field('seat', __('Seat'));
        $show->field('other_need', __('Other need'));
        $show->field('is_go', __('Is go'));
        $show->field('type', __('Type'));
        $show->field('lng', __('Lng'));
        $show->field('lat', __('Lat'));
        $show->field('area', __('Area'));
        $show->field('no', __('No'));
        $show->field('amount', __('Amount'));
        $show->field('paid_at', __('Paid at'));
        $show->field('payment_method', __('Payment method'));
        $show->field('payment_no', __('Payment no'));
        $show->field('closed', __('Closed'));
        $show->field('user_id', __('User id'));
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
        $form = new Form(new LocalCarpooling());

        $form->mobile('phone', __('Phone'));
        $form->text('name_car', __('Name car'));
        $form->text('capacity', __('Capacity'));
        $form->text('go', __('Go'));
        $form->text('end', __('End'));
        $form->text('departure_time', __('Departure time'));
        $form->text('seat', __('Seat'));
        $form->text('other_need', __('Other need'));
        $form->switch('is_go', __('Is go'));
        $form->text('type', __('Type'));
        $form->decimal('lng', __('Lng'));
        $form->decimal('lat', __('Lat'));
        $form->text('area', __('Area'));
        $form->text('no', __('No'));
        $form->decimal('amount', __('Amount'));
        $form->datetime('paid_at', __('Paid at'))->default(date('Y-m-d H:i:s'));
        $form->text('payment_method', __('Payment method'))->default('wechat');
        $form->text('payment_no', __('Payment no'));
        $form->switch('closed', __('Closed'));
        $form->number('user_id', __('User id'));

        return $form;
    }
}
