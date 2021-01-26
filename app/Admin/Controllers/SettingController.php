<?php

namespace App\Admin\Controllers;

use App\Model\Setting;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class SettingController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '默认设置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Setting());
        $grid->model()->orderBy('id','desc');

        $grid->column('id', __('Id'));
        $grid->column('key', __('Key'))->display(function ($key) {
            if ($key=='localCarpoolingAmount') {
                return '发布拼车费';
            }
            if ($key=='information_card_fee') {
                return '便民信息费用';
            }
            if ($key=='information_top_fee') {
                return '便民信息置顶费';

            }
            if ($key=='shop_fee') {
                return '商铺发布一年的费用';
            }
            if ($key=='shop_top_fee') {
                return '商铺发布一年置顶费';

            }
            if ($key=='shop_fee_two') {
                return '商铺发布两年的费用';

            }
            if ($key=='shop_top_fee_two') {
                return '商铺发布两年置顶费';

            }
            if ($key=='radius') {
                return '附近公里数';
            }
            if ($key=='award') {
                return '奖励金';
            }
        });
        $grid->column('value', __('Value'));
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
        $show = new Show(Setting::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('key', __('Key'));
        $show->field('value', __('Value'));
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
        $form = new Form(new Setting());

//        $form->text('key', __('Key'));
        $form->text('value', __('Value'));
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
