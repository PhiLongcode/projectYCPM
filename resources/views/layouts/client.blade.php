<?php
use App\Models\CategoryProduct;

$htmlSelect = '';

if (!function_exists('categoryRecursive')) {
    function categoryRecursive($id, $text = '', $htmlSelect)
    {
        $category = CategoryProduct::all();
        foreach ($category as $value) {
            if ($value->parent == $id) {
                $htmlSelect .= '<li>';
                $htmlSelect .= "<a href='' title=''>" . $value->name . '</a>';
                if (isset($value->id)) {
                    $htmlSelect .= '<ul class="sub-menu">';
                    $htmlSelect = categoryRecursive($value->id, $text . '--', $htmlSelect);
                    $htmlSelect .= '</ul>';
                }
                $htmlSelect .= '</li>';
            }
        }
        return $htmlSelect;
    }
}

$htmlSelect = categoryRecursive(0, '', $htmlSelect);
?>
<!DOCTYPE html>
<html>

<head>
    <title>HUNGSTORE</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ ASSET('/public/client/reset.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ ASSET('/public/client/css/carousel/owl.carousel.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ ASSET('/public/client/css/carousel/owl.theme.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ ASSET('/public/client/css/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ ASSET('/public/client/style.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ ASSET('/public/client/responsive.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="{{ ASSET('/public/client/js/jquery-2.2.4.min.js') }}" type="text/javascript"></script>
    <script src="{{ ASSET('/public/client/js/elevatezoom-master/jquery.elevatezoom.js') }}" type="text/javascript">
    </script>

    <script src="{{ ASSET('/public/client/js/bootstrap/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ ASSET('/public/client/js/carousel/owl.carousel.js') }}" type="text/javascript"></script>
    <script src="{{ ASSET('/public/client/js/main.js') }}" type="text/javascript"></script>
    <script src="{{ ASSET('/public/client/js/update_ajax.js') }}"></script>
    <script src="{{ ASSET('/public/client/js/add_cart.js') }}"></script>
    <script src="{{ ASSET('/public/client/js/product-filter.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div id="site">
        <div id="container">
            <div id="header-wp">
                <div id="head-top" class="clearfix">
                    <div class="wp-inner">
                        <a href="" title="" id="payment-link" class="fl-left">Hình thức thanh toán</a>
                        <div id="main-menu-wp" class="fl-right">
                            <ul id="main-menu" class="clearfix">
                                <li>
                                    <a href="{{ url('/') }}" title="">Trang chủ</a>
                                </li>
                                <li>
                                    <a href="{{ route('list_product_client') }}" title="">Sản phẩm</a>
                                </li>
                                <li>
                                    <a href="{{ route('blog') }}" title="">Blog</a>
                                </li>
                                <li>
                                    <a href="{{ route('intro') }}" title="">Giới thiệu</a>
                                </li>
                                <li>
                                    <a href="{{ route('contact') }}" title="">Liên hệ</a>
                                </li>
                                <li>
                                    @if(Auth::user())
                                    <div class="dropdown">
                                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            {{ Auth::user()->name }}
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">Tài khoản</a>
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                Đăng xuất
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
                <div id="head-body" class="clearfix">
                    <div class="wp-inner">
                        <a href="{{ url('/') }}" title="" id="logo" class="fl-left"><img
                                src="{{ ASSET('public/client/images/logo.png') }}" /></a>
                        <div id="search-wp" class="fl-left">
                            <form method="GET" action="{{ route('list_product_client') }}">@csrf
                                <input type="text" name="search" id="s" value="{{ request()->input('search') }}"
                                    placeholder="Nhập từ khóa tìm kiếm tại đây!">
                                <button type="submit" id="sm-s">Tìm kiếm</button>
                            </form>
                        </div>
                        <div id="action-wp" class="fl-right">
                            <div id="advisory-wp" class="fl-left">
                                <span class="title">Tư vấn</span>
                                <span class="phone">0987.654.321</span>
                            </div>
                            <div id="btn-respon" class="fl-right"><i class="fa fa-bars" aria-hidden="true"></i></div>
                            <a href="{{ route('cart') }}" title="giỏ hàng" id="cart-respon-wp" class="fl-right">
                                <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                <span id="num">{{ Cart::count() }}</span>
                            </a>

                            <div id="cart-wp" class="fl-right">
                                <div id="btn-cart">
                                    <a href="{{ route('cart') }}" style="color: white">
                                        <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                                        <span id="num">{{ Cart::count() }}</span>
                                    </a>
                                </div>
                                <div id="dropdown">

                                    @if (Cart::count() > 0)
                                        <p class="desc">Có <span>{{ Cart::count() }} sản phẩm</span> trong giỏ hàng
                                        </p>
                                        <ul class="list-cart">
                                            @foreach (Cart::content() as $product)
                                                <li class="clearfix">
                                                    <a href="{{ route('detail_product', $product->id) }}"
                                                        title="" class="thumb fl-left">
                                                        <img src="{{ asset($product->options->thumbnail) }}"
                                                            alt="">
                                                    </a>
                                                    <div class="info fl-right">
                                                        <a href="" title=""
                                                            class="product-name">{{ $product->name }}</a>
                                                        <p class="price">
                                                            {{ number_format($product->priceTotal, 0, ',', '.') }}đ</p>
                                                        <p class="qty">Số lượng: <span>{{ $product->qty }}</span>
                                                        </p>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="total-price clearfix">
                                            <p class="title fl-left">Tổng:</p>
                                            <p class="price fl-right">{{ Cart::total() }} đ</p>
                                        </div>
                                        <div class="action-cart clearfix">
                                            <a href="{{ route('cart') }}" title="Giỏ hàng"
                                                class="view-cart fl-left">Giỏ hàng</a>
                                            <a href="{{ route('checkout') }}" title="Thanh toán"
                                                class="checkout fl-right">Thanh
                                                toán</a>
                                        </div>
                                    @else
                                        <div id="cart_null">
                                            <p style="color: black; font-style:italic">Chưa có sản phẩm</p>
                                            <img src="{{ asset('public/client/images/no-cart.png') }}"
                                                alt="">
                                        </div>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="wp-content">
                @yield('content')

            </div>
            <div id="footer-wp">
                <div id="foot-body">
                    <div class="wp-inner clearfix">
                        <div class="block" id="info-company">
                            <h3 class="title">HUNGSTORE</h3>
                            <p class="desc">ISMART luôn cung cấp luôn là sản phẩm chính hãng có thông tin rõ ràng,
                                chính sách ưu đãi cực lớn cho khách hàng có thẻ thành viên.</p>
                            <div id="payment">
                                <div class="thumb">
                                    <img src="public/client/images/img-foot.png" alt="Payment method icons including Visa Mastercard and cash with a friendly and welcoming tone set against a clean background" />
                                </div>
                            </div>
                        </div>
                        <div class="block menu-ft" id="info-shop">
                            <h3 class="title">Thông tin cửa hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <p>256/15 Đường Dương Quảng Hàm - Phường 5 - Gò Vấp </p>
                                </li>
                                <li>
                                    <p>0987.654.321 - 0989.989.989</p>
                                </li>
                                <li>
                                    <p>vshop@gmail.com</p>
                                </li>
                            </ul>
                        </div>
                        <div class="block menu-ft policy" id="info-shop">
                            <h3 class="title">Chính sách mua hàng</h3>
                            <ul class="list-item">
                                <li>
                                    <a href="" title="">Quy định - chính sách</a>
                                </li>
                                <li>
                                    <a href="" title="">Chính sách bảo hành - đổi trả</a>
                                </li>
                                <li>
                                    <a href="" title="">Chính sách hội viện</a>
                                </li>
                                <li>
                                    <a href="" title="">Giao hàng - lắp đặt</a>
                                </li>
                            </ul>
                        </div>
                        <div class="block" id="newfeed">
                            <h3 class="title">Bảng tin</h3>
                            <p class="desc">Đăng ký với chung tôi để nhận được thông tin ưu đãi sớm nhất</p>
                            <div id="form-reg">
                                <form method="POST" action="">
                                    <input type="email" name="email" id="email"
                                        placeholder="Nhập email tại đây">
                                    <button type="submit" id="sm-reg">Đăng ký</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="foot-bot">
                    <div class="wp-inner">
                        <p id="copyright">© Bản quyền thuộc về hungpham</p>
                    </div>
                </div>
            </div>
        </div>
        <div id="menu-respon">
            <a href="{{ url('/') }}" title="" class="logo">HUNGSTORE</a>
            <div id="menu-respon-wp">
                <ul class="" id="main-menu-respon">
                    <?php echo $htmlSelect; ?>
                </ul>
            </div>
        </div>
        <div id="btn-top"><img src="public/client/images/icon-to-top.png" alt="" /></div>
        <div id="fb-root"></div>
        <script>
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.8&appId=849340975164592";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
</body>

</html>
