<?php

use Illuminate\Database\Seeder;

class AdminTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // base tables
        Encore\Admin\Auth\Database\Menu::truncate();
        Encore\Admin\Auth\Database\Menu::insert(
            [
                [
                    "parent_id" => 0,
                    "order" => 7,
                    "title" => "控制台",
                    "icon" => "fa-bar-chart",
                    "uri" => "/",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 1,
                    "title" => "后台管理",
                    "icon" => "fa-tasks",
                    "uri" => NULL,
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 2,
                    "title" => "用户管理",
                    "icon" => "fa-users",
                    "uri" => "auth/users",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 3,
                    "title" => "角色管理",
                    "icon" => "fa-user",
                    "uri" => "auth/roles",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 4,
                    "title" => "权限管理",
                    "icon" => "fa-ban",
                    "uri" => "auth/permissions",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 5,
                    "title" => "菜单管理",
                    "icon" => "fa-bars",
                    "uri" => "auth/menu",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 2,
                    "order" => 6,
                    "title" => "日志管理",
                    "icon" => "fa-history",
                    "uri" => "auth/logs",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 8,
                    "title" => "会员管理",
                    "icon" => "fa-bars",
                    "uri" => "/users",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 9,
                    "title" => "行业分类",
                    "icon" => "fa-bars",
                    "uri" => "/abbr_category",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 10,
                    "title" => "轮播图",
                    "icon" => "fa-bars",
                    "uri" => "/banners",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 11,
                    "title" => "便民信息分类",
                    "icon" => "fa-bars",
                    "uri" => "/card_category",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 12,
                    "title" => "拼车协议",
                    "icon" => "fa-bars",
                    "uri" => "/carpooling",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 13,
                    "title" => "便民信息",
                    "icon" => "fa-bars",
                    "uri" => "/information",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 14,
                    "title" => "司机认证",
                    "icon" => "fa-bars",
                    "uri" => "/driver_certifications",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 15,
                    "title" => "本地拼车",
                    "icon" => "fa-bars",
                    "uri" => "/local_carpooling",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 16,
                    "title" => "公告",
                    "icon" => "fa-bars",
                    "uri" => "/notices",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 17,
                    "title" => "设置",
                    "icon" => "fa-bars",
                    "uri" => "/settings",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 18,
                    "title" => "入住协议",
                    "icon" => "fa-bars",
                    "uri" => "/settlement_agreements",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 19,
                    "title" => "商户管理",
                    "icon" => "fa-bars",
                    "uri" => "/shops",
                    "permission" => NULL
                ],
                [
                    "parent_id" => 0,
                    "order" => 20,
                    "title" => "投诉建议",
                    "icon" => "fa-bars",
                    "uri" => "/suggestions",
                    "permission" => NULL
                ]
            ]
        );

        Encore\Admin\Auth\Database\Permission::truncate();
        Encore\Admin\Auth\Database\Permission::insert(
            [
                [
                    "name" => "All permission",
                    "slug" => "*",
                    "http_method" => "",
                    "http_path" => "*"
                ],
                [
                    "name" => "Dashboard",
                    "slug" => "dashboard",
                    "http_method" => "GET",
                    "http_path" => "/"
                ],
                [
                    "name" => "Login",
                    "slug" => "auth.login",
                    "http_method" => "",
                    "http_path" => "/auth/login\r\n/auth/logout"
                ],
                [
                    "name" => "User setting",
                    "slug" => "auth.setting",
                    "http_method" => "GET,PUT",
                    "http_path" => "/auth/setting"
                ],
                [
                    "name" => "Auth management",
                    "slug" => "auth.management",
                    "http_method" => "",
                    "http_path" => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs"
                ]
            ]
        );

        Encore\Admin\Auth\Database\Role::truncate();
        Encore\Admin\Auth\Database\Role::insert(
            [
                [
                    "name" => "Administrator",
                    "slug" => "administrator"
                ]
            ]
        );

        // pivot tables
        DB::table('admin_role_menu')->truncate();
        DB::table('admin_role_menu')->insert(
            [
                [
                    "role_id" => 1,
                    "menu_id" => 2
                ]
            ]
        );

        DB::table('admin_role_permissions')->truncate();
        DB::table('admin_role_permissions')->insert(
            [
                [
                    "role_id" => 1,
                    "permission_id" => 1
                ]
            ]
        );

        // finish
    }
}
