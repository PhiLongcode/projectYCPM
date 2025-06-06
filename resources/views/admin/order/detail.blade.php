@extends('layouts.admin')
@section('title')
    {{ "Chi tiết đơn hàng" }}
@endsection
@section('content')
    <div id="main-content-wp" class="list-product-page">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="wrap clearfix">
            <div id="content" class="detail-exhibition fl-right">
                <div class="section" id="info">
                    <div class="section-head">
                        <h3 class="section-title">Thông tin đơn hàng</h3>
                    </div>
                    <ul class="list-item">
                        <li>
                            <h3 class="detail"><i class="fa-solid fa-barcode"></i> Mã đơn hàng</h3>
                            <span>DH#</span><span class="detail" id="id">{{ $order->id }}</span>
                        </li>
                        <li>
                            <h3 class="detail"><i class="fa-solid fa-location-dot"></i> Địa chỉ nhận hàng</h3>
                            <span class="detail">{{ $order->address }}</span>
                        </li>
                        <li>
                            <h3 class="detail"><i class="fa-solid fa-phone"></i> Số điện thoại</h3>
                            <span class="detail">{{ $order->phone }}</span>
                        </li>
                        <li>
                            <h3 class="detail"><i class="fa-solid fa-truck-fast"></i> Phương thức thanh toán</h3>
                            <span class="detail">
                                @if ($order->payment_method == 'payment-home')
                                    {{ 'Thanh toán tại nhà' }}
                                @else
                                    {{ 'Thanh toán tại cửa hàng' }}
                                @endif
                            </span>
                        </li>
                        <form method="POST" action="{{ route('update_detail_order', $order->id) }}">@csrf
                            <li>
                                <h3 class="detail">Tình trạng đơn hàng</h3>
                                <select name="status" id="select_option_order">
                                    @foreach ($list_act as $k => $v)
                                        <option value="{{ $k }}"
                                            @if ($order->status == $k) selected @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                                <input type="submit" id="btn_update" name="btn_update_status" value="Cập nhật đơn hàng">
                            </li>
                        </form>
                    </ul>
                    <div class="section-head">
                        <h3 class="section-title">Sản phẩm đơn hàng</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table info-exhibition">
                            <thead>
                                <tr>
                                    <td class="thead-text">STT</td>
                                    <td class="thead-text">Ảnh sản phẩm</td>
                                    <td class="thead-text">Tên sản phẩm</td>
                                    <td class="thead-text">Đơn giá</td>
                                    <td class="thead-text">Số lượng</td>
                                    <td class="thead-text">Thành tiền</td>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $qty = 0;
                                @endphp
                                @foreach ($order->orderDetail as $detail)
                                    @php
                                        $qty++;
                                        $count_order = count($order->orderDetail);
                                    @endphp
                                    <tr>
                                        <td class="thead-text">{{ $qty }}</td>
                                        <td class="thead-text">
                                            <div class="thumb">
                                                <img src="{{ asset("$detail->thumbnail") }}" alt="">
                                            </div>
                                        </td>
                                        <td class="thead-text">{{ $detail->product_name }}</td>
                                        <td class="thead-text">{{ number_format($detail->price, 0, ',', '.') }}đ</td>
                                        <td class="thead-text">{{ $detail->qty }}</td>
                                        <td class="thead-text">{{ number_format($detail->sub_total, 0, ',', '.') }}đ</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="section">
                    <h3 class="section-title">Giá trị đơn hàng</h3>
                    <div class="section-detail">
                        <ul class="list-item clearfix">
                            <li>
                                <span class="total-fee">Tổng số lượng</span>
                                <span class="total">Tổng đơn hàng</span>
                            </li>
                            <li>
                                <span class="total-fee">{{ $count_order }}</span>
                                <span class="total">{{ number_format($order->total, 0, ',', '.') }}đ</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="toast"></div>
@endsection
