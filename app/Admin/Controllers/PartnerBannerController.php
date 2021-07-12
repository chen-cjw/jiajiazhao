<?php

namespace App\Admin\Controllers;

use App\Model\ChinaArea;
use App\Model\PartnerBanner;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PartnerBannerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '城市广告位';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PartnerBanner());

        $grid->column('id', __('Id'));
        $grid->column('image', __('图片'))->image('',50,50);
        $grid->column('area', __('地区'));
        $grid->column('link_url', __('外链地址'))->link();
        $grid->column('is_display', __('是否显示'))->using(['1'=>'是','0'=>'否']);
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
        $show = new Show(PartnerBanner::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('link_url', __('外链地址'));
        $show->field('image', __('Image'))->image();
        $show->field('area', __('Area'));
        $show->field('is_display', __('Is display'))->using([1 => '是', 0 => '否']);
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
        $form = new Form(new PartnerBanner());

        $form->image('image', __('Image'));
        $form->hidden('area', __('Area'));
        $form->text('link_url', __('外链地址'));
        $form->distpicker(['province_id', 'city_id', 'district_id'])->autoselect(1);

        $form->saving(function (Form $form) {
            $form->area = ChinaArea::where('code',$form->district_id)->value('name');
//            dd([$form->area,$form->district_id]);

        });
        $form->switch('is_display', __('Is display'))->default(1);

//        dd($form);
        return $form;
    }
}
