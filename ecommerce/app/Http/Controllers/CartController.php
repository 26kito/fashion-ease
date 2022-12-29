<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $data['title'] = 'Cart';
        if (Auth::check()) {
            $data['orderItems'] = DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->select(
                    'orders.id AS OrderID',
                    'order_items.id AS OrderItemsID',
                    'products.id AS ProductID',
                    'products.name AS ProductName',
                    'products.image',
                    'products.price',
                    'order_items.size',
                    'order_items.qty'
                )
                ->where('orders.user_id', '=', Auth::id())
                ->get();
            // dd($data);
            $data['total_orders'] = User::withCount('orderItems')->where('id', Auth::id())->first();
            $data['total'] = 0;
            foreach ($data['orderItems'] as $row) {
                $data['total'] = $data['total'] + ($row->price * $row->qty);
            };
            return view('cart', $data);
        } else {
            return redirect('login');
        }
    }

    public function getOrderItems()
    {
        $orderItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', 'orders.id')
            ->join('products', 'order_items.product_id', 'products.id')
            ->select(
                'orders.id AS OrderID',
                'order_items.id AS OrderItemsID',
                'products.id AS ProductID',
                'products.name as ProductName',
                'products.image',
                'products.price',
                'order_items.size',
                'order_items.qty'
            )
            ->where('orders.user_id', Auth::id())
            ->get();

        foreach ($orderItems as $key => $value) {
            $value->price = rupiah($value->price);
        }

        return $orderItems;
    }

    public function removeCartItem($orderID, $orderItemsID)
    {
        $data = DB::table('order_items')
            ->where('order_items.order_id', $orderID)
            ->where('order_items.id', $orderItemsID)
            ->first();

        if ($data) {
            DB::table('order_items')
                ->where('order_items.order_id', $orderID)
                ->where('order_items.id', $orderItemsID)
                ->delete();
        } else {
            return $this->failedResponse(200, 'Tidak ada data');
        }

        $orderItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', 'orders.id')
            ->join('products', 'order_items.product_id', 'products.id')
            ->select(
                'orders.id AS OrderID',
                'order_items.id AS OrderItemsID',
                'products.id AS ProductID',
                'products.name as ProductName',
                'products.image',
                'products.price',
                'order_items.size',
                'order_items.qty'
            )
            ->where('orders.id', $orderID)
            ->where('orders.user_id', Auth::id())
            ->get();

        foreach ($orderItems as $key => $value) {
            $value->price = rupiah($value->price);
        }

        // return response()->json(['data' => $orderItems]);
        return $this->successResponse(200, 'Pesanan berhasil dihapus', $orderItems);
    }
}
