@extends('layouts.admin')

@section('title')
    Thêm vai trò
@endsection

@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
                <h5 class="m-0 ">Thêm mới vai trò</h5>
                <div class="form-search form-inline">
                    <form action="#">
                        <input type="" class="form-control form-search" placeholder="Tìm kiếm">
                        <input type="submit" name="btn-search" value="Tìm kiếm" class="btn btn-primary">
                    </form>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('role_store') }}">@csrf
                    <div class="form-group">
                        <label for="name">Tên vai trò</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <strong>Vai trò này có quyền gì?</strong>
                    <small class="form-text text-muted pb-2">Check vào module hoặc các hành động bên dưới để chọn
                        quyền.</small>
                    <!-- List Permission  -->
                    @foreach ($permissions as $moduleName => $modulePermissions)
                        <div class="card my-4 border">
                            <div class="card-header">
                                <input type="checkbox" class="check-all" id="{{ $moduleName }}">
                                <label for="{{ $moduleName }}" class="m-0"><strong>Module
                                        {{ ucfirst($moduleName) }}</strong></label>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach ($modulePermissions as $permission)
                                        <div class="col-md-3">
                                            <input type="checkbox" name="permission_id[]" id="{{ $permission->slug }}"
                                                value="{{ $permission->id }}" class="permission">
                                            <label for="{{ $permission->slug }}">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <input type="submit" name="btn-add" class="btn btn-primary" value="Thêm vai trò">
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $('.check-all').click(function() {
            $(this).closest('.card').find('.permission').prop('checked', this.checked)
        })
    </script>
@endsection
