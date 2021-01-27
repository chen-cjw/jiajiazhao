<?php

namespace App\Admin\Controllers;

use App\Model\DriverCertification;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DriverCertificationController extends AdminController
{
    /**
     * Title for current resource.
     * 司机身份认证
     * @var string
     */
    protected $title = '司机身份认证';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DriverCertification());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'))->display(function ($userId){
            return User::where('id',$userId)->value('nickname');
        });
        $grid->column('id_card', __('Id card'))->image('',25,25);
        $grid->column('driver', __('Driver'))->image('',25,25);
        $grid->column('action', __('Action'))->image('',25,25);
        $grid->column('car', __('Car'))->image('',25,25);
        $grid->column('is_display', __('Is display'))->using([1 => '是', 0 => '否']);

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
        $show = new Show(DriverCertification::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('id_card', __('Id card'));
        $show->field('driver', __('Driver'));
        $show->field('action', __('Action'));
        $show->field('car', __('Car'));
        $show->field('is_display', __('Is display'));
        $show->field('user_id', __('User id'))->as(function ($user_id) {
            return User::where('id',$user_id)->value('nickname');
        });
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
//        $form = new Form(new DriverCertification());
//
//        $form->text('id_card', __('Id card'));
//        $form->text('driver', __('Driver'));
//        $form->text('action', __('Action'));
//        $form->text('car', __('Car'));
//        $form->switch('is_display', __('Is display'));
//        $form->number('user_id', __('User id'));
//        $form->footer(function ($footer) {
//             去掉`重置`按钮
//            $footer->disableReset();
//             去掉`查看`checkbox
//            $footer->disableViewCheck();
//             去掉`继续编辑`checkbox
//            $footer->disableEditingCheck();
//             去掉`继续创建`checkbox
//            $footer->disableCreatingCheck();
//        });
//        return $form;
    }
}
