<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;

class OrderController extends Controller
{
    // Dashboard
    public function index()
    {
        $title = 'Order Page';
        $orders = DB::table('orders')
            ->join('users', 'orders.user_id', 'users.id')
            ->join('cities', 'orders.shipping_to', 'cities.city_id')
            ->select('orders.id', 'users.first_name', 'users.last_name', 'users.username', 'orders.order_date', 'orders.grand_total', 'orders.shipping_to', 'cities.city_name')
            ->get();

        return view('admin.order.dashboard', ['orders' => $orders, 'title' => $title]);
    }

    // Insert Data
    public function insert()
    {
        $data['title'] = 'Tambah Pesanan';
        $users = User::all();
        $order = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.user_id', 'users.name')
            ->get();

        return view('admin.order.insert', compact('order', 'users'), $data);
    }

    public function insertAction(Request $request)
    {
        $order = new Order;
        $order->user_id = $request->input('user_id');
        $order->order_date = date('Y-m-d', strtotime($request->input('order_date')));

        $order->save();

        return back()->with('message', 'Data berhasil di tambahkan');
    }

    // Lihat Pesanan
    public function lihatPesanan(Request $request, $id)
    {
        $data['title'] = 'Pesanan';
        $products = Product::all();
        $size = ['S', 'M', 'L', 'XL'];

        return view('admin.order.orderList', compact('id', 'products', 'size'), $data);
    }

    public function insertOrder(Request $request)
    {
        $orderList = new OrderItem;
        $orderList->order_id = $request->order_id;
        $orderList->product_id = $request->input('product_id');
        $orderList->qty = $request->input('qty');
        $orderList->save();

        return back()->with('message', 'Data berhasil di tambahkan');
    }
}
