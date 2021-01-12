<?php

namespace App\Admin\Controllers;

use App\Model\Suggestions;
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
    protected $title = 'App\Model\Suggestions';

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
        $grid->column('user_id', __('User id'));
        $grid->column('is_accept', __('Is accept'));
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

        $form->text('content', __('Content'));
        $form->number('user_id', __('User id'));
        $form->switch('is_accept', __('Is accept'));

        return $form;
    }
}
