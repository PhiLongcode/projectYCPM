@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Cập nhật người dùng
        </div>
        <div class="card-body">
            @php
                $currentUser = auth()->user();
                $isAdmin = $currentUser->hasRole('admin');
                $editingUserIsAdmin = $user->hasRole('admin');
            @endphp

            @if($editingUserIsAdmin && !$isAdmin)
                <div class="alert alert-warning">
                    Bạn không có quyền chỉnh sửa thông tin của admin
                </div>
            @else
                <form action="{{ route('update-user', $user->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input class="form-control" type="text" value="{{ $user->name }}" name="name" id="name"
                            {{ $editingUserIsAdmin && !$isAdmin ? 'disabled' : '' }}>
                        @error('name')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input class="form-control" type="text" name="email" readonly value="{{ $user->email }}" id="email">
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <input class="form-control" type="password" value="{{ $user->password }}" name="password"
                            id="password" {{ $editingUserIsAdmin && !$isAdmin ? 'disabled' : '' }}>
                        @error('password')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">Xác nhận mật khẩu</label>
                        <input class="form-control" value="{{ $user->password }}" type="password"
                            name="password_confirmation" id="password-confirm"
                            {{ $editingUserIsAdmin && !$isAdmin ? 'disabled' : '' }}>
                    </div>

                    <div class="form-group">
                        <label for="roles">Vai trò</label>
                        @php
                        $selectedRoles = $user->roles->pluck('id')->toArray();
                        @endphp
                        <select name="roles[]" id="roles" class="form-control" multiple
                            {{ $editingUserIsAdmin && !$isAdmin ? 'disabled' : '' }}>
                            @foreach ($roles as $role)
                            @if($role->name !== 'admin' || $isAdmin)
                            <option value="{{ $role->id }}"

                                {{ in_array($role->id, $selectedRoles) ? 'selected' : '' }}
                                {{ $role->name === 'admin' && !$isAdmin ? 'disabled' : '' }}>
                                {{ $role->name }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                        @if(!$isAdmin)
                        <small class="text-muted">Chỉ admin mới có quyền chỉnh sửa thông tin của admin</small>
                        @endif
                    </div>

                    <button class="btn btn-primary" {{ $editingUserIsAdmin && !$isAdmin ? 'disabled' : '' }}>
                        Cập nhật
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection