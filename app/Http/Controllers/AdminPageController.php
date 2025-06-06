<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class AdminPageController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'page']);
            return $next($request);
        });
    }

    function add_page()
    {
        return view('admin.page.add');
    }

    function store_page(Request $request)
    {
        $request->validate(
            [
                'name_page' => ['required', 'string', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'name_page' => 'Tên trang',
            ]
        );
        Page::create([
            'name_page' => $request->input('name_page'),
            'content' => $request->input('content'),
            'status' => $request->input('exampleRadios'),
            'slug' => Str::slug($request->input('name_page')),
            'user_add' => Auth::user()->name,
        ]);
        return redirect(route('list_page'))->with('status', "Bạn đã thêm trang thành công");
    }

    function list_page(Request $request)
    {
        $status = $request->input('status');
        $list_act = [
            'pending' => 'Chờ duyệt',
            'public' => 'Công khai',
            'delete' => 'Xóa tạm thời',

        ];
        if ($status == 'pending') {
            $list_act = [
                'public' => 'Công khai',
                'delete' => 'Xóa tạm thời',
            ];
            $search = "";
            if ($request->input('search')) {
                $search = $request->input('search');
            }
            $pages = Page::where('status', 'pending')->orderByDesc('created_at')->paginate(5);
        }

        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $pages = Page::onlyTrashed()->paginate(5);
        } elseif ($status == 'pending') {
            $list_act = [
                'public' => 'Công khai',
                'delete' => 'Xóa tạm thời',
            ];
            $pages = Page::where('status', 'pending')->orderByDesc('created_at')->paginate(5);
        } elseif ($status == 'public') {
            $list_act = [
                'pending' => 'Chờ duyệt',
                'delete' => 'Xóa tạm thời',
            ];
            $pages = Page::where('status', 'public')->orderByDesc('created_at')->paginate(5);
        } else {
            $search = "";
            if ($request->input('search')) {
                $search = $request->input('search');
            }
            $pages = Page::where(function ($query) use ($search) {
                $query->where('name_page', 'LIKE', "%$search%")
                    ->orWhere('slug', 'LIKE', "%$search%")
                    ->orWhere('user_add', 'LIKE', "%$search%");
            })->orderByDesc('created_at')->paginate(5);
        }
        $count_page_active = Page::count();
        $count_page_public = Page::where('status', 'public')->count();
        $count_page_pending = Page::where('status', 'pending')->count();
        $count_page_trash = Page::onlyTrashed()->count();
        $count = [$count_page_active, $count_page_public, $count_page_pending, $count_page_trash];
        return view('admin.page.list', compact('pages', 'count', 'list_act'));
    }

    function edit_page($id)
    {
        $page = Page::find($id);
        return view('admin.page.edit', compact('page'));
    }

    function update_page(Request $request, $id)
    {
        $request->validate(
            [
                'name_page' => ['required', 'string', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'name_page' => 'Tên trang',
            ]
        );
        Page::where('id', $id)->update([
            'name_page' => $request->input('name_page'),
            'status' => $request->input('exampleRadios'),
            'slug' => Str::slug($request->input('name_page')),
            'user_add' => Auth::user()->name,
        ]);
        return redirect(route('list_page'))->with('status', "Bạn cập nhật trang thành công");
    }

    function delete_page($id)
    {
        Page::where('id', $id)->update(['status' => 'canceled']);
        Page::where('id',$id)->delete();
        return redirect('admin/page/list')->with('status', "Bạn đã xóa tạm thời thành công");
    }

    function action_page(Request $request)
    {
        $list_check = $request->input('listcheck');
        if ($list_check) {
            if (!empty($list_check)) {
                $act = $request->input('act');
                if ($act == 'pending') {
                    Page::whereIn('id', $list_check)->update(['status' => 'pending']);
                    return redirect('admin/page/list')->with('status', "Bạn đã chỉnh sửa thành công");
                }
                if ($act == 'public') {
                    Page::whereIn('id', $list_check)->update(['status' => 'public']);
                    return redirect('admin/page/list')->with('status', "Bạn đã chỉnh sửa thành công");
                }
                if ($act == 'delete') {
                    Page::whereIn('id', $list_check)->update(['status' => 'canceled']);
                    Page::destroy($list_check);
                    return redirect('admin/page/list')->with('status', "Bạn đã xóa tạm thời thành công");
                }

                if ($act == 'restore') {
                    Page::withTrashed()->whereIn('id', $list_check)->update(['status' => 'public']);
                    Page::withTrashed()->whereIn('id', $list_check)->restore();
                    return redirect('admin/page/list')->with('status', "Bạn đã khôi phục thành công");
                }
                if ($act == 'forceDelete') {
                    Page::withTrashed()->whereIn('id', $list_check)->forceDelete();
                    return redirect('admin/page/list')->with('status', "Bạn đã xóa vĩnh viễn thành công");
                }
            }
        } else {
            return redirect('admin/page/list')->with('status', "Bạn cần chọn phần tử để thực hiện");
        }
    }
}
