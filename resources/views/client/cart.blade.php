@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="cart-page">
        @if (session('status'))
            <script>
                const data = "Cập nhật giỏ hàng thành công"
                Swal.fire({
                    position: "mid-end",
                    icon: "success",
                    title: data,
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>
        @endif
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="{{ url('/') }}" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="{{ route('cart') }}" title="">Giỏ hàng</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div id="wrapper" class="wp-inner clearfix">
            <p style="padding-bottom: 10px; color:red">Hiện có {{ Cart::count() }} sản phẩm trong giỏ hàng</p>
            @if (Cart::count() > 0)
                <form action="{{ route('update_cart') }}" method="POST" id="update-cart-form"
                    enctype="multipart/form-data">@csrf
                    <div class="section" id="info-cart-wp">
                        <div class="section-detail table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>Mã sản phẩm</td>
                                        <td>Ảnh sản phẩm</td>
                                        <td>Tên sản phẩm</td>
                                        <td>Giá sản phẩm</td>
                                        <td>Số lượng</td>
                                        <td colspan="2">Thành tiền</td>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach (Cart::content() as $product)
                                        <tr>
                                            <td>ĐHS{{ $product->id }}</td>
                                            <td>
                                                <a href="{{ route('detail_product', $product->id) }}" title="" class="thumb">
                                                    <img style="padding-top: 15px;"src="{{ asset($product->options->thumbnail) }}"
                                                        alt="">
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('detail_product', $product->id) }}" title=""
                                                    class="name-product">{{ $product->name }}</a>
                                            </td>
                                            <td>{{ number_format($product->price, 0, ',', '.') }}đ</td>
                                            <td>
                                                <input type="number" min="1" max="10"
                                                    name="qty[{{ $product->rowId }}]" value="{{ $product->qty }}"
                                                    class="num-order" data-product_rowId="{{ $product->rowId }}"
                                                    data-url="{{ route('update_ajax') }}">

                                            </td>
                                            <td id="sub-total-{{ $product->rowId }}">{{ number_format($product->priceTotal, 0, ',', '.') }}đ
                                            </td>
                                            <td>
                                                <a href="{{ route('remove_cart', $product->rowId) }}"
                                                    class="del-product"><i class="fa fa-trash-o"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <div class="clearfix">
                                                <p id="total-price" class="fl-right">Tổng giá:
                                                    <span>{{ Cart::total() }} đ</span>
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="7">
                                            <div class="clearfix">
                                                <div class="fl-right">
                                                    <input type="submit" value="Cập nhật giỏ hàng" name="update-cart"
                                                        id="update-cart" placeholder="Cập nhật sản phẩm">
                                                    <a href="{{ route('checkout') }}" title="" id="checkout-cart">Thanh
                                                        toán</a>

                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            $('.del-product').click(function(e) {
                                e.preventDefault(); // Ngăn chặn hành động mặc định của liên kết
                                var deleteUrl = $(this).attr('href'); // Lấy đường dẫn xóa từ thẻ a

                                Swal.fire({
                                    title: 'Bạn có muốn xóa sản phẩm',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonText: 'Xóa sản phẩm',
                                    cancelButtonText: 'Hủy xóa',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        Swal.fire({
                                            title: 'Đã xóa sản phẩm thành công',
                                            text: 'Kiểm tra lại giỏ hàng của bạn',
                                            icon: 'success',
                                            showConfirmButton: false,
                                            timer: 1500 // Thời gian tự động đóng sau 2 giây
                                        }).then(() => {
                                            // Chuyển hướng đến đường dẫn xóa sản phẩm
                                            window.location.href = deleteUrl;
                                        });
                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                        Swal.fire({
                                            title: 'Đã hủy xóa sản phẩm',
                                            icon: 'info',
                                            showConfirmButton: false,
                                            timer: 2000 // Thời gian tự động đóng sau 2 giây
                                        });
                                    }
                                });
                            });
                        });
                    </script>

                    <div class="section" id="action-cart-wp">
                        <div class="section-detail">
                            <p class="title">Click vào <span>“Cập nhật giỏ hàng”</span> để cập nhật số lượng. Nhập vào số
                                lượng
                                <span>0</span> để xóa sản phẩm khỏi giỏ hàng. Nhấn vào thanh toán để hoàn tất mua hàng.
                            </p>
                            <a href="{{ url('/') }}" title="" id="buy-more">Mua tiếp</a><br />
                            <a href="{{ route('destroy_cart') }}" title="" id="delete-cart" class="delete-cart">Xóa
                                giỏ hàng</a>
                        </div>
                    </div>
                </form>
                <script>
                    $(document).ready(function() {
                        $('.delete-cart').click(function(event) {
                            event.preventDefault(); // Ngăn chặn hành động mặc định của liên kết
                            var deleteUrl = $(this).attr('href'); // Lấy đường dẫn xóa từ thẻ a
                            Swal.fire({
                                title: 'Bạn muốn xóa toàn bộ sản phẩm',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Xóa toàn bộ sản phẩm',
                                cancelButtonText: 'Hủy xóa'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        title: 'Đã xóa sản phẩm thành công',
                                        text: 'Kiểm tra lại giỏ hàng của bạn',
                                        icon: 'success',
                                        showConfirmButton: false,
                                        timer: 2000 // Thời gian tự động đóng sau 2 giây
                                    });

                                    // Thực hiện hành động xóa sản phẩm ở đây
                                    // ...
                                    window.location.href = deleteUrl;

                                } else if (result.dismiss === Swal.DismissReason.cancel) {
                                    Swal.fire({
                                        title: 'Đã hủy xóa sản phẩm',
                                        icon: 'info',
                                        showConfirmButton: false,
                                        timer: 2000 // Thời gian tự động đóng sau 2 giây
                                    });
                                }
                            });
                        });
                    });
                </script>
            @else
                <div id="wrapper" class="wp-inner clearfix mt-3">
                    <div class="wp-img d-flex align-items-center justify-content-center" style="height: 100vh;">
                        <img src="{{ asset('public/client/images/no-cart.png') }}" alt="" class="ml-auto mr-auto">
                        <div class="wp-btn mt-3">
                            <a href="{{ url('/') }}" type="button"
                                style="display: inline-block;
                        padding: 10px 30px;
                        font-size: 16px;
                        background: red;
                        color: #fff;
                        border-radius: 5px;
                        text-transform: uppercase">Mua
                                sắm ngay</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <script>
        $(function() {
            $(".num-order").change(function() {
                var product_rowId = $(this).attr('data-product_rowId');
                var qty = $(this).val();
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var data = {
                    product_rowId: product_rowId,
                    qty: qty,
                    _token: csrfToken
                };
                var URL = $(this).data('url');
                $.ajax({
                    url: URL,
                    method: 'POST',
                    data: data,
                    dataType: 'json',
                    success: function(data) {
                        $("#sub-total-" + product_rowId).text(data.priceTotal);
                        $("#total-price span").text(data.total);
                        // console.log(data);
                        // console.log('a');

                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(xhr);
                    }
                });
            });
        });
    </script>

@endsection
