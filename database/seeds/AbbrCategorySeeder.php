<?php

use Illuminate\Database\Seeder;

class AbbrCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $table->string('abbr')->nullable()->comment('分类');
//        $table->bigInteger('sort')->default(0)->comment('排序大的在上');
//        $table->bigInteger('parent_id')->nullable()->comment('父级');
        $res = \App\Model\AbbrCategory::create([
            'abbr'=>'日常维修',
            'sort'=>'1',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',
            'parent_id'=>null,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'冰箱维修',
            'sort'=>'2',
            'parent_id'=>$res->id,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'空调维修',
            'sort'=>'4',
            'parent_id'=>$res->id,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'洗衣机维修',
            'sort'=>'3',
            'parent_id'=>$res->id,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'水管维修',
            'sort'=>'1',
            'parent_id'=>$res->id,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'电动车维修',
            'sort'=>'12',
            'parent_id'=>$res->id,
        ]);

         \App\Model\AbbrCategory::create([
            'abbr'=>'家政服务',
            'sort'=>'1',
             'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',

             'parent_id'=>null,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'开锁配钥',
            'sort'=>'2',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',

            'parent_id'=>null,

        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'搬家拉货',
            'sort'=>'4',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',

            'parent_id'=>null,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'家电清洗',
            'sort'=>'3',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',

            'parent_id'=>null,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'二手供求',
            'sort'=>'1',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',

            'parent_id'=>null,
        ]);

        \App\Model\AbbrCategory::create([
            'abbr'=>'服装',
            'sort'=>'1',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',

            'parent_id'=>null,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'家具',
            'sort'=>'2',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',

            'parent_id'=>null,

        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'美食',
            'sort'=>'4',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',

            'parent_id'=>null,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'代步',
            'sort'=>'3',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',

            'parent_id'=>null,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'装修',
            'sort'=>'1',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',
            'parent_id'=>null,
        ]);
        \App\Model\AbbrCategory::create([
            'abbr'=>'卖房',
            'sort'=>'12',
            'logo'=>'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fc-ssl.duitang.com%2Fuploads%2Fitem%2F202003%2F21%2F20200321022745_H4EYd.thumb.400_0.jpeg&refer=http%3A%2F%2Fc-ssl.duitang.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=jpeg?sec=1612854885&t=ac6503c120691baed4a1d3f3ce337363',
            'parent_id'=>null,
        ]);

    }

}
