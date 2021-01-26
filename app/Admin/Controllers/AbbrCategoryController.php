<?php

namespace App\Admin\Controllers;

use App\Model\AbbrCategory;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Table;

class AbbrCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '商铺分类';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AbbrCategory());
        $grid->model()->orderBy('id','desc');//->whereNull('parent_id')

        $grid->column('id', __('Id'));

        $grid->column('logo', __('分类图标'))->image('',50,50);
        $grid->column('image', '分类列表广告位')->image('',50,50);

        $grid->column('abbr', __('Abbr'))->display(function ($abbr) {
//            $abb = AbbrCategory::where('abbr',$abbr)->first();
            return $abbr;
        });
        $grid->column('sort', __('Sort'))->sortable();
        $grid->column('parent_id', __('上级分类名'))->display(function ($parent_id) {
            return AbbrCategory::where('id',$parent_id)->value('abbr');
        });
        $grid->column('type', __('Type'))->using(['shop' => '商铺', 'other' => '跳转']);
        $grid->column('local', __('Local'))->using(['one' => '第一部分', 'two' => '第二部分']);
        $grid->column('add_two_category', __('添加'))->display(function () {
            if($this->parent_id == null) {
                return "<a href='/admin/abbr_category/create?parent_id={$this->id}' target='_blank'>添加子集</a>";
            }
        });
        $grid->column('show_two_category', __('查看'))->display(function () {
            if($this->parent_id == null) {

                return "<a href='/admin/abbr_category?parent_id={$this->id}' target='_blank'>查看子集</a>";
            }
        });
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();
        $grid->filter(function ($filter) {
            $filter->disableIdFilter();
            $filter->like('abbr',  __('Abbr'));
            $filter->equal('parent_id',  __('上级分类ID'));
            $filter->equal('type',__('Type'))->select(['shop'=>'商铺','other'=>'跳转']);
            $filter->equal('local',__('Local'))->select(['one'=>'第一部分','two'=>'第二部分']);

//            $filter->where(function ($query) {
//                $input = $this->input;
//                $query->whereHas('user', function ($query) use ($input) {
//                    $query->where('nickname', 'like', "%$input%");
//                });
//            }, '用户名');

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
        $show = new Show(AbbrCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('abbr', __('Abbr'));
        $show->field('sort', __('Sort'));
        $show->field('logo', __('分类图标'))->image();
        $show->field('image', __('分类列表广告位'))->image();
        $show->field('parent_id', __('Parent id'));
        $show->field('type', __('Type'));
        $show->field('local', __('Local'));
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
        $form = new Form(new AbbrCategory());
        $form->text('abbr', __('Abbr'));
        $form->image('image', __('分类列表广告位'));
        $form->image('logo', __('分类图标'));
        $form->select('type', __('Type'))->default('shop')->options(['shop' => '商铺', 'other' => '跳转']);
        $form->select('local', __('Local'))->default('one')->options(['one' => '第一部分', 'two' => '第二部分']);
        $form->number('sort', __('Sort'))->default(0);
//        $form->select('is_pub', __('Is Pub'))->options([true => '是', false => '否']);
        $abbr = AbbrCategory::where('parent_id',null)->pluck('abbr','id');
        if (request('parent_id')) {
            $form->select('parent_id', __('上级分类名'))->default(null)->options($abbr);
        }else {

        }


        $form->footer(function ($footer) {

            // 去掉`重置`按钮
            $footer->disableReset();

            // 去掉`提交`按钮
            $footer->disableSubmit();

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
