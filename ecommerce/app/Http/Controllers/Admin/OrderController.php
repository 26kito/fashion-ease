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
            ->orderBy('orders.id', 'asc')
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
    public function lihatPesanan($id)
    {
        $title = 'Dashboard | Detail Order';
        $headingNavbar = 'Detail Order';
        $orderID = DB::table('orders')->where('id', $id)->value('order_id');
        $orderInformation = DB::table('orders')
            ->join('payment_method', 'orders.payment_method_id', 'payment_method.id')
            ->join('users', 'orders.user_id', 'users.id')
            ->join('cities', 'orders.shipping_to', 'cities.city_id')
            ->join('provinces', 'cities.province_id', 'provinces.province_id')
            ->select('orders.id', 'orders.order_id', 'users.first_name', 'users.last_name', 'users.username', 
            'users.phone_number', 'users.email', 'orders.order_date', 'orders.grand_total', 'orders.shipping_to',
            'cities.city_name', 'provinces.province_name', 'payment_method.name AS payment_method_name', 'payment_method.category AS payment_method_category')
            ->where('orders.order_id', $orderID)
            ->first();

        $orderList = DB::table('order_items')
            ->join('orders', 'order_items.order_id', 'orders.order_id')
            ->join('users', 'orders.user_id', 'users.id')
            ->join('cities', 'orders.shipping_to', 'cities.city_id')
            ->join('provinces', 'cities.province_id', 'provinces.province_id')
            ->join('products', 'order_items.product_id', 'products.id')
            ->select('orders.order_date', 'order_items.product_id', 'products.name AS product_name', 'order_items.size', 
            'order_items.price AS product_price', 'products.image AS product_image', 'order_items.qty', 'orders.grand_total', 'orders.shipping_to', 
            'cities.city_name', 'provinces.province_name')
            ->where('order_items.order_id', $orderID)
            ->orderBy('orders.id', 'asc')
            ->get();

        // dd($orderInformation);

        return view('admin.order.orderList', ['title' => $title, 'headingNavbar' => $headingNavbar, 'orderInformation' => $orderInformation, 'orderList' => $orderList]);
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
