@extends('layouts.admin')
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Cập nhật danh mục sản phẩm
                    </div>
                    @if (isset($categoryById))
                        <div class="card-body">
                            <form action="{{ route('update_category', $categoryById->id) }}" method="POST">@csrf
                                <div class="form-group">
                                    <label for="name">Tên danh mục</label>
                                    <input class="form-control" type="text" name="name" id="name" value="{{ $categoryById->name }}">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="parent_category">Danh mục cha</label>
                                    <select class="form-control" id="parent_category" name="parent_id">
                                        <option value="0">Chọn danh mục</option>
                                        {!! $htmlOption !!}
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Trạng thái</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="exampleRadios1"
                                            value="pending" {{ $categoryById->status == 'pending' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="exampleRadios1">
                                            Chờ duyệt
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="exampleRadios2"
                                            value="public" {{ $categoryById->status == 'public' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="exampleRadios2">
                                            Công khai
                                        </label>
                                    </div>
                                </div>
                                <button class="btn btn-primary">Cập nhật danh mục</button>
                            </form>
                        </div>
                    @else
                        <div class="card-body">
                            <form action="{{ route('create_cat_product') }}" method="POST">@csrf
                                <div class="form-group">
                                    <label for="name">Tên danh mục</label>
                                    <input class="form-control" type="text" name="name" id="name">
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="parent_category">Danh mục cha</label>
                                    <select class="form-control" id="parent_category" name="parent_id">
                                        <option value="0">Chọn danh mục</option>
                                        {!! $htmlOption !!}
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="">Trạng thái</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="exampleRadios1"
                                            value="pending" checked>
                                        <label class="form-check-label" for="exampleRadios1">
                                            Chờ duyệt
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status" id="exampleRadios2"
                                            value="public">
                                        <label class="form-check-label" for="exampleRadios2">
                                            Công khai
                                        </label>
                                    </div>
                                </div>
                                <button class="btn btn-primary">Thêm mới</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">Tên danh mục</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Trạng thái</th>
                                    <th scope="col">Ngày tạo</th>
                                    <th scope="col">Tác vụ</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @php
                                    $qty = 0;
                                @endphp
                                @foreach ($categories as $category)
                                    @php
                                        $qty++;
                                    @endphp
                                    @if ($category->parent == 0)
                                        <tr>
                                            <th scope="row">{{ $qty }}</th>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->slug }}</td>
                                            <td>{{ $category->status }}</td>
                                            <td>{{ $category->created_at }}</td>
                                        </tr>
                                    @endif
                                @endforeach --}}
                                {!! $categories !!}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
