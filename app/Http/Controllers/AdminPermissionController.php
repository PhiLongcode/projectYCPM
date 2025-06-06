<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class AdminPermissionController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'permission']);
            return $next($request);
        });
    }

    function add()
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->slug)[0];
        });
        return view('admin.permission.add', compact('permissions'));
    }
    function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'name' => 'Tên quyền',
                'slug' => 'Tên slug',
            ]
        );
        Permission::create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description')
        ]);

        return redirect(route('permission_add'))->with('status', "Đã thêm quyền thành công");
    }

    function edit($id)
    {
        $permissions = Permission::all()->groupBy(function ($permission) {
            return explode('.', $permission->slug)[0];
        });
        $permission = Permission::find($id);
        return view('admin.permission.edit', compact('permissions', 'permission'));
    }

    function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'slug' => ['required', 'string', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'name' => 'Tên quyền',
                'slug' => 'Tên slug',
            ]
        );
        Permission::where('id', $id)->update([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description')
        ]);

        return redirect(route('permission_add'))->with('status', "Đã cập nhật quyền thành công");
    }

    function delete($id)
    {
        Permission::where('id', $id)->delete();
        return redirect(route('permission_add'))->with('status', "Đã xóa quyền thành công");
    }
}
