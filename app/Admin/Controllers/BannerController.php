<?php

namespace App\Admin\Controllers;

use App\Model\Banner;
use App\Model\ChinaArea;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Log;

class BannerController extends AdminController
{
    /**
     * Title for current resource.
     * 轮播图
     * @var string
     */
    protected $title = '首页广告';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Banner());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'));
        $grid->column('image', __('Image'))->image('',50,50);
        $grid->column('link', __('Link'))->link();
        $grid->column('is_display', __('Is display'))->using([1 => '是', 0 => '否']);
        $grid->column('sort', __('Sort'))->sortable()->editable();
        $grid->column('type', __('Type'))->display(function ($type) {
            return $type == 'index_one' ? '第一部分广告' : '第二部分广告';
        });
        $grid->column('area', __('地区'));

        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function ($filter) {
//            $filter->like('title', __('Link'));
            $filter->equal('type','类型')->select(['index_one'=>'第一部分广告','index_two'=>'第二部分广告']);
            $filter->equal('is_display',__('Is display'))->select([true=>'是',false=>'否']);
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
        $show = new Show(Banner::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('image', __('Image'));
        $show->field('link', __('Link'));
        $show->field('is_display', __('Is display'));
        $show->field('sort', __('Sort'));
        $show->field('type', __('Type'));
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
        $form = new Form(new Banner());
        $form->hidden('area', __('Area'));

        $form->image('image', __('Image'));
        $form->textarea('link', __('Link'));
        $form->switch('is_display', __('Is display'))->default(1);
        $form->number('sort', __('Sort'))->default(0);
        $form->select('type', __('Type'))->options(['index_one' => '第一部分广告', 'index_two' => '第二部分广告'])->default('index_one');
        Banner::baseBanner($form);

        $form->footer(function ($footer) {
            // 去掉`重置`按钮
            $footer->disableReset();
            // 去掉`查看`checkbox
            $footer->disableViewCheck();
            // 去掉`继续编辑`checkbox
            $footer->disableEditingCheck();
            // 去掉`继续创建`checkbox
            $footer->disableCreatingCheck();
        });

        return $form;
    }
}
