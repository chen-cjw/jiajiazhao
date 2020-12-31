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


    }
}
