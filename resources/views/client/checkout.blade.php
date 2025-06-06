@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="checkout-page">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="?page=home" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="" title="">Thanh toán</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="wrapper" class="wp-inner clearfix">
            <form method="POST" action="{{ route('sendmail') }}" name="form-checkout">@csrf
                <div class="section" id="customer-info-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin khách hàng</h1>
                    </div>
                    <div class="section-detail">
                        <div class="form-row clearfix">
                            <div class="form-col fl-left">
                                <label for="fullname">Họ tên</label>
                                <input type="text" name="fullname" id="fullname" value="{{ old('fullname') }}">
                                @error('fullname')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col fl-right">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email">
                                @error('email')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row clearfix">
                            <div class="form-col fl-left">
                                <label for="address">Địa chỉ</label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}">
                                @error('address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-col fl-right">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-col">
                                <label for="notes">Ghi chú</label>
                                <textarea name="note"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section" id="order-review-wp">
                    <div class="section-head">
                        <h1 class="section-title">Thông tin đơn hàng</h1>
                    </div>
                    <div class="section-detail">
                        <table class="shop-table">
                            <thead>
                                <tr>
                                    <td>Sản phẩm</td>
                                    <td>Tổng</td>
                                </tr>
                            </thead>
                            <tbody>
                                @if (Cart::count() > 0)
                                    @foreach (Cart::content() as $product)
                                        <tr class="cart-item">
                                            <td class="product-name">{{ $product->name }}<strong class="product-quantity">x
                                                    {{ $product->qty }}</strong></td>
                                            <td class="product-total">
                                                {{ number_format($product->priceTotal, 0, ',', '.') }}đ</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <td>Chưa có sản phẩm nào!</td>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr class="order-total">
                                    <td>Tổng đơn hàng:</td>
                                    <td><strong class="total-price">{{ Cart::total() }} đ</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div id="payment-checkout-wp">
                            <ul id="payment_methods">
                                <li>
                                    <input type="radio" id="direct-payment" name="payment-method" value="direct-payment">
                                    <label for="direct-payment">Thanh toán tại cửa hàng</label>
                                </li>
                                <li>
                                    <input type="radio" id="payment-home" name="payment-method" value="payment-home">
                                    <label for="payment-home">Thanh toán tại nhà</label>
                                </li>
                            </ul>
                            @error('payment_method')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="place-order-wp clearfix">
                            <input type="submit" name="order_now" id="order-now" value="Đặt hàng ngay">
                            <div id="check_out">
                                <input type="submit" name="payUrl" id="order-momo" value="Thanh toán MoMo">
                                <img id="img_momo"
                                    src="{{ asset('public/client/images/momo_icon_square_pinkbg_RGB.png') }}"
                                    alt="">
                            </div>
                        </div>

                        <style>
                            #check_out {
                                position: relative;
                                display: inline-block;
                            }

                            #order-momo {
                                background-color: #ee79a2;
                                margin-right: 10px;
                                transition: 0.5s;
                                position: relative;
                                z-index: 1;
                            }

                            #order-momo:hover {
                                background-color: #dd5a88;
                            }

                            #img_momo {
                                max-width: 19%;
                                height: auto;
                                position: absolute;
                                right: -30px;
                                top: 50%;
                                transform: translateY(-50%);
                            }
                        </style>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
