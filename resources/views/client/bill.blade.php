@extends('layouts.client')
@section('content')
    <div id="main-content-wp" class="list-product-page">
        <div class="section" id="breadcrumb-wp">
            <div class="wp-inner">
                <div class="section-detail">
                    <ul class="list-item clearfix">
                        <li>
                            <a href="trang-chu.html" title="">Trang chủ</a>
                        </li>
                        <li>
                            <a href="Thong-tin-don-hang.html" title="">Đặt hàng thành công</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="wrap clearfix wp-inner wp_noti_order_success">
            <div class="wp_container_noti_success">
                <h2 class="font-weight-bold h4 mb-2 d-block"><i class="fa-solid fa-circle-check mr-2"></i>ĐẶT HÀNG THÀNH
                    CÔNG</h2>
                <p class="">Cảm ơn quý khách đã đặt hàng tại cửa hàng chúng tôi.</p>
                <p>Thông tin đơn hàng đã được gửi trên email của quý khách, quý khách vui lòng kiểm tra email của mình</p>
                <p>Nhân viên của chúng tôi sẽ liên hệ với bạn để xác nhận đơn hàng, thời gian giao hàng chậm nhất là 48h.
                </p>
            </div>
        </div>
        <div class="wrap clearfix">
            <div id="content" class="detail-exhibition wp-inner">
                <div class="section" id="info">
                    <div class="title h5 font-weight-bold ">Mã đơn hàng: <span
                            class="detail text-order-id">#DH{{ $order->id }}</span></div>
                </div>
                <h5 class="mb-1 mt-3 text-title-info"><i class="fa-solid fa-circle-info"></i>Thông tin khách hàng</h5>
                <div class="section">
                    <div class="table-responsive table-danger">
                        <table class="table info-exhibition">
                            <thead>
                                <tr>
                                    <td>Họ và tên</td>
                                    <td>Số điện thoại</td>
                                    <td>Email</td>
                                    <td>Địa chỉ</td>
                                    <td>Ghi chú</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="font-weight-bold">{{ $order->fullname }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ $order->email }}</td>
                                    <td>{{ $order->address }}</td>
                                    <td>{{ $order->note }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h5 class="text-danger mb-1 mt-3 text-title-info"><i class="fa-solid fa-circle-info"></i>Thông tin đơn
                        hàng</h5>
                    <div class="table-responsive table-danger">
                        <table class="table info-exhibition">
                            <thead class="font-weight-bold">
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
                                @foreach ($order->orderDetail as $orderProduct)
                                    {{-- <td>{{ $orderProduct }}</td> --}}
                                    @php
                                        $qty++;
                                    @endphp
                                    <tr>
                                        <td>{{ $qty }}</td>
                                        <td>
                                            <div class="thumb">
                                                <img style="width:75px;height:auto"
                                                    src="{{ asset($orderProduct->thumbnail) }}" alt="">
                                            </div>
                                        </td>
                                        <td>{{ $orderProduct->product_name }}</td>
                                        <td>{{ number_format($orderProduct->price, 0, ',', '.') }} đ</td>
                                        <td>{{ $orderProduct->qty }}</td>
                                        <td>{{ number_format($orderProduct->sub_total, 0, ',', '.') }} đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="font-weight-bold">
                                <tr>
                                    <td colspan="5" class="thead-text text_total">Tổng tiền</td>
                                    <td class="thead-text">{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="thead-text text_total"><a class="btn-link-product-buy"
                                            href="{{ url('/') }}">Trang chủ</a></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
