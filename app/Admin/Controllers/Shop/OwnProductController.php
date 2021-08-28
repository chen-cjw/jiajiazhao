<?php

namespace App\Admin\Controllers\Shop;

use App\Model\Shop\OwnCategory;
use App\Model\Shop\OwnProduct;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OwnProductController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商品';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OwnProduct());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'))->sortable();
        $grid->column('own_category_id', __('分类名'))->display(function ($own_category_id) {
            return OwnCategory::where('id',$own_category_id)->value('title');
        });

        $grid->column('title', __('商品名称'));
//        $grid->column('description', __('商品介绍'));
        $grid->column('image', __('Image'))->image('',50,50);
        $grid->column('on_sale', __('已上架'))->display(function ($value) {
            return $value ? '是' : '否';
        });;
        $grid->column('rating', __('评分'))->sortable();
        $grid->column('sold_count', __('销量'))->sortable();
        $grid->column('review_count', __('评价'))->sortable();
        $grid->column('price', __('SKU 最低价格'))->sortable();
        $grid->column('sort', __('Sort'))->sortable();

        $grid->column('updated_at', __('Updated at'))->sortable();
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
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
        $show = new Show(OwnProduct::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('image', __('Image'));
        $show->field('on_sale', __('On sale'));
        $show->field('rating', __('Rating'));
        $show->field('sold_count', __('Sold count'));
        $show->field('review_count', __('Review count'));
        $show->field('price', __('Price'));
        $show->field('own_category_id', __('Own category id'));
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
        $form = new Form(new OwnProduct());

//        $form->text('title', __('Title'));
//        $form->textarea('description', __('Description'));
//        $form->image('image', __('Image'));
//        $form->switch('on_sale', __('On sale'))->default(1);
//        $form->decimal('rating', __('Rating'))->default(5.00);
//        $form->number('sold_count', __('Sold count'));
//        $form->number('review_count', __('Review count'));
//        $form->decimal('price', __('Price'));
        $form->select('own_category_id', __('分类名'))->options(OwnCategory::where('is_display',1)->pluck('title','id'));
        // 创建一个输入框，第一个参数 title 是模型的字段名，第二个参数是该字段描述
        $form->text('title', '商品名称')->rules('required');

        // 创建一个选择图片的框
        $form->multipleImage('image', '封面图片')->rules('required|image');

        // 创建一个富文本编辑器
        $form->UEditor('description', '商品描述')->rules('required');

        // 创建一组单选框
        $form->radio('on_sale', '上架')->options(['1' => '是', '0'=> '否'])->default('0');
        $form->number('sort', __('Sort'))->default(0);

        // 直接添加一对多的关联模型
        $form->hasMany('skus', 'SKU 列表', function (Form\NestedForm $form) {
            $form->text('title', 'SKU 名称')->rules('required');
            $form->text('description', 'SKU 描述')->rules('required');
            $form->text('price', '单价')->rules('required|numeric|min:0.01');
            $form->text('stock', '剩余库存')->rules('required|integer|min:0');
        });

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            $form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price') ?: 0;
        });

        return $form;
    }
}
