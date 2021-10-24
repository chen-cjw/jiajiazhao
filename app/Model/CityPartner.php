<?php

namespace App\Model;

use Encore\Admin\Form;

class CityPartner extends Model
{
    protected $fillable = ['name','phone','IDCard','in_city','market','user_id','no','amount','paid_at','payment_no'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // 支付订单的随机数
    public static function findAvailableNo()
    {
        // 订单流水号前缀
        $prefix = date('YmdHis');
        for ($i = 0; $i < 10; $i++) {
            // 随机生成 6 位的数字
            $no = $prefix.str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            // 判断是否已经存在
            if (!CityPartner::where('no', $no)->exists()) {
                return $no;
            }
        }
        \Log::warning('find order no failed');

        return false;
    }

    public static function baseCity($form)
    {
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
            $chinaArea = ChinaArea::where('code',$form->district_id)->first();
            $chinaMarket = ChinaArea::where('code',$form->city_id)->first();
            if ($chinaMarket) {
                $form->market = $chinaMarket->name;
            }else {
                $form->market = null;
            }
            if ($chinaArea) {
                $form->in_city = $chinaArea->name;
            }else {
                $form->in_city = null;
            }
        });
        return $form;
    }
}
