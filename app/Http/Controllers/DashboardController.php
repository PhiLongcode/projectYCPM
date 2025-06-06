<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware(function($request, $next){
            session(["module_active" => "dashboard"]);
            return $next($request);
        });
    }
    public function index(){
        $orders = Order::with('orderDetail')->orderByDesc('created_at')->paginate(5);
        // $count_order_pending = Order::where('status', 'pending')->count();
        $count_order_processing = Order::where('status', 'processing')->count();
        // $count_order_delivering = Order::where('status', 'delivering')->count();
        $count_order_delivered = Order::where('status', 'delivered')->count();
        $count_user_delete = Order::onlyTrashed()->count();
        $count = [$count_order_processing, $count_order_delivered,  $count_user_delete];
        $sales = Order::where('status','delivered')->sum('total');

        return view('admin.dashboard', compact('orders', 'count', 'sales'));
    }
}
