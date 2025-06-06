<?php

namespace App\Http\Controllers;

use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use \Illuminate\Support\Str;

class AdminProductController extends Controller
{

    private $htmlSelect, $htmlList;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(["module_active" => "product"]);
            return $next($request);
        });
        $this->htmlSelect = "";
        $this->htmlList = "";
    }

    // Category product ==================================================================================
    public function categoryRecusive($id, $text = "", $categoryById = null)
    {
        // dd($categoryById);
        $category = CategoryProduct::all();
        foreach ($category as $value) {
            if ($value->parent == $id) {
                $selected = ($categoryById == $value->id) ? 'selected' : 0;
                $disabled = ($value->parent == 0) ? 'disabled' : '';
                $this->htmlSelect .= "<option value='$value->id' $selected $disabled>" . $text . $value->name . "</option>";
                $this->categoryRecusive($value->id, $text . "--", $categoryById);
            }
        }
        return $this->htmlSelect;
    }

    public function categoryRecusiveSidebar($id, $text = "", $categoryById = null)
    {
        // dd($categoryById);
        $category = CategoryProduct::all();
        foreach ($category as $value) {
            if ($value->parent == $id) {
                $background_color = ($value->parent == 0) ? 'rgba(0,0,0,.05)' : '';
                $selected = ($categoryById == $value->id) ? 'selected' : 0;
                $this->htmlSelect .= "<option style='background-color: $background_color;' value='$value->id' $selected >" . $text . $value->name . "</option>";
                $this->categoryRecusive($value->id, $text . "--", $categoryById);
            }
        }
        return $this->htmlSelect;
    }


    public function listCategoryRecusive($id, $text = "")
    {
        $category = CategoryProduct::all();
        foreach ($category as $value) {
            if ($value->parent == $id) {
                $background_color = ($value->parent == 0) ? 'rgba(0,0,0,.05)' : 'white';
                $this->htmlList .= "<tr style='background-color: $background_color;'>";
                $this->htmlList .= "<td>" . $text . $value->name . "</td>";
                $this->htmlList .= "<td>" . $value->slug . "</td>";
                $this->htmlList .= "<td>" . $value->status . "</td>";
                $this->htmlList .= "<td>" . $value->created_at . "</td>";
                $this->htmlList .= "<td>" . "<a href='" . route('list_cat', ['id' => $value->id]) . "' class='btn btn-success btn-sm rounded-0 text-white' type='button'
                data-toggle='tooltip' data-placement='top' title='Edit'><i
                    class='fa fa-edit'></i></a>
            <a href='" . route('delete_category', $value->id) . "'
                onclick=\"return confirm('Bạn có chắc chắn xóa bản ghi này?')\"
                class='btn btn-danger btn-sm rounded-0 text-white' type='button'
                data-toggle='tooltip' data-placement='top' title='Delete'><i
                    class='fa fa-trash'></i></a>" . "</td>";
                $this->htmlList .= "</tr>";
                $this->listCategoryRecusive($value->id, $text . "--");
            }
        }
        return $this->htmlList;
    }

    public function list_cat(Request $request, $id = null)
    {
        $categoryById = CategoryProduct::where("id", "=", $id)->first();
        if ($categoryById) {
            $selectOp = $categoryById->parent;
        } else {
            $selectOp = "";
        }
        $htmlOption = $this->categoryRecusiveSidebar(0, '', $selectOp);
        $categories = $this->listCategoryRecusive(0);
        return  view('admin.product.category', compact('htmlOption', 'categories', 'categoryById'));
    }

    public function create_cat_product(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
            ],
            [
                'name' => 'Tên danh mục',
            ]
        );
        $slug = Str::slug($request->input('name'));
        CategoryProduct::create(
            [
                'name' => $request->input('name'),
                'status' => $request->input('status'),
                'slug' => $slug,
                'parent' => $request->input('parent_id'),
            ]
        );
        return redirect(route('list_cat'))->with('status', "Đã thêm danh mục thành công");
    }

    public function update_category(Request $request, $id)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống',
                'min' => ':attribute có độ dài ít nhất :min ký tự',
                'max' => ':attribute có độ dài tối đa :max ký tự',
            ],
            [
                'name' => 'Tên danh mục',
            ]
        );
        $slug = Str::slug($request->input('name'));
        CategoryProduct::where("id", "=", $id)->update([
            'name' => $request->input('name'),
            'status' => $request->input('status'),
            'slug' => $slug,
            'parent' => $request->input('parent_id'),
            'updated_at' => Date::now()->format('Y-m-d H:i:s'),
        ]);
        return redirect(route('list_cat'))->with('status', "Đã cập nhật danh mục thành công");
    }

    public function delete_category($id)
    {
        CategoryProduct::where("id", "=", $id)->delete();
        return redirect(route('list_cat'))->with('status', "Đã xóa danh mục thành công");
    }
    // End Category product ==================================================================================


    public function add()
    {
        $selectOp = "";
        $htmlOption = $this->categoryRecusive(0, '', $selectOp);
        return view("admin.product.add", compact('htmlOption'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required'],
                'discount' => ['required'],
                'desc' => ['required', 'string'],
                'detail' => ['required', 'string'],
                'thumbnail' => ['required'],
                'listFile' => ['required'],
                'category_id' => ['required', 'string', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài tối đa :max ký tự',
            ],
            [
                'name' => 'Tên loại sản phẩm',
                'price' => 'Giá sản phẩm',
                'desc' => 'Mô tả sản phẩm',
                'detail' => 'Chi tiết sản phẩm',
                'thumbnail' => 'Ảnh nổi bật sản phẩm',
                'listFile' => 'Ảnh chi tiết sản phẩm',
                'category_id' => 'Danh mục sản phẩm',
            ]
        );
        //Upload thumbnail products
        if ($request->hasFile('thumbnail')) {
            $file = $request->thumbnail;
            $name = $file->getClientOriginalName();
            $file->move('uploads', $file->getClientOriginalName());
            $thumbnail = 'public/uploads/' . $name;
        }
        //Upload multiple images products
        if ($request->hasfile('listFile')) {
            foreach ($request->file('listFile') as $file) {
                $name = $file->getClientOriginalName();
                $urlImage = strtolower('public/uploads/' . $name);
                $file->move('uploads', $file->getClientOriginalName());
                $listImages[] = $urlImage;
            }
        }
        Product::create([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'discount' => $request->input('discount'),
            'desc' => $request->input('desc'),
            'detail' => $request->input('detail'),
            'images' => implode(',', $listImages),
            'thumbnail' => $thumbnail,
            'category_id' => $request->input('category_id'),
            'slug' => Str::slug($request->input('name')),
            'status' => $request->input('status'),
        ]);
        return redirect(route('list_product'))->with('status', "Bạn đã thêm sản phẩm thành công");
    }


    public function list(Request $request)
    {
        $status = $request->input('status');
        $list_action = ['delete' => 'Xóa tạm thời'];
        if ($status == 'trash') {
            $list_action = [
                'restore' => 'khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $products = Product::onlyTrashed()->paginate(10);
        } elseif ($status == 'pending') {
            $products = Product::where('status', 'LIKE', "pending")->paginate(10);
        } else {
            $keyword = '';
            if ($request->input('keyword')) {
                $keyword = $request->input('keyword');
                $products = Product::where('name', 'LIKE', "%$keyword%")->paginate(10);
            }
            $products = Product::paginate(10);
        }
        $count_active = Product::all()->count();
        $count_pending = Product::where('status', 'LIKE', "pending")->count();
        $count_trash = Product::onlyTrashed()->count();
        $count = [$count_active, $count_pending, $count_trash];
        return view("admin.product.list", compact('products', 'list_action', 'count'));
    }

    public function edit($id)
    {
        $productById = Product::where('id', $id)->first();
        if ($productById) {
            $selectOp = $productById->category_id;
        } else {
            $selectOp = "";
        }
        $htmlOption = $this->categoryRecusive(0, '', $selectOp);
        return view("admin.product.edit", compact('htmlOption', 'productById'));
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'price' => ['required', 'string'],
                'desc' => ['required', 'string'],
                'detail' => ['required', 'string'],
                'category_id' => ['required', 'string', 'max:255'],
            ],
            [
                'required' => ':attribute không được để trống',
                'max' => ':attribute có độ dài tối đa :max ký tự',
            ],
            [
                'name' => 'Tên loại sản phẩm',
                'price' => 'Giá sản phẩm',
                'desc' => 'Mô tả sản phẩm',
                'detail' => 'Chi tiết sản phẩm',
                'category_id' => 'Danh mục sản phẩm',
            ]
        );

        $product = Product::find($id);
        $thumbnail = $product->thumbnail;
        $listImages = explode(',', $product->images);

        //Upload thumbnail products
        if ($request->hasFile('thumbnail')) {
            $file = $request->thumbnail;
            $name = $file->getClientOriginalName();
            $file->move('uploads', $file->getClientOriginalName());
            $thumbnail = 'public/uploads/' . $name;
        }
        //Upload multiple images products
        if ($request->hasfile('listFile')) {
            foreach ($request->file('listFile') as $file) {
                $name = $file->getClientOriginalName();
                $urlImage = strtolower('public/uploads/' . $name);
                $file->move('uploads', $file->getClientOriginalName());
                $listImages[] = $urlImage;
            }
        }


        Product::where('id', $id)->update([
            'name' => $request->input('name'),
            'price' => $request->input('price'),
            'desc' => $request->input('desc'),
            'detail' => $request->input('detail'),
            'images' => implode(',', $listImages),
            'thumbnail' => $thumbnail,
            'category_id' => $request->input('category_id'),
            'slug' => Str::slug($request->input('name')),
            'status' => $request->input('status'),
        ]);
        return redirect(route('list_product'))->with('status', "Bạn đã cập nhật sản phẩm thành công");
    }

    public function delete($id)
    {
        Product::where('id', $id)->delete();
        return redirect(route('list_product'))->with('status', "Bạn đã tạm thời xóa sản phẩm");
    }

    public function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if (!empty($list_check)) {
            $act = $request->input('list_action');
            if ($act == 'delete') {
                Product::destroy($list_check);
                return redirect(route('list_product'))->with('status', "Bạn đã xóa tạm thời thành công");
            }
            if ($act == 'restore') {
                Product::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect(route('list_product'))->with('status', "Bạn đã khôi phục thành công");
            }
            if ($act == 'forceDelete') {
                Product::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect(route('list_product'))->with('status', "Bạn đã xóa vĩnh viễn thành công");
            }
        }
    }
}
