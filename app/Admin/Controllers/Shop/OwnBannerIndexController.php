<?php

namespace App\Admin\Controllers\Shop;

use App\Model\AdvertisingSpace;
use App\Model\Shop\OwnBannerIndex;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OwnBannerIndexController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '轮播图';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OwnBannerIndex());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'))->sortable();
        $grid->column('image', __('Image'))->image('',50,50);
        $grid->column('is_display', __('Is display'))->using([1 => '是', 0 => '否']);
        $grid->column('sort', __('Sort'))->sortable();
        $grid->column('area', __('Area'));
        $grid->column('link', __('Link'));
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
        $show = new Show(OwnBannerIndex::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('image', __('Image'));
        $show->field('link', __('Link'));
        $show->field('is_display', __('Is display'));
        $show->field('sort', __('Sort'));
        $show->field('area', __('Area'));
        $show->field('province_id', __('Province id'));
        $show->field('city_id', __('City id'));
        $show->field('district_id', __('District id'));
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
        $form = new Form(new OwnBannerIndex());

        $form->image('image', __('Image'));

//        $form->text('area', __('Area'));
        $form->hidden('area', __('Area'));
        $form->textarea('link', __('Link'));
        $form->switch('is_display', __('Is display'))->default(1);
        $form->number('sort', __('Sort'))->default(0);
//        $form->text('province_id', __('Province id'));
//        $form->text('city_id', __('City id'));
//        $form->text('district_id', __('District id'));
        AdvertisingSpace::baseBanner($form);
        return $form;
    }
}
