@extends('layouts.admin')
@section('title')
    {{ "Danh sách đơn hàng" }}
@endsection
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách đơn hàng</h5>
                <div class="form-search form-inline">
                    <form action="#" method="GET">@csrf
                        <input type="text" name="search" value="{{ request()->input('search') }}"
                            class="form-control form-search" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <form action="{{ route('order_action') }}" method="GET"> @csrf
                <div class="card-body">
                    <div class="analytic">
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ xác
                            nhận<span class="text-muted">({{ $count[0] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'processing']) }}" class="text-primary">Đang xử
                            lý<span class="text-muted">({{ $count[1] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'delivering']) }}" class="text-primary">Đang
                            giao<span class="text-muted">({{ $count[2] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'delivered']) }}" class="text-primary">Đã
                            giao<span class="text-muted">({{ $count[3] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'canceled']) }}" class="text-primary">Đã
                            hủy<span class="text-muted">({{ $count[4] }})</span></a>
                        <a href="" class="text-primary">Doanh số<span class="text-muted">( {{ number_format($sales, 0, ',', '.') }}đ )</span></a>
                    </div>
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="action" name="act">
                            <option>---Chọn tác vụ---</option>
                            @foreach ($list_act as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
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
                                    <td><a href="{{ route('detail_order', $order->id) }}">ĐH{{ $order->id }}</a></td>
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
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            </form>
        </div>
    </div>
@endsection
