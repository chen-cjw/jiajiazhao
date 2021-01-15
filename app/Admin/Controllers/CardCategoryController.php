<?php

namespace App\Admin\Controllers;

use App\Model\CardCategory;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CardCategoryController extends AdminController
{
    /**
     * Title for current resource.
     * 便民信息的分类
     * @var string
     */
    protected $title = 'App\Model\CardCategory';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CardCategory());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('sort', __('Sort'));
        $grid->column('is_display', __('Is display'));
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
        $show = new Show(CardCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('sort', __('Sort'));
        $show->field('is_display', __('Is display'));
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
        $form = new Form(new CardCategory());

        $form->text('name', __('Name'));
        $form->number('sort', __('Sort'));
        $form->switch('is_display', __('Is display'))->default(1);

        return $form;
    }
}