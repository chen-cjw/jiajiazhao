<?php

namespace App\Admin\Controllers;

use App\Model\CityPartnerProfit;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CityPartnerProfitController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '四大收益';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CityPartnerProfit());

        $grid->column('id', __('Id'));
        $grid->column('content', __('Content'));
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
        $show = new Show(CityPartnerProfit::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('content', __('Content'));
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
        $form = new Form(new CityPartnerProfit());

        $form->UEditor('content', __('Content'));
        $form->number('sort', __('Sort'))->default(0);

        return $form;
    }
}
