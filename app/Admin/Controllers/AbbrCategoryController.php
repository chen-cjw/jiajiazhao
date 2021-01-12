<?php

namespace App\Admin\Controllers;

use App\Model\AbbrCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AbbrCategoryController extends AdminController
{
    /**
     * Title for current resource.
     * 行业分类
     * @var string
     */
    protected $title = 'App\Model\AbbrCategory';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AbbrCategory());

        $grid->column('id', __('Id'));
        $grid->column('abbr', __('Abbr'));
        $grid->column('sort', __('Sort'));
        $grid->column('parent_id', __('Parent id'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('logo', __('Logo'));

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
        $show = new Show(AbbrCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('abbr', __('Abbr'));
        $show->field('sort', __('Sort'));
        $show->field('parent_id', __('Parent id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('logo', __('Logo'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new AbbrCategory());

        $form->text('abbr', __('Abbr'));
        $form->number('sort', __('Sort'));
        $form->number('parent_id', __('Parent id'));
        $form->textarea('logo', __('Logo'));

        return $form;
    }
}
