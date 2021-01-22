<?php

namespace App\Admin\Controllers;

use App\Model\CardCategory;
use App\Model\ConvenientInformation;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Str;

class ConvenientInformationController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '便民信息';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ConvenientInformation());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'))->display(function ($userId){
            return User::where('id',$userId)->value('nickname');
        });
        $grid->column('card_id', __('Card id'))->display(function ($cardId){
            return CardCategory::where('id',$cardId)->value('name');
        });

        $grid->column('title', __('Title'));
        $grid->column('content', __('Content'))->display(function ($content) {
            return Str::limit($content, 50, '....');
        });
        $grid->column('location', __('Location'));
//        $grid->column('lng', __('Lng'));
//        $grid->column('lat', __('Lat'));
        $grid->column('view', __('View'));

        $grid->column('no', __('No'));
        $grid->column('card_fee', __('Card fee'));
        $grid->column('top_fee', __('Top fee'));
        $grid->column('paid_at', __('Paid at'));
//        $grid->column('payment_method', __('Payment method'));
        $grid->column('payment_no', __('Payment no'));
        $grid->column('sort', __('Sort'));
        $grid->column('is_display', __('Is display'))->using([1 => '是', 0 => '否']);
        $grid->column('comment_count', __('Comment count'));
//        $grid->column('is_top', __('Is top'));
        $grid->column('created_at', __('Created at'));
//        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(ConvenientInformation::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('content', __('Content'));
        $show->field('location', __('Location'));
        $show->field('lng', __('Lng'));
        $show->field('lat', __('Lat'));
        $show->field('view', __('View'));
        $show->field('card_id', __('Card id'));
        $show->field('user_id', __('User id'));
        $show->field('no', __('No'));
        $show->field('card_fee', __('Card fee'));
        $show->field('top_fee', __('Top fee'));
        $show->field('paid_at', __('Paid at'));
        $show->field('payment_method', __('Payment method'));
        $show->field('payment_no', __('Payment no'));
        $show->field('sort', __('Sort'));
        $show->field('is_display', __('Is display'));
        $show->field('comment_count', __('Comment count'));
        $show->field('is_top', __('Is top'));
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
        $form = new Form(new ConvenientInformation());

        $form->text('title', __('Title'));
        $form->UEditor('content', __('Content'));
        $form->text('location', __('Location'));
        $form->decimal('lng', __('Lng'));
        $form->decimal('lat', __('Lat'));
        $form->text('view', __('View'));
        $form->number('card_id', __('Card id'));
        $form->number('user_id', __('User id'));
        $form->text('no', __('No'));
        $form->decimal('card_fee', __('Card fee'));
        $form->decimal('top_fee', __('Top fee'));
        $form->datetime('paid_at', __('Paid at'))->default(date('Y-m-d H:i:s'));
        $form->text('payment_method', __('Payment method'))->default('wechat');
        $form->text('payment_no', __('Payment no'));
        $form->number('sort', __('Sort'));
        $form->switch('is_display', __('Is display'))->default(1);
        $form->text('comment_count', __('Comment count'));
        $form->switch('is_top', __('Is top'));

        return $form;
    }
}
