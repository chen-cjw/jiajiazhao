<?php

namespace App\Admin\Controllers;

use App\Model\ChinaArea;
use App\Model\CityPartner;
use App\User;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CityPartnerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '城市合伙人';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new CityPartner());
        $grid->model()->orderBy('id','desc');//->where('paid_at','!=',null);

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('姓名'));
        $grid->column('phone', __('Phone'));
        $grid->column('IDCard', __('IDCard'));
        $grid->column('market', __('城市'));
        $grid->column('in_city', __('县级市'));
        // 2自动成为合伙人 4运行中
        $grid->column('is_partners', __('Is partners'))->using([0 => '未付款',1 => '取消合伙人', 2=>'审核中',3=>'是']);
        $grid->column('user_id', __('User id'))->display(function ($userId) {
            return User::where('id',$userId)->value('nickname');
        });
        $grid->column('no', __('No'));
        $grid->column('amount', __('加盟费'));
        $grid->column('balance', __('可提现余额'));
        $grid->column('total_balance', __('收益总额'));
        $grid->column('paid_at', __('Paid at'));
//        $grid->column('payment_method', __('Payment method'));
        $grid->column('payment_no', __('Payment no'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->filter(function ($filter) {
            $filter->column(1/2, function ($filter) {

                $filter->like('phone', __('Phone'));
                $filter->like('market', __('城市'));

                $filter->like('in_city', __('县级市'));

            });
            $filter->column(1/2, function ($filter) {
                $filter->like('name', __('姓名'));
                $filter->where(function ($query) {
                    $input = $this->input;
                    $query->whereHas('user', function ($query) use ($input) {
                        $query->where('nickname', 'like', "%$input%");
                    });
                }, '用户名');
                $filter->equal('is_partners', __('Is partners'))->select([0 => '未付款',1 => '取消合伙人', 2 => '审核中', 3 => '是']);


                $filter->between('paid_at', '支付时间查询')->datetime();
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
        $show = new Show(CityPartner::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('姓名'));
        $show->field('phone', __('Phone'));
        $show->field('IDCard', __('IDCard'));
        $show->field('in_city', __('县级市'));
        $show->field('is_partners', __('Is partners'));
        $show->field('user_id', __('User id'));
        $show->field('no', __('No'));
        $show->field('amount', __('Amount'));
        $show->field('balance', __('Balance'));
        $show->field('total_balance', __('Total balance'));
        $show->field('paid_at', __('Paid at'));
        $show->field('payment_method', __('Payment method'));
        $show->field('payment_no', __('Payment no'));
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
        $form = new Form(new CityPartner());

        $form->text('name', __('姓名'));
        $form->mobile('phone', __('Phone'));
        $form->text('IDCard', __('IDCard'));
//        $form->text('in_city', __('县级市'));
        // 0 未付款/取消合伙人资格
        $form->select('is_partners', __('Is partners'))->options([
            '0'=>'取消合伙人身份',
//            '1'=>'已付款',
            '2'=>'审核中',
            '3'=>'是'
        ]);
        $form->number('user_id', __('User id'));
        $form->text('no', __('No'));
        $form->decimal('amount', __('加盟费'));
        $form->decimal('balance', __('可提现余额'))->default(0.000);
        $form->decimal('total_balance', __('收益总额'))->default(0.000);
        $form->datetime('paid_at', __('Paid at'))->default(date('Y-m-d H:i:s'));
        $form->text('payment_method', __('Payment method'))->default('wechat');
        $form->text('payment_no', __('Payment no'));
//        CityPartner::baseCity($form);

        $form->distpicker([
            'province_id' => '省份',
            'city_id' => '市',
            'district_id' => '区'
        ], '地域选择')->default([
            'province' => 0,
            'city'     => 0,
            'district' => 0,
        ]);

        $form->saving(function (Form $form) {
//            dd($form);
            $chinaArea = ChinaArea::where('code',$form->district_id)->first();
            $chinaMarket = ChinaArea::where('code',$form->city_id)->first();
//            dd($chinaArea);
            if ($chinaMarket) {

                $form->model()->market = $chinaMarket->name;
            }else {

                $form->market = null;
            }
            if ($chinaArea) {
                $form->model()->in_city = $chinaArea->name;
            }else {
                $form->in_city = null;
            }
        });
        return $form;
    }
}
