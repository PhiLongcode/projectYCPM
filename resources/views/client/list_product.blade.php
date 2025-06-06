@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="clearfix category-product-page">
        <div class="wp-inner">
            <div class="secion" id="breadcrumb-wp">
                <div class="secion-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="" title="">Điện thoại</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="main-content fl-right">
                <div class="section" id="list-product-wp">
                    <div class="section-head clearfix">
                        <div class="filter-wp fl-right">
                            <p class="desc">Hiển thị 45 trên 50 sản phẩm</p>
                            <div class="form-filter">
                                <form method="GET" action="{{ route('list_product_client', ['id' => $id]) }}">@csrf
                                    <input type="hidden" name="id" value="{{ $id }}">
                                    <select name="select">
                                        <option value="0">Sắp xếp</option>
                                        <option value="1" {{ request()->input('select') == '1' ? 'selected' : '' }}>Từ
                                            A-Z</option>
                                        <option value="2" {{ request()->input('select') == '2' ? 'selected' : '' }}>Từ
                                            Z-A</option>
                                        <option value="3" {{ request()->input('select') == '3' ? 'selected' : '' }}>Giá
                                            cao xuống thấp</option>
                                        <option value="4" {{ request()->input('select') == '4' ? 'selected' : '' }}>Giá
                                            thấp lên cao</option>
                                    </select>
                                    <button type="submit">Lọc</button>
                                </form>

                            </div>
                        </div>
                    </div>
                    @foreach ($categories as $category)
                        @if (!isset($keyword))
                            <h3 class="section-title fl-left">{{ $category->name }}</h3>
                        @endif
                        <div class="section-detail">
                            <ul class="list-item clearfix" id="listProduct">
                                @foreach ($products as $product)
                                    @if ($category->id == $product->category_id or $category->id == $product->CategoryProduct->parent)
                                        <li>
                                            <a href="{{ route('detail_product', $product->id) }}" title=""
                                                class="thumb">
                                                <img src="{{ asset($product->thumbnail) }}">
                                            </a>
                                            <a href="{{ route('detail_product', $product->id) }}" title=""
                                                class="product-name">{{ $product->name }}</a>
                                            <div class="price">
                                                <span
                                                    class="new">{{ number_format($product->price - ($product->price / 100) * $product->discount, 0, '.') }}đ
                                                </span>
                                                <span class="old">
                                                    @if ($product->discount != 0)
                                                        <del
                                                            style="font-size: 13px; font-style: italic">{{ number_format($product->price, 0, '.') }}đ</del>
                                                    @endif
                                                </span>
                                            </div>
                                            <div class="action clearfix btn-detail pb-2">
                                                <a href="{{ route('detail_product', $product->id) }}" title=""
                                                    class="p-1 rounded text-center">Xem chi
                                                    tiết <i class="fa-solid fa-eye"></i></a>
                                            </div>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                    {{ $products->links() }}

                </div>
            </div>
            @include('layouts.sidebar.sidebarProduct')
            <div class="section" id="banner-wp">
                <div class="section-detail">
                    <a href="?page=detail_product" title="" class="thumb">
                        <img src="public/images/banner.png" alt="">
                    </a>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        $(function() {
            $('.arrange-price').change(function() {
                let url = $(this).data('url');
                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                let option = $(this).val();
                let categoryId = $('input[name="id"]').val(); // Lấy ID của danh mục đã chọn
                // console.log(url)

                // console.log("oke")

                $.ajax({
                    url: url,
                    method: 'GET',
                    data: {
                        option: option,
                        categoryId: categoryId, // Truyền ID danh mục đã chọn
                        _token: csrfToken
                    },

                    dataType: 'json',
                    success: function(response) {
                        // console.log('a');
                        $('#listProduct').empty();
                        $('#listProduct').html(response.html);

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                    }
                });
            });

        });
    </script>
@endsection
