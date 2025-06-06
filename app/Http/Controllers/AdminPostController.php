<?php

namespace App\Http\Controllers;

use App\Models\Cat_post;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AdminPostController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);
            return $next($request);
        });
    }
    //
    function add_post()
    {
        $pages = Page::with('cat_post')->get();
        return view('admin.post.add', compact('pages'));
    }

    function store_post(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'content' => ['required'],
                'thumbnail' => ['required'],
                'category' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'name' => 'Tiêu đề',
                'content' => 'Tiêu đề',
                'thumbnail' => 'Ảnh nổi bật',
                'category' => 'Danh mục',
            ]
        );

        //Upload thumbnail products
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $name = $file->getClientOriginalName();
            $file->move('public/uploads', $file->getClientOriginalName());
            $thumbnail = 'public/uploads/' . $name;
        }

        Post::create([
            'post_title' => $request->input('name'),
            'content_post' => $request->input('content'),
            'slug' => Str::slug($request->input('name')),
            'status' => $request->input('exampleRadios'),
            'post_cat' => $request->input('category'),
            'thumbnail' => $thumbnail,
        ]);
        return redirect(route('list_post'))->with('status', "Bạn đã thêm bài viết thành công");
    }

    function list_post(Request $request)
    {
        $status = $request->input('status');
        $list_act = [
            'pending' => 'Chờ duyệt',
            'delete' => 'Xóa tạm thời',
        ];
        if ($status == 'trash') {
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $pages = Page::with('cat_post')->all();
            $posts = Post::onlyTrashed()->paginate(5);
        } elseif ($status == 'pending') {
            $list_act = [
                'public' => 'Công khai',
                'delete' => 'Xóa tạm thời'
            ];
            $pages = Page::with('cat_post')->get();
            $posts = Post::where('status', 'pending')->paginate(5);
        } elseif ($status == 'public') {
            $list_act = [
                'pending' => 'Chờ duyệt',
                'delete' => 'Xóa tạm thời',
            ];
            $pages = Page::with('cat_post')->get();
            $posts = Post::where('status', 'public')->paginate(5);
        } else {
            $keyword = "";
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
            }
            $pages = Page::with('cat_post')->get();
            $posts = Post::where(function ($query) use ($keyword) {
                $query->where('post_title', 'LIKE', "%$keyword%")
                    ->orWhere('slug', 'LIKE', "%$keyword%");
            })->orderByDesc('created_at')->paginate(5);
        }
        $count_post_public = Post::where('status', 'public')->count();
        $count_post_pending = Post::where('status', 'pending')->count();
        $count_post_trash = Post::onlyTrashed()->count();
        $count = [$count_post_public, $count_post_pending, $count_post_trash];
        return view('admin.post.list', compact('posts', 'pages', 'count', 'list_act'));
    }

    function list_cat()
    {
        $cat_pages = Page::with('cat_post')->paginate(10);
        $pages = Page::all();
        return view('admin.post.cat', compact('pages', 'cat_pages'));
    }

    function add_cat_post(Request $request)
    {
        $request->validate(
            [
                'cat_post_name' => ['required', 'string', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'cat_post_name' => 'Tên loại danh mục',
            ]
        );
        Cat_post::create([
            'cat_post_name' => $request->input('cat_post_name'),
            'status' => $request->input('status'),
            'slug' => Str::slug($request->input('cat_post_name')),
            'post_cat_id' => $request->input('post_cat_id'),
        ]);
        return redirect(route('list_cat_post'))->with('status', "Bạn đã thêm danh mục thành công");
    }

    function edit_cat_post($id)
    {
        $cat_post = Cat_post::where('id', $id)->with('page')->first();
        $pages = Page::all();
        return view('admin.post.edit_cat', compact('pages', 'cat_post'));
    }

    function update_cat_post(Request $request, $id)
    {
        $request->validate(
            [
                'cat_post_name' => ['required', 'string', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'cat_post_name' => 'Tên loại danh mục',
            ]
        );
        Cat_post::where('id', $id)->update([
            'cat_post_name' => $request->input('cat_post_name'),
            'status' => $request->input('status'),
            'slug' => Str::slug($request->input('cat_post_name')),
            'post_cat_id' => $request->input('post_cat_id'),
        ]);
        return redirect(route('list_cat_post'))->with('status', "Bạn đã cập nhật thành công");
    }

    function delete_cat_post($id)
    {
        Cat_post::where('id', $id)->delete();
        return redirect(route('list_cat_post'))->with('status', "Bạn đã xóa bản ghi thành công");
    }

    function delete_post($id)
    {
        Post::where('id', $id)->update(['status' => 'canceling']);
        Post::where('id', $id)->delete();
        return redirect('admin/post/list')->with('status', "Bạn đã xóa tạm thời thành công");
    }

    function post_action(Request $request)
    {
        $list_check = $request->input('listcheck');

        if (!empty($list_check)) {
            $act = $request->input('act');
            if ($act == 'delete') {
                Post::whereIn('id', $list_check)->update(['status' => 'canceling']);
                Post::destroy($list_check);
                return redirect('admin/post/list')->with('status', "Bạn đã xóa tạm thời thành công");
            }
            if ($act == 'pending') {
                Post::whereIn('id', $list_check)->update(['status' => 'pending']);
                return redirect('admin/post/list')->with('status', "Bạn cập nhật thành công");
            }
            if ($act == 'public') {
                Post::whereIn('id', $list_check)->update(['status' => 'public']);
                return redirect('admin/post/list')->with('status', "Bạn đã cập nhật thành công");
            }
            if ($act == 'restore') {
                Page::withTrashed()->whereIn('id', $list_check)->update(['status' => 'public']);
                Post::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/post/list')->with('status', "Bạn đã khôi phục thành công");
            }
            if ($act == 'forceDelete') {
                Post::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/post/list')->with('status', "Bạn đã xóa vĩnh viễn thành công");
            }
        }
    }

    function edit_post($id){
        $pages = Page::all();
        $post = Post::find($id);
        return view('admin.post.edit', compact('post', 'pages'));
    }

    function update_post(Request $request, $id){
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'content' => ['required'],
                'category' => ['required'],
            ],
            [
                'required' => ':attribute không được để trống',
            ],
            [
                'name' => 'Tiêu đề',
                'content' => 'Tiêu đề',
                'category' => 'Danh mục',
            ]
        );

        //Upload thumbnail products
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $name = $file->getClientOriginalName();
            $file->move('public/uploads', $file->getClientOriginalName());
            $thumbnail = 'public/uploads/' . $name;
        }

        Post::where('id',$id)->update([
            'post_title' => $request->input('name'),
            'content_post' => $request->input('content'),
            'slug' => Str::slug($request->input('name')),
            'status' => $request->input('exampleRadios'),
            'post_cat' => $request->input('category'),
            'thumbnail' => $thumbnail ?? $request->input('filethumbnail'),
        ]);
        return redirect(route('list_post'))->with('status', "Bạn cập nhật bài viết thành công");
    }
}
