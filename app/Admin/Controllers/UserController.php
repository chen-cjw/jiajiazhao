<?php

namespace App\Admin\Controllers;

use App\Shop;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     * 会员管理
     * @var string
     */
    protected $title = '会员管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'));
//        $grid->column('ml_openid', __('Ml openid'));
        $grid->column('phone', __('Phone'));
        $grid->column('avatar', __('Avatar'))->image('',50,50);
        $grid->column('nickname', __('Nickname'));
        $grid->column('sex', __('Sex'))->using([1 => '男', 0 => '女']);
        $grid->column('parent_id', __('Parent id'))->display(function ($parent_id) {
            return User::where('id',$parent_id)->value('nickname');
        });
        $grid->column('is_member', __('Is member'))->display(function ($isMember) {
            return  $isMember == '0' ? '商家' : '会员';
        });
        $grid->column('is_certification', __('Is certification'));
        $grid->column('balance', __('Balance'))->sortable();
        $grid->column('city_partner', __('City partner'))->using([1 => '是', 0 => '否']);
        $grid->column('ref_code', __('Ref code'));
        $grid->column('display', __('是否禁用'))->using([1 => '是', 0 => '否']);
        $grid->column('is_shop', __('商户入驻'))->display(function () {
            return Shop::where('user_id',$this->id)->whereNotNull('payment_no')->first() ? '是' : '否';
        });
//        $grid->column('code', __('Code'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
            $filter->like('nickname', '用户昵称');
            $filter->like('phone', '手机号');
            $filter->equal('parent_id', '我邀请的用户(输入邀请人用户ID)');
            $filter->equal('is_member','商家/会员')->select([true=>'商家',false=>'会员']);
            $filter->equal('city_partner','城市合伙人')->select([true=>'是',false=>'否']);
        });

        $grid->disableCreateButton();
        $grid->disableExport();
//        $grid->disableActions();

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
//        $show->field('code', __('Code'));
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

//        $form->text('ml_openid', __('Ml openid'));
        $form->mobile('phone', __('Phone'));
//        $form->image('avatar', __('Avatar'));
        $form->text('nickname', __('Nickname'));
//        $form->switch('sex', __('Sex'))->default(1);
//        $form->number('parent_id', __('Parent id'));
//        $form->switch('is_member', __('Is member'))->default(1);
//        $form->switch('is_certification', __('Is certification'));
//        $form->decimal('balance', __('Balance'))->default(0.000);
//        $form->switch('city_partner', __('City partner'));
//        $form->text('ref_code', __('Ref code'));
        $form->switch('display', __('是否禁用'));
//        $form->text('code', __('Code'));
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
