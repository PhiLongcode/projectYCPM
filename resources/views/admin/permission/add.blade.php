@extends('layouts.admin')
@section('title')
    {{ 'Phân quyền' }}
@endsection
@section('content')
    <div id="content" class="container-fluid">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Thêm quyền
                    </div>
                    <div class="card-body">
                        <form action="{{ route('permission_store') }}" method="POST">@csrf
                            <div class="form-group">
                                <label for="name">Tên quyền</label>
                                <input class="form-control" type="text" value="{{ old('name') }}" name="name"
                                    id="name">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="slug">Slug</label>
                                <small class="form-text text-muted pb-2">Ví dụ: posts.add</small>
                                <input class="form-control" value="{{ old('slug') }}" type="text" name="slug"
                                    id="slug">
                                @error('slug')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea class="form-control" type="text" name="description" id="description"> </textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="card">
                    <div class="card-header font-weight-bold">
                        Danh sách quyền
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên quyền</th>
                                    <th scope="col">Slug</th>
                                    <!-- <th scope="col">Mô tả</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $qty = 1;
                                @endphp
                                @foreach ($permissions as $moduleName => $modulePermissions)
                                    <tr>
                                        <td></td>
                                        <td><strong>Module {{ ucfirst($moduleName) }}</strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @foreach ($modulePermissions as $permission)
                                        <tr>
                                            <td>{{ $qty++ }}</td>
                                            <td>|---{{ $permission->name }}</td>
                                            <td>{{ $permission->slug }}</td>
                                            <td>
                                                <a href="{{route('permission_edit',$permission->id)}}" class="btn btn-success btn-sm rounded-0 text-white"
                                                    type="button" data-toggle="tooltip" data-placement="top"
                                                    title="Edit"><i class="fa fa-edit"></i></a>
                                                <a href="{{route('permission_delete',$permission->id)}}"
                                                    onclick="return confirm('Bạn có chắc chắn xóa bản ghi này?')"
                                                    class="btn btn-danger btn-sm rounded-0 text-white" type="button"
                                                    data-toggle="tooltip" data-placement="top" title="Delete"><i
                                                        class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
