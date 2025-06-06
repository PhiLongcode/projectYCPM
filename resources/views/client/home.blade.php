@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="home-page clearfix">
        <div class="wp-inner">
            <div class="main-content fl-right">
                <div class="section" id="slider-wp">
                    <div class="section-detail">
                        <div class="item">
                            <!-- <img src="http://localhost/hungstore/public/client/images/slider-01.png" alt=""> -->
                        </div>
                        <div class="item">
                            <!-- <img src="http://localhost/hungstore/public/client/images/slider-02.png" alt=""> -->
                        </div>
                        <div class="item">
                            <!-- <img src="http://localhost/hungstore/public/client/images/slider-03.png" alt=""> -->
                        </div>
                    </div>
                </div>
                <div class="section" id="support-wp">
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            <li>
                                <div class="thumb">
                                    <!-- <img src="http://localhost/hungstore/public/client/images/icon-1.png"> -->
                                </div>
                                <h3 class="title">Miễn phí vận chuyển</h3>
                                <p class="desc">Tới tận tay khách hàng</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <!-- <img src="http://localhost/hungstore/public/client/images/icon-2.png"> -->
                                </div>
                                <h3 class="title">Tư vấn 24/7</h3>
                                <p class="desc">1900.9999</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <!-- <img src="http://localhost/hungstore/public/client/images/icon-3.png"> -->
                                </div>
                                <h3 class="title">Tiết kiệm hơn</h3>
                                <p class="desc">Với nhiều ưu đãi cực lớn</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <!-- <img src="http://localhost/hungstore/public/client/images/icon-4.png"> -->
                                </div>
                                <h3 class="title">Thanh toán nhanh</h3>
                                <p class="desc">Hỗ trợ nhiều hình thức</p>
                            </li>
                            <li>
                                <div class="thumb">
                                    <!-- <img src="http://localhost:8000/public/client/images/icon-5.png"> -->
                                </div>
                                <h3 class="title">Đặt hàng online</h3>
                                <p class="desc">Thao tác đơn giản</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- <div class="section" id="feature-product-wp">
                    <div class="section-head">
                        <h3 class="section-title">Sản phẩm nổi bật</h3>
                    </div>
                    <div class="section-detail">
                        <ul class="list-item">
                            <li>
                                <a href="?page=detail_product" title="" class="thumb">
                                    <img src="http://localhost/hungstore/public/client/images/img-pro-05.png">
                                </a>
                                <a href="?page=detail_product" title="" class="product-name">Laptop Lenovo IdeaPad
                                    120S</a>
                                <div class="price">
                                    <span class="new">5.190.000đ</span>
                                    <span class="old">6.190.000đ</span>
                                </div>
                                {{-- <div class="action clearfix">
                                    <a href="?page=cart" title="" class="add-cart fl-left">Thêm giỏ hàng</a>
                                    <a href="?page=checkout" title="" class="buy-now fl-right">Mua ngay</a>
                                </div> --}}
                                <div class="action clearfix btn-detail pb-2">
                                    <a href="" title="" class="p-1 rounded text-center">Xem chi
                                        tiết <i class="fa-solid fa-eye"></i></a>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div> -->
                @foreach ($categories as $category)
                    <div class="section" id="list-product-wp">
                        <div class="section-head">
                            <h3 class="section-title">{{ $category->name }}</h3>
                        </div>
                        <div class="section-detail">
                            <ul class="list-item clearfix">
                                @foreach ($products as $product)
                                    @if ($category->id == $product->category_id OR $category->id == $product->CategoryProduct->parent)
                                        <li>
                                            <a href="{{ route('detail_product', $product->id) }}" title="" class="thumb">
                                                <img src="{{ asset($product->thumbnail) }}">
                                            </a>
                                            <a href="{{ route('detail_product', $product->id) }}" title=""
                                                class="product-name">{{ $product->name }}</a>
                                            <div class="price">
                                                <span class="new">{{ number_format($product->price - ($product->price / 100) * $product->discount, 0, '.') }}đ </span>
                                                <span class="old">@if ($product->discount != 0)
                                                    <del
                                                        style="font-size: 13px; font-style: italic">{{ number_format($product->price, 0, '.') }}đ</del>
                                                @endif</span>
                                            </div>
                                            <div class="action clearfix btn-detail pb-2">
                                                <a href="{{ route('detail_product', $product->id) }}" title="" class="p-1 rounded text-center">Xem chi
                                                    tiết <i class="fa-solid fa-eye"></i></a>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endforeach

            </div>
            @include('layouts.sidebar.sidebar')
        </div>
    </div>
@endsection
