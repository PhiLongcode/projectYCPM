@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Danh sách sản phẩm</h5>
                <div class="form-search form-inline">
                    <form action="{{ route('list_product') }}">
                        <input type="text" class="form-control form-search" name="keyword" placeholder="Tìm kiếm"
                            value="{{ request()->input('keyword') }}">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <form action="{{ route('action_product') }}" method="post">@csrf
                <div class="card-body">
                    <div class="analytic">
                        <a href="{{ request()->fullUrlWithQuery(['status' => '']) }}" class="text-primary">Tất cả<span
                                class="text-muted">({{ $count[0] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ
                            duyệt<span class="text-muted">({{ $count[1] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Đã xóa<span
                                class="text-muted">({{ $count[2] }})</span></a>
                    </div>
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="list_action" name="list_action">
                            <option>Chọn</option>
                            @foreach ($list_action as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <table class="table table-striped table-checkall">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <input name="checkall" type="checkbox">
                                </th>
                                <th scope="col">#</th>
                                <th scope="col">Ảnh</th>
                                <th scope="col">Tên sản phẩm</th>
                                <th scope="col">Giá</th>
                                <th scope="col">Danh mục</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Trạng thái</th>
                                @if (request()->input('status') != 'trash')
                                    <th scope="col">Tác vụ</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            <?php $qty = ($products->currentPage() - 1) * $products->perPage(); ?>
                            @foreach ($products as $product)
                                <?php $qty++; ?>
                                <tr class="">
                                    <td>
                                        <input type="checkbox" name="list_check[]" value="{{ $product->id }}">
                                    </td>
                                    <td>{{ $qty }}</td>
                                    <td style="width: 200px;height: 100px;"><img style="max-width: 90%; height:auto"
                                            src="{{ asset($product->thumbnail) }}" alt=""></td>
                                    <td><a href="{{ route('edit_product', $product->id) }}">{{ $product->name }}</a></td>
                                    <td>{{ number_format($product->price - ($product->price / 100) * $product->discount, 0, '.') }}đ
                                        @if ($product->discount != 0)
                                            <del
                                                style="font-size: 13px; font-style: italic">{{ number_format($product->price, 0, '.') }}đ</del>
                                        @endif
                                    </td>
                                    <td>{{ $product->CategoryProduct->name }}</td>
                                    <td>{{ $product->created_at }}</td>
                                    <td>
                                        @if (request()->input('status') != 'trash')
                                            <span
                                                class="badge badge-{{ $product->status == 'pending' ? 'primary' : 'success' }}">{{ $product->status == 'pending' ? 'Chờ duyệt' : 'Đã xác nhận' }}</span>
                                        @else
                                            <span class="badge badge-danger">Tạm xóa</span>
                                        @endif
                                    </td>
                                    <td style="width:100px">
                                        @if (request()->input('status') != 'trash')
                                            <a href="{{ route('edit_product', $product->id) }}"
                                                class="btn btn-success btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                    class="fa fa-edit"></i></a>
                                            <a href="{{ route('delete_product', $product->id) }}"
                                                class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                data-toggle="tooltip" data-placement="top" title="Delete"
                                                onclick="return confirm('Bạn có chắc chắn xóa bản ghi này?')"><i
                                                    class="fa fa-trash"></i></a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $products->links() }}
                </div>
            </form>

        </div>
    </div>
@endsection
