@extends('layouts.admin')
@section('title')
    {{ "Danh sách trang" }}
@endsection
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ url('admin/page/action') }}" method="POST">@csrf
            <div class="card">
                <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                    <h5 class="m-0 ">Danh sách trang</h5>
                    <div class="form-search form-inline">
                        <form action="#">
                            <input type="text" name="search" value="{{ request()->input('search') }}"
                                class="form-control form-search" placeholder="Tìm kiếm">
                            <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="analytic">
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'active']) }}" class="text-primary">Tất cả<span class="text-muted">({{ $count[0] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'public']) }}" class="text-primary">Hoạt
                            động<span class="text-muted">({{ $count[1] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="text-primary">Chờ duyệt<span class="text-muted">({{ $count[2] }})</span></a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'trash']) }}" class="text-primary">Đã xóa<span
                                class="text-muted">({{ $count[3] }})</span></a>
                    </div>
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="action" name="act">
                            <option>Chọn</option>
                            @foreach ($list_act as $k => $v)
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
                                <th scope="col">Tên trang</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Người tạo</th>
                                <th scope="col">trạng thái</th>
                                <th scope="col">Ngày tạo</th>
                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $qty = ($pages->currentPage() - 1) * $pages->perPage();
                            @endphp
                            @foreach ($pages as $page)
                                @php
                                    $qty++;
                                @endphp
                                <tr>
                                    <td>
                                        <input type="checkbox" name="listcheck[]" value="{{ $page->id }}">
                                    </td>
                                    <td scope="row">{{ $qty }}</td>
                                    <td>{{ $page->name_page }}</td>
                                    <td>{{ $page->slug }}</td>
                                    <td>{{ $page->user_add }}</td>
                                    <td><span
                                            class="  @if ($page->status == 'pending') badge badge-warning
                                        @elseif($page->status == 'public')
                                        badge badge-success
                                        @else
                                        badge badge-dark @endif ">
                                            @if ($page->status == 'pending')
                                                {{ 'Chờ xác nhận' }}
                                            @elseif($page->status == 'public')
                                                {{ 'Công khai' }}
                                            @else
                                                {{ 'Đã xóa' }}
                                            @endif
                                        </span>

                                    </td>
                                    <td>{{ $page->created_at }}</td>
                                    <td><a href="{{route('edit_page', $page->id)}}" class="btn btn-success btn-sm rounded-0" type="button"
                                            data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                class="fa fa-edit"></i></a>
                                        <a href="{{route('delete_page', $page->id)}}" class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="tooltip"
                                            data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{ $pages->appends(request()->query())->links() }}
                </div>
            </div>
        </form>
    </div>
@endsection
