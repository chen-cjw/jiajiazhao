<?php

use Illuminate\Database\Seeder;

class UserAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'phone' => '1836177154'.rand(4,9),
            'ml_openid' => 'ml_openid'.rand(1,100),
            'nickname' => 'name',
            'avatar' => 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=1654242150,297019303&fm=26&gp=0.jpg',
            'sex' => 1,
            'parent_id' =>  null,
            'city_partner' => 0,
            'ref_code'=>rand(1,20)
        ];
        $len = \App\Model\AdminUser::where('id','>',1)->count();
//        echo $len;

        for ($i = 1; $i <= $len; $i++) {
//            echo $i;

            $queryAdmin = \App\Model\AdminUser::where('id',$i+1);
//
            if (\App\User::where('id',$i+1)->first()) {

            }else {

                $data = [
                    'id' => $queryAdmin->value('id'),
                    'phone' => '1000000000' . $i,
                    'ml_openid' => 'ml_openid' . $i,
                    'nickname' => $queryAdmin->value('username'),
                    'avatar' => $queryAdmin->value('avatar'),
                    'sex' => 2,
                    'parent_id' => null,
                    'city_partner' => 0,
                    'ref_code' => $i
                ];
                \App\User::create($data);
            }
        }
    }
}
