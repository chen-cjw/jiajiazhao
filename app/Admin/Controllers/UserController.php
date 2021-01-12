<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'App\User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());

        $grid->column('id', __('Id'));
//        $grid->column('ml_openid', __('Ml openid'));
        $grid->column('avatar', '头像')->image('',25,25);
        $grid->column('phone', '手机号');
        $grid->column('nickname', '用户名');
        $grid->column('sex', '性别');
//        $grid->column('parent_id', __('Parent id'));
        $grid->column('is_member', '是否成员');
        $grid->column('is_certification', '司机是否认证');
        $grid->column('balance', '余额');
        $grid->column('city_partner', '城市合伙人');
        $grid->column('ref_code', __('邀请码'));
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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('ml_openid', __('Ml openid'));
        $show->field('phone', __('Phone'));
        $show->field('avatar', __('Avatar'));
        $show->field('nickname', __('Nickname'));
        $show->field('sex', __('Sex'));
        $show->field('parent_id', __('Parent id'));
        $show->field('is_member', __('Is member'));
        $show->field('is_certification', __('Is certification'));
        $show->field('balance', __('Balance'));
        $show->field('city_partner', __('City partner'));
        $show->field('ref_code', __('Ref code'));
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
        $form = new Form(new User());

        $form->text('ml_openid', __('Ml openid'));
        $form->mobile('phone', __('Phone'));
        $form->image('avatar', __('Avatar'));
        $form->text('nickname', __('Nickname'));
        $form->switch('sex', __('Sex'))->default(1);
        $form->number('parent_id', __('Parent id'));
        $form->switch('is_member', __('Is member'))->default(1);
        $form->switch('is_certification', __('Is certification'));
        $form->decimal('balance', __('Balance'))->default(0.000);
        $form->switch('city_partner', __('City partner'));
        $form->text('ref_code', __('Ref code'));

        return $form;
    }
}
