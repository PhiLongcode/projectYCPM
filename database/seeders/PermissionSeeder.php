<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         
        $permissions = [
            // Module Users
            [
                'name' => '|---List User',
                'slug' => 'users.list',
                'description' => 'Xem danh sách người dùng'
            ],
            [
                'name' => '|---Add User',
                'slug' => 'users.add',
                'description' => 'Thêm người dùng'
            ],
            [
                'name' => '|---Edit User',
                'slug' => 'users.edit',
                'description' => 'Sửa người dùng'
            ],
            [
                'name' => '|---Delete User',
                'slug' => 'users.delete',
                'description' => 'Xóa người dùng'
            ],

            // Module Products
            [
                'name' => '|---Add Product',
                'slug' => 'products.add',
                'description' => 'Thêm sản phẩm'
            ],
            [
                'name' => '|---Edit Product',
                'slug' => 'products.edit',
                'description' => 'Sửa sản phẩm'
            ],
            [
                'name' => '|---Delete Product',
                'slug' => 'products.delete',
                'description' => 'Xóa sản phẩm'
            ],

            // Module Orders
            [
                'name' => '|---List Order',
                'slug' => 'orders.list',
                'description' => 'Xem danh sách đơn hàng'
            ],
            [
                'name' => '|---Edit Order',
                'slug' => 'orders.edit',
                'description' => 'Sửa đơn hàng'
            ],
            [
                'name' => '|---Delete Order',
                'slug' => 'orders.delete',
                'description' => 'Xóa đơn hàng'
            ],

            // Module Pages
            [
                'name' => '|---Add Page',
                'slug' => 'pages.add',
                'description' => 'Thêm trang'
            ],
            [
                'name' => '|---Edit Page',
                'slug' => 'pages.edit',
                'description' => 'Sửa trang'
            ],
            [
                'name' => '|---Delete Page',
                'slug' => 'pages.delete',
                'description' => 'Xóa trang'
            ],

            // Module Posts
            [
                'name' => '|---Add Post',
                'slug' => 'posts.add',
                'description' => 'Thêm bài viết'
            ],
            [
                'name' => '|---Edit Post',
                'slug' => 'posts.edit',
                'description' => 'Sửa bài viết'
            ],

            // Module Roles
            [
                'name' => '|---View Role',
                'slug' => 'roles.view',
                'description' => 'Xem vai trò'
            ],
            [
                'name' => '|---Add Role',
                'slug' => 'roles.add',
                'description' => 'Thêm vai trò'
            ],
            [
                'name' => '|---Edit Role',
                'slug' => 'roles.edit',
                'description' => 'Sửa vai trò'
            ],
            [
                'name' => '|---Delete Role',
                'slug' => 'roles.delete',
                'description' => 'Xóa vai trò'
            ],

            // Module Categories
            [
                'name' => '|---List Category',
                'slug' => 'categorys.list',
                'description' => 'Xem danh sách danh mục'
            ],
            [
                'name' => '|---Add Category',
                'slug' => 'categorys.add',
                'description' => 'Thêm danh mục'
            ],
            [
                'name' => '|---Edit Category',
                'slug' => 'categorys.edit',
                'description' => 'Sửa danh mục'
            ],
            [
                'name' => '|---Delete Category',
                'slug' => 'categorys.delete',
                'description' => 'Xóa danh mục'
            ],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
