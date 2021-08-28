<?php

namespace App\Admin\Controllers\Shop;

use App\Model\Shop\OwnCategory;
use App\Model\Shop\OwnProduct;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OwnCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '分类管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OwnCategory());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'))->sortable();
        $grid->column('title', __('Title'));

        $grid->column('image', __('Image'))->image('',50,50);
        $grid->column('is_display', __('Is display'))->using([1 => '是', 0 => '否']);
        $grid->column('sort', __('Sort'))->sortable();
        $grid->column('parent_id', __('上一级'))->display(function ($parnetId) {
            if ($parnetId) {
                return OwnCategory::where('id',$parnetId)->value('title');
            }
            return '无';
        });
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
        $show = new Show(OwnCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('parent_id', __('Parent id'));
        $show->field('image', __('Image'));
        $show->field('is_display', __('Is display'));
        $show->field('sort', __('Sort'));
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
        $form = new Form(new OwnCategory());
        $data = OwnCategory::where('is_display',1)->orderBy('sort','desc')->pluck('title','id');
        $form->select('parent_id', __('上一级'))->options($data);
        $form->text('title', __('Title'));
        $form->image('image', __('Image'));
        $form->switch('is_display', __('Is display'))->default(1);
        $form->number('sort', __('Sort'))->default(0);

        return $form;
    }
}
