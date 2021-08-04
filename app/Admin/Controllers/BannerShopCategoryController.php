<?php

namespace App\Admin\Controllers;

use App\Model\AbbrCategory;
use App\Model\BannerShopCategory;
use App\Model\CardCategory;
use App\Model\ChinaArea;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BannerShopCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '行业分类轮播图';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new BannerShopCategory());

        $grid->column('id', __('Id'));
        $grid->column('image', __('Image'))->image('',50,50);
        $grid->column('link', __('Link'))->link();
        $grid->column('sort', __('Sort'));
        $grid->column('area', __('Area'));
//        $grid->column('province_id', __('Province id'));
//        $grid->column('city_id', __('City id'));
//        $grid->column('district_id', __('District id'));
        $grid->column('abbr_category_id', __('行业分类名'))->display(function ($abbr_category_id){
            return AbbrCategory::where('id',$abbr_category_id)->value('abbr');
        });
        $grid->column('is_display', __('Is display'))->using([1 => '是', 0 => '否']);

        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->filter(function ($filter) {
            $filter->like('area', __('Area'));

            // 查询过滤
                // 关联关系查询
                $filter->where(function ($query) {
                    $input = $this->input;
                    $query->whereHas('abbCategory', function ($query) use ($input) {
                                $query->where('abbr_category_id',  'like', "%$input%");
                            });
                }, '行业分类名')->select(AbbrCategory::whereNull('parent_id')->where('is_display',1)->pluck('abbr','id'));
//            $filter->equal('province_id', '谨慎')->select(ChinaArea:: where('pid',0)->orderBy('name')->pluck('name', 'id'));
//
//
//            $filter->equal('city_id', '城市')->select(ChinaArea::where('parent_id','<>',0)->orderBy('name')->pluck('name', 'id'))
//                ->load('city3', '/api/wdcity/district');
//
//            $filter->equal('district_id', '区')->select(function ($id) {
//                return ChinaArea::options($id);
//            });
        });
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
        $show = new Show(BannerShopCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('image', __('Image'))->image('',50,50);
        $show->field('link', __('Link'));
        $show->field('is_display', __('Is display'));
        $show->field('sort', __('Sort'));
        $show->field('area', __('Area'));
//        $show->field('province_id', __('Province id'));
//        $show->field('city_id', __('City id'));
//        $show->field('district_id', __('District id'));
        $show->field('abbr_category_id', __('行业分类名'));
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
        $form = new Form(new BannerShopCategory());
        $form->select('abbr_category_id', __('行业分类名'))->options(AbbrCategory::where('is_display',1)->whereNull('parent_id')->pluck('abbr','id'));

        $form->image('image', __('Image'));
        $form->switch('is_display', __('Is display'))->default(1);
        $form->number('sort', __('Sort'))->default(0);
        $form->hidden('area', __('Area'));
        $form->textarea('link', __('Link'));

        //        $form->text('province_id', __('Province id'));
//        $form->text('city_id', __('City id'));
//        $form->text('district_id', __('District id'));
        BannerShopCategory::baseBanner($form);

        return $form;
    }
}
