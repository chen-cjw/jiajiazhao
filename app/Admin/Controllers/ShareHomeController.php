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