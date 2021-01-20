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
     *
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
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'));
        $grid->column('logo', __('Logo'))->image('',25,25);
        $grid->column('image', '商铺广告')->image('',25,25);

        $grid->column('abbr', __('Abbr'))->display(function ($abbr) {
//            $abb = AbbrCategory::where('abbr',$abbr)->first();
            return $abbr;
        });
        $grid->column('sort', __('Sort'))->sortable();
        $grid->column('parent_id', __('Parent id'));
        $grid->column('type', __('Type'));
        $grid->column('local', __('Local'));
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

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
        $show->field('logo', __('Logo'));
        $show->field('parent_id', __('Parent id'));
        $show->field('type', __('Type'));
        $show->field('local', __('Local'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('image', __('Image'));

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
        $form->textarea('logo', __('Logo'));
        $form->number('parent_id', __('Parent id'));
        $form->text('type', __('Type'))->default('shop');
        $form->text('local', __('Local'))->default('one');
        $form->image('image', __('Image'));

        return $form;
    }
}
