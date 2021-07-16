<?php

namespace App\Admin\Controllers;

use App\Model\BannerShopShow;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BannerShopShowController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '店铺详情轮播图';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BannerShopShow());

        $grid->column('id', __('Id'));
        $grid->column('image', __('Image'))->image('',50,50);
        $grid->column('link', __('Link'));
        $grid->column('is_display', __('Is display'));
        $grid->column('sort', __('Sort'));
        $grid->column('area', __('地区'));

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
        $show = new Show(BannerShopShow::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('image', __('Image'))->image('',50,50);
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
        $form = new Form(new BannerShopShow());
        $form->hidden('area', __('Area'));

        $form->image('image', __('Image'));
        $form->textarea('link', __('Link'));
        $form->switch('is_display', __('Is display'))->default(1);
        $form->number('sort', __('Sort'))->default(0);
        BannerShopShow::baseBanner($form);

        return $form;
    }
}
