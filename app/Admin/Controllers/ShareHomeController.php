<?php

namespace App\Admin\Controllers;

use App\Model\ShareHome;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ShareHomeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '分享页设置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ShareHome());

        $grid->column('id', __('Id'));
        $grid->column('image', __('分享图'))->image('',50,50);
        $grid->column('name', __('分享名'));
        $grid->column('local', __('位置'))->display(function ($local) {
            if($this->id == 1) {
                return '分享页';
            }
            if($this->id == 2) {
                return '商铺分享页';
            }
            if($this->id == 3) {
                return '便民信息分享页';
            }
            if($this->id == 4) {
                return '拼车分享页';
            }
            if($this->id == 5) {
                return '总的分享';
            }
            if($this->id == 6) {
                return '合伙人分享页';
            }
        });
        $grid->actions(function ($actions) {
            //关闭行操作 删除
            $actions->disableDelete();
        });
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
        $show = new Show(ShareHome::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('分享名'));
        $show->field('image', __('分享图'))->image('',300,300);
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
        $form = new Form(new ShareHome());

        $form->text('name', __('分享名'));
        $form->image('image', __('分享图'));

        return $form;
    }
}
