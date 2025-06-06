<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminRoleController extends Controller
{
    function list()
    {
        if (!Gate::allows('roles.view')) {
            abort(403);
        }
        $roles = Role::all();
        return view('admin.role.list', compact('roles'));
    }

    function add()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->slug)[0];
        });
        return view('admin.role.add', compact('permissions'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|unique:roles,name',
                'description' => 'required',
                'permission_id' => 'nullable|array',
                'permission_id.*' => 'exists:permissions,id'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => ':attribute đã tồn tại trên hệ thống',
            ],
            [
                'name' => 'Tên vai trò',
                'description' => 'Mô tả',
            ]
        );
        $role = Role::create([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);
        $role->permission()->attach($request->input('permission_id'));
        return redirect(route('list_role'))->with('status', "Đã thêm vai trò thành công");
    }

    function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->slug)[0];
        });
        return view('admin.role.edit', compact('permissions', 'role'));
    }

    function update(Request $request, Role $role)
    {
        $request->validate(
            [
                'name' => 'required|unique:roles,name,' . $role->id,
                'permission_id' => 'nullable|array',
                'permission_id.*' => 'exists:permissions,id'
            ],
            [
                'required' => ':attribute không được để trống',
                'unique' => ':attribute đã tồn tại trên hệ thống',
            ],
            [
                'name' => 'Tên vai trò',
                'description' => 'Mô tả',
            ]
        );
        $role->update([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);
        $role->permission()->sync($request->input('permission_id', []));
        return redirect(route('list_role'))->with('status', "Đã cập nhật vai trò thành công");
    }

    function delete(Role $role)
    {
        $role->delete();
        return redirect(route('list_role'))->with('status', "Đã xóa vai trò thành công");
    }
}
