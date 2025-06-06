<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'users']);
            return $next($request);
        });
    }


    public function list(Request $request)
    {
        $status = $request->input('status');
        $list_act = [
            'delete' => 'Xóa tạm thời'
        ];

        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $users = User::onlyTrashed()->paginate(5);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $users = User::where('name', 'LIKE', "%{$keyword}%")->paginate(5);
        }

        $count_user_active = User::count();
        $count_user_trash = User::onlyTrashed()->count();
        $count = [$count_user_active, $count_user_trash];
        return view('admin/users/list', compact('users', 'list_act', 'count'));
    }


    public function create()
    {
        return view('admin/users/add');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
            ],
            [
                'name' => 'Tên người dùng',
                'email' => 'Email',
                'password' => 'Mật khẩu',
            ]
        );
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return redirect(route("list-user"))->with('status', "Đã thêm thành viên thành công");
    }

    function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'email' => 'required|email|unique:users,email,' . $user->id,
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
                'confirmed' => 'Xác nhận mật khẩu không thành công'
            ],
            [
                'name' => 'Tên người dùng',
                'password' => 'Mật khẩu',
            ]
        );
        // dd($request->input('password'));
        if ($request->input('password') == $user->password) {
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => $request->input('password'),
            ]);
        } else {
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);
        }
        $user->roles()->sync($request->input('roles'));
        return redirect(route("list-user"))->with('status', "Đã cập nhật thành viên thành công");
    }

    public function delete($id)
    {
        if (Auth::id() != $id) {
            $user = User::find($id);
            $user->delete();
            return redirect(route("list-user"))->with("status", "Đã xóa tạm thời thành viên");
        } else {
            return redirect(route("list-user"))->with("status", "Bạn không thể xóa chính mình");
        }
    }

    public function action(Request $request)
    {
        $list_check = $request->input('listcheck');
        if ($list_check) {
            foreach ($list_check as $k => $id) {
                if (Auth::id() == $id) {
                    unset($list_check[$k]);
                }
            }
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == 'delete') {
                    User::destroy($list_check);
                    return redirect(route('list-user'))->with('status', "Bạn đã xóa tạm thời thành công");
                }
                if ($act == 'restore') {
                    User::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect(route('list-user'))->with('status', "Bạn đã khôi phục thành công");
                }
                if ($act == 'forceDelete') {
                    User::withTrashed()->whereIn('id', $list_check)->forceDelete();
                    return redirect(route('list-user'))->with('status', "Bạn đã xóa vĩnh viễn thành công");
                }
            }
        }
    }
}
