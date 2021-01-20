<?php

namespace App\Admin\Controllers;

use App\Model\SettlementAgreement;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Str;

class SettlementAgreementController extends AdminController
{
    /**
     * Title for current resource.
     * 入住协议
     * @var string
     */
    protected $title = '入住协议';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SettlementAgreement());

        $grid->column('id', __('Id'));
        $grid->column('introduction', __('Introduction'))->display(function ($content) {
            return Str::limit($content, 50, '....');
        });;
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
        $show = new Show(SettlementAgreement::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('introduction', __('Introduction'));
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
        $form = new Form(new SettlementAgreement());

        $form->textarea('introduction', __('Introduction'));

        return $form;
    }
}
