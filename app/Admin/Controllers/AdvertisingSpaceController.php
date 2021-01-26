<?php

namespace App\Admin\Controllers;

use App\Model\AdvertisingSpace;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class AdvertisingSpaceController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '便民广告位';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdvertisingSpace());

        $grid->column('id', __('Id'));
        $grid->column('image', __('Image'));
        $grid->column('link', __('Link'));
        $grid->column('is_display', __('Is display'));
        $grid->column('sort', __('Sort'));
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
        $show = new Show(AdvertisingSpace::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('image', __('Image'));
        $show->field('link', __('Link'));
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
        $form = new Form(new AdvertisingSpace());

        $form->image('image', __('Image'));
        $form->textarea('link', __('Link'));
        $form->switch('is_display', __('Is display'))->default(1);
        $form->number('sort', __('Sort'));

        return $form;
    }
}
