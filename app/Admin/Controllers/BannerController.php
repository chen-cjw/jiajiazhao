<?php

namespace App\Admin\Controllers;

use App\Model\Banner;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BannerController extends AdminController
{
    /**
     * Title for current resource.
     * 轮播图
     * @var string
     */
    protected $title = 'App\Model\Banner';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Banner());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'));
        $grid->column('image', __('Image'))->image('',25,25);
        $grid->column('link', __('Link'))->link();
        $grid->column('is_display', __('Is display'))->using([1 => '是', 0 => '否']);
        $grid->column('sort', __('Sort'))->sortable();
        $grid->column('type', __('Type'));
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
        $show = new Show(Banner::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('image', __('Image'));
        $show->field('link', __('Link'));
        $show->field('is_display', __('Is display'));
        $show->field('sort', __('Sort'));
        $show->field('type', __('Type'));
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
        $form = new Form(new Banner());

        $form->image('image', __('Image'));
        $form->textarea('link', __('Link'));
        $form->switch('is_display', __('Is display'));
        $form->number('sort', __('Sort'));
        $form->text('type', __('Type'));

        return $form;
    }
}
