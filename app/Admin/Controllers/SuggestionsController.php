<?php

namespace App\Admin\Controllers;

use App\Model\Suggestions;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SuggestionsController extends AdminController
{
    /**
     * Title for current resource.
     * 投诉建议
     * @var string
     */
    protected $title = '投诉建议';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Suggestions());

        $grid->column('id', __('Id'));
        $grid->column('content', __('Content'));
        $grid->column('username', __('User id'))->display(function ($userId){
            return User::where('id',$this->user_id)->value('nickname');
        });
//        $grid->column('user_id', __('User id'))->display(function ($userId){
//            return User::where('id',$userId)->value('phone');
//        });
        $grid->column('user_id', __('用户ID'))->display(function ($user_id) {
            if ($this->localCarpooling_id==0) {
                return "<a href='/admin/users?&id={$user_id}'>$user_id</a>";
            }
        });
        $grid->column('localCarpooling_id', __('投诉的帖子'))->display(function ($localCarpooling_id) {
            if($localCarpooling_id == 0) {
                return '个人中心投诉';
            }else {
                return "<a href='/admin/information?&id={$localCarpooling_id}'>投诉帖子ID.$localCarpooling_id</a>";
//                return '/admin/information?&id='.$localCarpooling_id;
            }
        });

        $grid->column('is_accept', __('Is accept'))->using([1 => '是', 0 => '否']);
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
        $show = new Show(Suggestions::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('content', __('Content'));
        $show->field('user_id', __('User id'));
        $show->field('is_accept', __('Is accept'));
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
        $form = new Form(new Suggestions());

        $form->UEditor('content', __('Content'));
        $form->number('user_id', __('User id'));
        $form->switch('is_accept', __('Is accept'));
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
