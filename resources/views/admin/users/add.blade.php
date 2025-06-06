@extends('layouts.admin')
@section('content')
    <div id="content" class="container-fluid">
        <div class="card">
            <div class="card-header font-weight-bold">
                Thêm người dùng
            </div>
            <div class="card-body">
                <form action="{{ route('store-user') }}" method="POST">@csrf
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input class="form-control" type="text" name="name" id="name" value="{{ old("name") }}">
                        @error("name")
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="email" name="email" id="email" >
                        @error("email")
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="email">Mật khẩu</label>
                        <input class="form-control" type="password" name="password" id="email">
                        @error("password")
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- <div class="form-group">
                        <label for="roles">Roles</label>
                        <select name="roles[]" id="roles" class="form-control" multiple>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}" {{ in_array($role->id, $selectedRoles) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}
                    <button class="btn btn-primary">Thêm mới</button>
                </form>
            </div>
        </div>
    </div>
@endsection
