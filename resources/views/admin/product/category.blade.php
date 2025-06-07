@extends('layouts.admin')
@section('content')
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div id="content" class="container-fluid">
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        {{ isset($categoryById) ? 'Cập nhật danh mục sản phẩm' : 'Thêm danh mục sản phẩm' }}
                    </div>
                    @if (isset($categoryById))
                        <div class="card-body">
                            <form action="{{ route('update_category', $categoryById->id) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Tên danh mục</label>
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name', $categoryById->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="parent_category">Danh mục cha</label>
                                    <select class="form-control @error('parent_id') is-invalid @enderror" 
                                            id="parent_category" 
                                            name="parent_id">
                                        <option value="0">Chọn danh mục</option>
                                        {!! $htmlOption !!}
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="status" 
                                               id="status_pending"
                                               value="pending" 
                                               {{ old('status', $categoryById->status) == 'pending' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_pending">
                                            Chờ duyệt
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="radio" 
                                               name="status" 
                                               id="status_public"
                                               value="public" 
                                               {{ old('status', $categoryById->status) == 'public' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_public">
                                            Công khai
                                        </label>
                                    </div>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Cập nhật danh mục
                                </button>
                                <a href="{{ route('list_cat') }}" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Hủy
                                </a>
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
