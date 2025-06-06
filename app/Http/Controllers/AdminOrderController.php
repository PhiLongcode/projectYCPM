<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function($request, $next){
            session(['module_active' => 'order']);
            return $next($request);
        });
    }

    function list_order(Request $request)
    {
        $status = $request->input('status');
        $list_act = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'delivering' => 'Đang gửi',
            'delivered' => 'Đã gửi',
            'delete' => 'Xóa tạm thời',
        ];

        if ($status == 'canceled') {
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $orders = Order::onlyTrashed()->orderByDesc('created_at')->paginate(5);
        } elseif ($status == 'pending') {
            $list_act = [
                'processing' => 'Đang xử lý',
                'delivering' => 'Đang gửi',
                'delivered' => 'Đã gửi',
                'delete' => 'Xóa tạm thời',
            ];
            $orders = Order::where('status', $status)->with('orderDetail')->orderByDesc('created_at')->paginate(5);
        } elseif ($status == 'processing') {
            $list_act = [
                'pending' => 'Chờ xác nhận',
                'delivering' => 'Đang gửi',
                'delivered' => 'Đã gửi',
                'delete' => 'Xóa tạm thời',
            ];
            $orders = Order::where('status', $status)->with('orderDetail')->orderByDesc('created_at')->paginate(5);
        } elseif ($status == 'delivering') {
            $list_act = [
                'pending' => 'Chờ xác nhận',
                'processing' => 'Đang xử lý',
                'delivered' => 'Đã gửi',
                'delete' => 'Xóa tạm thời',
            ];
            $orders = Order::where('status', $status)->with('orderDetail')->orderByDesc('created_at')->paginate(5);
        } elseif ($status == 'delivered') {
            $list_act = [
                'pending' => 'Chờ xác nhận',
                'processing' => 'Đang xử lý',
                'delivering' => 'Đang gửi',
                'delete' => 'Xóa tạm thời',
            ];
            $orders = Order::where('status', $status)->with('orderDetail')->orderByDesc('created_at')->paginate(5);
        } else {
            $search = "";
            if ($request->input('search')) {
                $search = $request->input('search');
            }
            $orders = Order::where(function ($query) use ($search) {
                $query->where('fullname', 'LIKE', "%$search%")
                    ->orWhere('phone', 'LIKE', "%$search%")
                    ->orWhere('id', 'LIKE', "%$search%");
            })->with('orderDetail')->orderByDesc('created_at')->paginate(5);
        }
        $count_order_pending = Order::where('status','pending')->count();
        $count_order_processing = Order::where('status','processing')->count();
        $count_order_delivering = Order::where('status','delivering')->count();
        $count_order_delivered = Order::where('status','delivered')->count();
        $count_order_delete = Order::onlyTrashed()->count();
        $count = [$count_order_pending, $count_order_processing, $count_order_delivering, $count_order_delivered,  $count_order_delete];

        $sales = Order::where('status','delivered')->sum('total');
        return view('admin.order.list', compact('orders', 'count','list_act', 'sales'));
    }

    function detail_order($id){
        $list_act = [
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'delivering' => 'Đang gửi',
            'delivered' => 'Đã gửi',
        ];
        $order = Order::where('id', $id)->with('orderDetail')->first();
        return view('admin.order.detail', compact('order', 'list_act'));
    }

    function update_detail_order(Request $request, $id){
        $status = $request->input('status');
        Order::where('id', $id)->update(['status'=>$status]);
        return redirect(route('detail_order', $id))->with('status', "Bạn đã cập nhật thành công");
    }

    function delete($id){
        Order::where('id', $id)->update(['status'=> 'canceled']);
        Order::where('id', $id)->delete();
        return redirect(route('list_order'))->with('status', "Bạn đã xóa tạm thời thành công");
    }

    function order_action(Request $request)
    {
        $list_check = $request->input('listcheck');

        if (!empty($list_check)) {
            $act = $request->input('act');
            if ($act == 'pending') {
                Order::whereIn('id', $list_check)->update(['status'=> 'pending']);
                return redirect('admin/order/list')->with('status', "Bạn đã cập nhật trạng thái thành công");
            }
            if ($act == 'processing') {
                Order::whereIn('id', $list_check)->update(['status'=> 'processing']);
                return redirect('admin/order/list')->with('status', "Bạn đã cập nhật trạng thái thành công");
            }
            if ($act == 'delivering') {
                Order::whereIn('id', $list_check)->update(['status'=> 'delivering']);
                return redirect('admin/order/list')->with('status', "Bạn đã cập nhật trạng thái thành công");
            }
            if ($act == 'delivered') {
                Order::whereIn('id', $list_check)->update(['status'=> 'delivered']);
                return redirect('admin/order/list')->with('status', "Bạn đã cập nhật trạng thái thành công");
            }
            if ($act == 'delete') {
                Order::whereIn('id', $list_check)->update(['status'=> 'canceled']);
                Order::destroy($list_check);
                return redirect('admin/order/list')->with('status', "Bạn đã xóa tạm thời thành công");
            }
            if ($act == 'restore') {
                Order::whereIn('id', $list_check)->update(['status'=> 'pending']);
                Order::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/order/list')->with('status', "Bạn đã khôi phục thành công");
            }
            if ($act == 'forceDelete') {
                Order::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/order/list')->with('status', "Bạn đã xóa vĩnh viễn thành công");
            }
        }
    }
}
