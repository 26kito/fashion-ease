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
    public function index() {
        $data['title'] = 'Order Page';
        $order = DB::table('orders')
                            ->join('users', 'orders.user_id', '=', 'users.id')
                            ->select('orders.id', 'users.name', 'orders.order_date')
                            ->get();
                            
        return view('admin.order.dashboard', compact('order'), $data);
    }

    // Insert Data
    public function insert() {
        $data['title'] = 'Tambah Pesanan';
        $users = User::all();
        $order = DB::table('orders')
                            ->join('users', 'orders.user_id', '=', 'users.id')
                            ->select('orders.user_id', 'users.name')
                            ->get();
        return view('admin.order.insert', compact('order', 'users'), $data);
    }

    public function insertAction(Request $request) {
        $order = new Order;
        $order->user_id = $request->input('user_id');
        $order->order_date = date('Y-m-d', strtotime($request->input('order_date')));
        
        $order->save();

        return back()->with('message', 'Data berhasil di tambahkan');
    }

    // Lihat Pesanan
    public function lihatPesanan(Request $request, $id) {
        $data['title'] = 'Pesanan';
        
        $orderList = DB::table('order_items')
                                ->join('products', 'order_items.product_id', '=', 'products.id')
                                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                ->join('users', 'orders.user_id', '=', 'users.id')
                                ->select('order_items.id', 'users.name as nama_user', 'order_items.product_id', 'products.name as nama_produk', 'order_items.qty')
                                ->where('order_id', '=', $id)
                                ->get();
        $products = Product::all();

        return view('admin.order.orderList', compact('orderList', 'id', 'products'), $data);
    }

    public function insertOrder(Request $request) {
        $orderList = new OrderItem;
        $orderList->order_id = $request->order_id;
        $orderList->product_id = $request->input('product_id');
        $orderList->qty = $request->input('qty');
        $orderList->save();

        return back()->with('message', 'Data berhasil di tambahkan');
    }

    // Delete Order
    // public function deleteOrder($id) {
    //     $orderItems = DB::table('order_items')->where('order_id', '=', $id)->delete();
    //     $data = Order::find($id)->delete();

    //     return back()->with('message', 'Berhasil menghapus pesanan');
    // }

    // // Delete Order Item
    // public function deleteOrderItem($id) {
    //     $orderItems = DB::table('order_items')->where('order_id', '=', $id)->delete();
    //     $data = OrderItem::find($id)->delete();
        
    //     return back()->with('message', 'Berhasil menghapus pesanan');
    // }

    // Edit Order Item
    // public function editOrderItem(Request $request, $id) {
    //     $data['title'] = 'Pesanan';

    //     $edit = DB::table('order_items')
    //                     ->join('products', 'order_items.product_id', '=', 'products.id')
    //                     ->join('orders', 'order_items.order_id', '=', 'orders.id')
    //                     ->join('users', 'orders.user_id', '=', 'users.id')
    //                     ->select('order_items.id', 'order_items.order_id', 'users.name as nama_user', 'order_items.product_id', 'products.name as nama_produk', 'order_items.qty')
    //                     ->where('order_id', '=', $id)
    //                     ->get();
    //     $orderItems = OrderItem::find($id);
    //     $products = Product::all();
        
    //     return view('admin.order.edit_order', compact('edit', 'orderItems', 'id', 'products'), $data);
    // }

    // public function updateOrderItem(Request $request, $id) {
    //     $orderItems = OrderItem::find($id);

    //     $orderItems->order_id = $request->order_id;
    //     $orderItems->product_id = $request->input('insertProduct');
    //     $orderItems->qty = $request->qty;
    //     $orderItems->save();

    //     /* $data = array();
    //     $data['order_id'] = $request->order_id;
    //     $data['product_id'] = $request->input('insertProduct');
    //     $data['qty'] = $request->qty;
    //     DB::table('order_items')->where('id', $id)->update($data); */

    //     return back()->with('message', 'Data berhasil di ubah');
    // }
}
