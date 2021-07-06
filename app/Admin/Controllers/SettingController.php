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
            if ($key=='driverCertification') {
                return '司机认证是否需要审核/0审核/1无需审核';
            }
            if ($key=='informationDisplay') {
                return '发布消息是否需要审核';
            }
            if ($key=='shop_verify') {
                return '商户入驻是否需要审核';
            }
            //
            if ($key=='withdrawal_low') {
                return '低于当前额度不准提现';
            }
            if ($key=='timeSearch') {
                return '帖子时间设置/天数';
            }
            if ($key=='city_partner_amount') {
                return '入住费';
            }
            if ($key=='information_fee') {
                return '便民发帖抽佣/邀请人';
            }
            if ($key=='city_information_fee') {
                return '便民发帖抽佣/合伙人';
            }

            if ($key=='city_transition_flow_fee') {
                return '商户交易流水';
            }
            if ($key=='adv') {
                return '地接广告的';
            }
            if ($key=='city_shop_fee') {
                return '商户入住抽佣/合伙人';
            }
            // city_partner_withdrawal_low
            if ($key=='city_partner_withdrawal_low') {
                return '最低提现金额/合伙人';
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
