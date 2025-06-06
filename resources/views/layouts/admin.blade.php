<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/solid.min.css">
    <link rel="stylesheet" href="{{ asset('public/admin/css/style.css') }}">
    <script src="https://cdn.tiny.cloud/1/tcv3y077497x8n19v3ghvpluyygmo4mno2uxmcacikl939ft/tinymce/7/tinymce.min.js"
        referrerpolicy="origin"></script>

    <title>Admintrator</title>
</head>

<body>
    <div id="warpper" class="nav-fixed">
        <nav class="topnav shadow navbar-light bg-white d-flex">
            <div class="navbar-brand"><a href="?">{{ config('app.name', 'Laravel') }}</a></div>
            <div class="nav-right ">
                <div class="btn-group mr-auto">
                    <button type="button" class="btn dropdown" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="plus-icon fas fa-plus-circle"></i>
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="?view=add-post">Thêm bài viết</a>
                        <a class="dropdown-item" href="?view=add-product">Thêm sản phẩm</a>
                        <a class="dropdown-item" href="?view=list-order">Thêm đơn hàng</a>
                    </div>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        {{ Auth::user()->name }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#">Tài khoản</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            Đăng xuất
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </nav>
        <!-- end nav  -->

        <?php $module_active = session('module_active'); ?>

        <div id="page-body" class="d-flex">
            <div id="sidebar" class="bg-white">
                <ul id="sidebar-menu">
                    <li class="nav-link {{ $module_active == 'dashboard' ? 'active' : '' }}">
                        <a href="/dashboard">
                            <div class="nav-link-icon d-inline-flex">
                                <i class="far fa-folder"></i>
                            </div>
                            Dashboard
                        </a>
                        <i class="arrow fas fa-angle-right"></i>
                    </li>

                    @canany(['pages.add', 'pages.delete', 'pages.edit', 'posts.add', 'posts.edit', ''])
                        <li class="nav-link {{ $module_active == 'page' ? 'active' : '' }}">
                            <a href="{{ route('list_page') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Trang
                            </a>
                            <i class="arrow fas fa-angle-right"></i>

                            <ul class="sub-menu">
                                <li><a href="{{ route('add_page') }}">Thêm mới</a></li>
                                <li><a href="{{ route('list_page') }}">Danh sách</a></li>
                            </ul>
                        </li>

                        <li class="nav-link {{ $module_active == 'post' ? 'active' : '' }}">
                            <a href="{{ route('list_post') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Bài viết
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li><a href="{{ route('add_post') }}">Thêm mới</a></li>
                                <li><a href="{{ route('list_post') }}">Danh sách</a></li>
                                <li><a href="{{ route('list_cat_post') }}">Danh mục</a></li>
                            </ul>
                        </li>
                    @endcanany

                    @canany(['products.add', 'products.delete', 'products.edit'])
                        <li class="nav-link {{ $module_active == 'product' ? 'active' : '' }}">
                            <a href="{{ route('list_product') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Sản phẩm
                            </a>
                            <i class="arrow fas fa-angle-{{ $module_active == 'users' ? 'down' : 'right' }}"></i>
                            <ul class="sub-menu">
                                <li><a href="{{ route('add_product') }}">Thêm mới</a></li>
                                <li><a href="{{ route('list_product') }}">Danh sách</a></li>
                                <li><a href="{{ route('list_cat') }}">Danh mục</a></li>
                            </ul>
                        </li>
                    @endcanany

                    @canany(['orders.delete', 'orders.edit', 'orders.list'])
                        <li class="nav-link {{ $module_active == 'order' ? 'down' : 'right' }}">
                            <a href="{{ route('list_order') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Bán hàng
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li><a href="{{ route('list_order') }}">Đơn hàng</a></li>
                            </ul>
                        </li>
                    @endcanany


                    @canany(['users.add', 'users.delete', 'users.edit', 'users.list'])
                        <li class="nav-link {{ $module_active == 'users' ? 'active' : '' }}">
                            <a href="{{ route('list-user') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Users
                            </a>
                            <i class="arrow fas fa-angle-{{ $module_active == 'users' ? 'down' : 'right' }}"></i>

                            <ul class="sub-menu">
                                <li><a href="{{ route('create-user') }}">Thêm mới</a></li>
                                <li><a href="{{ route('list-user') }}">Danh sách</a></li>
                            </ul>
                        </li>
                    @endcanany


                    @canany(['roles.add', 'roles.delete', 'roles.edit', 'roles.view'])
                        <li class="nav-link {{ $module_active == 'permission' ? 'active' : '' }}">
                            <a href="{{ route('permission_add') }}">
                                <div class="nav-link-icon d-inline-flex">
                                    <i class="far fa-folder"></i>
                                </div>
                                Phân quyền
                            </a>
                            <i class="arrow fas fa-angle-right"></i>
                            <ul class="sub-menu">
                                <li><a href="{{ route('permission_add') }}">Quyền</a></li>
                                <li><a href="{{ route('add_role') }}">Thêm vai trò</a></li>
                                <li><a href="{{ route('list_role') }}">Danh sách vai trò</a></li>
                            </ul>
                        </li>
                    @endcanany


                    <!-- <li class="nav-link"><a>Bài viết</a>
                        <ul class="sub-menu">
                            <li><a>Thêm mới</a></li>
                            <li><a>Danh sách</a></li>
                            <li><a>Thêm danh mục</a></li>
                            <li><a>Danh sách danh mục</a></li>
                        </ul>
                    </li>
                    <li class="nav-link"><a>Sản phẩm</a></li>
                    <li class="nav-link"><a>Đơn hàng</a></li>
                    <li class="nav-link"><a>Hệ thống</a></li> -->

                </ul>
            </div>
            <div id="wp-content">
                @yield('content')
            </div>
        </div>


    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="{{ asset('public/admin/js/app.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script>
        tinymce.init({
            selector: 'textarea#tiny'
        });
        // Prevent Bootstrap dialog from blocking focusin
        $(document).on('focusin', function(e) {
            if ($(e.target).closest(".tox-tinymce, .tox-tinymce-aux, .moxman-window, .tam-assetmanager-root")
                .length) {
                e.stopImmediatePropagation();
            }
        });
    </script>

</body>

</html>
