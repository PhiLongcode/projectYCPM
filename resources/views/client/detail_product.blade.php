@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix detail-product-page">
        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="{{ url('/') }}" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="" title="">Điện thoại</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                <div class="section" id="detail-product-wp">
                    <form action="{{ route('add_cart', $product->id) }}" method="GET">@csrf
                        <div class="section-detail clearfix">
                            <div class="thumb-wp fl-left">
                                <a href="" title="" id="main-thumb" style="position: relative">
                                    <img id="zoom" src="{{ ASSET("$product->thumbnail") }}"
                                        data-zoom-image="{{ ASSET("$product->thumbnail") }}" />
                                </a>
                                <div id="list-thumb">
                                    @foreach (explode(',', $product->images) as $image)
                                        <a href="" data-image="{{ ASSET("$image") }}"
                                            data-zoom-image="{{ ASSET("$image") }}">
                                            <img id="zoom" src="{{ ASSET("$image") }}" />
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <div class="thumb-respon-wp fl-left">
                                <img src="{{ $product->thumbnail }}" alt="">
                            </div>
                            <div class="info fl-right">
                                <h3 class="product-name">{{ $product->name }}</h3>
                                <div class="desc">
                                    <p>{!! nl2br($product->desc) !!}</p>
                                </div>
                                <div class="num-product">
                                    <span class="title">Sản phẩm: </span>
                                    <span class="status">Còn hàng</span>
                                </div>
                                <p class="price">{{ number_format($product->price - ($product->price / 100) * $product->discount, 0, '.') }}đ @if ($product->discount != 0)
                                    <del
                                        style="font-size: 13px; font-style: italic">{{ number_format($product->price, 0, '.') }}đ</del>
                                @endif</p>
                                <div id="num-order-wp">
                                    <a title="" id="minus"><i class="fa fa-minus"></i></a>
                                    <input type="text" name="num-order" value="1" id="num-order">
                                    <a title="" id="plus"><i class="fa fa-plus"></i></a>
                                </div>
                                <button title="Thêm giỏ hàng" class="add-cart">Thêm giỏ hàng</button>
                                <script>
                                    $(document).ready(function() {
                                        $('.add-cart').click(function(event) {

                                            const swalWithBootstrapButtons = Swal.mixin({
                                                buttonsStyling: false,
                                                showCancelButton: false,
                                                showConfirmButton: false
                                            });

                                            swalWithBootstrapButtons.fire({
                                                title: 'Thêm sản phẩm thành công',
                                                text: 'Vui lòng kiểm tra giỏ hàng của bạn',
                                                icon: 'success',
                                                timer: 2000, // Thời gian tự động đóng sau 2 giây
                                                timerProgressBar: true,
                                                onBeforeOpen: () => {
                                                    swalWithBootstrapButtons.showLoading();
                                                }
                                            })
                                        });
                                    });
                                </script>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="section-detail">
                    <div class="cps-block-content_btn-showmore" style="">
                        <div>
                            <a class="btn-show-more button__content-show-more">
                                Xem thêm
                                <div>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="10"
                                        height="10">
                                        <path
                                            d="M224 416c-8.188 0-16.38-3.125-22.62-9.375l-192-192c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L224 338.8l169.4-169.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-192 192C240.4 412.9 232.2 416 224 416z">
                                        </path>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="cps-block-content_full-description"
                        style="display: none;letter-spacing: 1px;
                    ">
                        {!! $product->detail !!}
                    </div>
                    <div class="fb-comments" data-href="{{ url()->current() }}" data-width="100%" data-numposts="5"></div>
                </div>
                <script>
                    $(document).ready(function() {
                        $('.btn-show-more').click(function() {
                            $(this).closest('.section-detail').find('.cps-block-content_btn-showmore').hide();
                            $(this).closest('.section-detail').find('.cps-block-content_full-description').show();
                        });
                    });
                </script>

                <div class="section" id="same-category-wp">
                    <div class="section-head">
                        <h3 class="section-title">Sản phẩm mới</h3>
                    </div>
                    {{-- <div class="section-detail">
                        <ul class="list-item clearfix" id="listProduct">
                            @foreach ($products as $product)
                                <li style="height: 300px">
                                    <a href="{{ route('detail_product', ['product_id' => $product->product_id]) }}"
                                        title="" class="thumb">
                                        <img class="img-product" src="{{ ASSET("$product->thumbnail") }}">
                                    </a>
                                    <a href="{{ route('detail_product', ['product_id' => $product->product_id]) }}"
                                        title="" class="product-name">{{ $product->product_name }}</a>
                                    <div class="price">
                                        <span class="new">{{ number_format($product->price, 0, '.', ',') }}đ</span>
                                    </div>
                                    <div class="action clearfix btn-detail pb-2">
                                        <a href="{{ route('detail_product', ['product_id' => $product->product_id]) }}"
                                            title="" class="p-1 rounded text-center">Xem chi tiết <i
                                                class="fa-solid fa-eye"></i></a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div> --}}
                </div>
            </div>
            @include('layouts.sidebar.sidebar')
        </div>
    </div>
@endsection
