<?php

use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminPageController;
use App\Http\Controllers\AdminPermissionController;
use App\Http\Controllers\AdminPostController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\ClientHomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;


Auth::routes();

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // User
    Route::get('/admin/user/list', [AdminUserController::class, 'list'])->name('list-user')->can('users.list');
    Route::get('/admin/user/create', [AdminUserController::class, 'create'])->name('create-user')->can('users.add');
    Route::post('/admin/user/store', [AdminUserController::class, 'store'])->name('store-user');
    Route::get('/admin/user/edit/{user}', [AdminUserController::class, 'edit'])->name('edit-user')->can('users.edit');
    Route::post('/admin/user/update/{user}', [AdminUserController::class, 'update'])->name('update-user');
    Route::get('/admin/user/delete/{id}', [AdminUserController::class, 'delete'])->name('delete-user')->can('users.delete');
    Route::post('/admin/user/action', [AdminUserController::class, 'action'])->name('action-user');

    //Product-category
    Route::get('/admin/product/category/{id?}', [AdminProductController::class, 'list_cat'])->name('list_cat')->can('categorys.list');
    Route::post('/admin/product/create/category', [AdminProductController::class, 'create_cat_product'])->name('create_cat_product')->can('categorys.add');
    Route::post('/admin/product/update/category/{id}', [AdminProductController::class, 'update_category'])->name("update_category")->can('categorys.edit');
    Route::get('/admin/product/update/delete/{id}', [AdminProductController::class, 'delete_category'])->name("delete_category")->can('categorys.delete');

    //Product
    Route::get("/admin/product/add", [AdminProductController::class, 'add'])->name('add_product')->can('products.add');
    Route::post("/admin/product/store", [AdminProductController::class, 'store'])->name('product_store');
    Route::get("/admin/product/list", [AdminProductController::class, 'list'])->name('list_product');
    Route::get("/admin/product/edit/{id}", [AdminProductController::class, 'edit'])->name('edit_product')->can('products.edit');
    Route::post("/admin/product/update/{id}", [AdminProductController::class, 'update'])->name('update_product');
    Route::get('/admin/product/delete/{id}', [AdminProductController::class, 'delete'])->name('delete_product')->can('products.delete');
    Route::post('/admin/product/action', [AdminProductController::class, 'action'])->name('action_product');

    // Order
    Route::get('/admin/order/list', [AdminOrderController::class, 'list_order'])->name('list_order')->can('orders.list');
    Route::get('/admin/order/detail/{id}', [AdminOrderController::class, 'detail_order'])->name('detail_order');
    Route::post('/admin/order/detail/update/{id}', [AdminOrderController::class, 'update_detail_order'])->name('update_detail_order')->can('orders.edit');
    Route::get('/delete/{id}', [AdminOrderController::class, 'delete'])->name('delete')->can('orders.delete');
    Route::get('/order/action', [AdminOrderController::class, 'order_action'])->name('order_action');

    // Page
    Route::get('admin/page/add', [AdminPageController::class, 'add_page'])->name('add_page')->can('pages.add');
    Route::get('admin/page/list', [AdminPageController::class, 'list_page'])->name('list_page');
    Route::get('admin/page/edit/{id}', [AdminPageController::class, 'edit_page'])->name('edit_page')->can('pages.edit');
    Route::post('admin/page/update/{id}', [AdminPageController::class, 'update_page'])->name('update_page');
    Route::get('admin/page/delete/{id}', [AdminPageController::class, 'delete_page'])->name('delete_page')->can('pages.delete');
    Route::post('admin/page/action', [AdminPageController::class, 'action_page'])->name('action_page');
    Route::post('store_page', [AdminPageController::class, 'store_page'])->name('store_page');

    // Post
    Route::get('admin/post/add', [AdminPostController::class, 'add_post'])->name('add_post')->can('posts.add');
    Route::post('admin/post/store', [AdminPostController::class, 'store_post'])->name('store_post');
    Route::get('admin/post/list', [AdminPostController::class, 'list_post'])->name('list_post');
    Route::get('admin/post/cat/add', [AdminPostController::class, 'list_cat'])->name('list_cat_post');
    Route::post('add_cat_post', [AdminPostController::class, 'add_cat_post'])->name('add_cat_post');
    Route::get('edit_cat_post/{id}', [AdminPostController::class, 'edit_cat_post'])->name('edit_cat_post')->can('posts.edit');
    Route::post('update_cat_post/{id}', [AdminPostController::class, 'update_cat_post'])->name('update_cat_post');
    Route::get('delete_cat_post/{id}', [AdminPostController::class, 'delete_cat_post'])->name('delete_cat_post');
    Route::get('delete_post/{id}', [AdminPostController::class, 'delete_post'])->name('delete_post');
    Route::get('edit_post/{id}', [AdminPostController::class, 'edit_post'])->name('edit_post');
    Route::post('post_action', [AdminPostController::class, 'post_action'])->name('post_action');
    Route::post('update_post/{id}', [AdminPostController::class, 'update_post'])->name('update_post');

    // Permission
    Route::get('admin/permission/add', [AdminPermissionController::class, 'add'])->name('permission_add');
    Route::post('admin/permission/store', [AdminPermissionController::class, 'store'])->name('permission_store');
    Route::get('admin/permission/edit/{id}', [AdminPermissionController::class, 'edit'])->name('permission_edit');
    Route::post('admin/permission/update/{id}', [AdminPermissionController::class, 'update'])->name('permission_update');
    Route::get('admin/permission/delete/{id}', [AdminPermissionController::class, 'delete'])->name('permission_delete');

    // Role
    Route::get('admin/role/list', [AdminRoleController::class, 'list'])->name('list_role')->can('roles.view');
    Route::get('admin/role/add', [AdminRoleController::class, 'add'])->name('add_role')->can('roles.add');
    Route::post('admin/role/store', [AdminRoleController::class, 'store'])->name('role_store');
    Route::get('admin/role/edit/{role}', [AdminRoleController::class, 'edit'])->name('role_edit')->can('roles.edit');
    Route::post('admin/role/update/{role}', [AdminRoleController::class, 'update'])->name('role_update');
    Route::get('admin/role/delete/{role}', [AdminRoleController::class, 'delete'])->name('role_delete')->can('roles.delete');
});

Route::get('/{id?}', [ClientHomeController::class, 'home'])->name('home');
Route::get('/detail/product/{id}', [ClientHomeController::class, 'detail_product'])->name('detail_product');
Route::get('/product/list/{id?}', [ClientHomeController::class, 'list_product'])->name('list_product_client');


Route::middleware(['redirectIfNotAuthenticated'])->group(function () {
    Route::get('/product/cart', [ClientHomeController::class, 'cart'])->name('cart');
    Route::get('/add/cart/{productId}', [ClientHomeController::class, 'add_cart'])->name('add_cart');
    Route::post('/update/cart', [ClientHomeController::class, 'update_cart'])->name('update_cart');
    Route::get('/remove/cart/{productId}', [ClientHomeController::class, 'remove_cart'])->name('remove_cart');
    Route::get('/destroy/cart', [ClientHomeController::class, 'destroy_cart'])->name('destroy_cart');
    Route::post('/product/update_ajax', [ClientHomeController::class, 'update_ajax'])->name('update_ajax');
    Route::get('/product/checkout', [ClientHomeController::class, 'checkout'])->name('checkout');
    Route::post('/product/sendmail', [ClientHomeController::class, 'sendmail'])->name('sendmail');
    Route::get('/product/bill', [ClientHomeController::class, 'bill'])->name('bill');
});

//blog
Route::get('/trang-chu/bai-viet', [ClientHomeController::class, 'blog'])->name('blog');
Route::get('/trang-chu/chi-tiet-bai-viet/{id}', [ClientHomeController::class, 'detail_blog'])->name('detail_blog');
//lien-he
Route::get('/trang-chu/lien-he', [ClientHomeController::class, 'contact'])->name('contact');
//gioi-thieu
Route::get('/home/intro', [ClientHomeController::class, 'intro'])->name('intro');
Route::get('/home/product_filter/{id?}', [ClientHomeController::class, 'product_filter'])->name('product_filter');

//SendMailMomo
Route::get('/payMomoSendMail/{fullname}/{address}/{email}/{note}/{phone}/{total}/{payment_method}', [ClientHomeController::class, 'payMomoSendMail'])->name('payMomoSendMail');

