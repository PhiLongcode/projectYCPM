@extends('layouts.admin')
@section('title')
    {{ "Dashboard" }}
@endsection
@section('content')
    <div class="container-fluid py-5">
        <div class="row">
            <div class="col">
                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN HÀNG THÀNH CÔNG</div>
                    <div class="card-body">
                        <h5 class="card-title">{{$count[1]}}</h5>
                        <p class="card-text">Đơn hàng giao dịch thành công</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐANG XỬ LÝ</div>
                    <div class="card-body">
                        <h5 class="card-title">{{$count[0]}}</h5>
                        <p class="card-text">Số lượng đơn hàng đang xử lý</p>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">DOANH SỐ</div>
                    <div class="card-body">
                        <h5 class="card-title">{{ number_format($sales, 0, ',', '.') }}đ</h5>
                        <p class="card-text">Doanh số hệ thống</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">ĐƠN HÀNG HỦY</div>
                    <div class="card-body">
                        <h5 class="card-title">{{$count[2]}}</h5>
                        <p class="card-text">Số đơn bị hủy trong hệ thống</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- end analytic  -->
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Đơn hàng mới</h5>
            </div>
            <form action="{{ route('order_action') }}" method="GET"> @csrf
                <div class="card-body">
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="checkall">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Mã</th>
                                <th scope="col">Khách hàng</th>
                                <th scope="col">Giá trị</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Thời gian</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $qty = ($orders->currentPage() - 1) * $orders->perPage();
                            @endphp
                            @foreach ($orders as $order)
                                @php
                                    $qty++;
                                @endphp

                                <tr>
                                    <td>
                                        <input type="checkbox" name="listcheck[]" value="{{ $order->id }}">
                                    </td>
                                    <td>{{ $qty }}</td>
                                    <td><a href="">ĐH{{ $order->id }}</a></td>
                                    <td>
                                        {{ $order->fullname }}<br>
                                        {{ $order->phone }}
                                    </td>
                                    <td>{{ number_format($order->total, 0, ',', '.') }}đ</td>
                                    <td><span
                                            class="
                                    @if ($order->status == 'pending') badge badge-warning
                                        @elseif ($order->status == 'processing')
                                        badge badge-primary
                                        @elseif ($order->status == 'delivering')
                                        badge badge-danger
                                        @elseif ($order->status == 'delivered')
                                        badge badge-success
                                        @elseif ($order->status == 'canceled')
                                        badge badge-dark @endif
                                    ">
                                            @if ($order->status == 'pending')
                                                {{ 'Chờ xác nhận' }}
                                            @elseif ($order->status == 'processing')
                                                {{ 'Đang xử lý' }}
                                            @elseif ($order->status == 'delivering')
                                                {{ 'Đang gửi' }}
                                            @elseif ($order->status == 'delivered')
                                                {{ 'Đã gửi' }}
                                            @elseif ($order->status == 'canceled')
                                                {{ 'Đã hủy' }}
                                            @endif
                                        </span></td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>
                                        <a href="{{ route('detail_order', $order->id) }}"
                                            class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="{{ route('delete', $order->id) }}"
                                            class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $orders->links() }}
                </div>
            </form>
        </div>

    </div>
@endsection
