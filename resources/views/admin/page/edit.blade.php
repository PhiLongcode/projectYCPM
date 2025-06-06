@extends('layouts.admin')
@section('title')
    {{ "Chỉnh sửa trang" }}
@endsection
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Chỉnh sửa trang
            </div>
            <div class="card-body">
                <form action="{{ route('update_page', $page->id) }}" method="post">@csrf
                    <div class="form-group">
                        <label for="name">Tên trang</label>
                        <input class="form-control" value="{{ $page->name_page }}" type="text" name="name_page"
                            id="name">
                    </div>
                    <div class="form-group">
                        <label for="">Trạng thái</label>
                        <div class="form-check">
                            <input class="form-check-input" @if ($page->status == 'pending') checked @endif type="radio"
                                name="exampleRadios" id="exampleRadios1" value="pending" checked>
                            <label class="form-check-label" for="exampleRadios1">
                                Chờ duyệt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" @if ($page->status == 'public') checked @endif type="radio"
                                name="exampleRadios" id="exampleRadios2" value="public">
                            <label class="form-check-label" for="exampleRadios2">
                                Công khai
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật trang</button>
                </form>
            </div>
        </div>
    </div>
@endsection
