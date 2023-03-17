<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $title = 'Cart';

            $orderItems = DB::table('order_items')
                ->join('orders', 'order_items.order_id', 'orders.order_id')
                ->join('products', 'order_items.product_id', 'products.id')
                ->select(
                    'orders.id AS OrderID',
                    'order_items.id AS OrderItemsID',
                    'products.id AS ProductID',
                    'products.name AS ProductName',
                    'products.image',
                    'order_items.price',
                    'order_items.size',
                    'order_items.qty'
                )
                ->where('orders.user_id', Auth::id())
                ->get();

            $totalOrders = DB::table('carts')->where('user_id', Auth::id())->count('product_id');

            $wishlist = DB::table('wishlists')
                ->where('user_id', Auth::id())
                ->count();

            return view('cart', ['title' => $title, 'orderItems' => $orderItems, 'totalOrders' => $totalOrders, 'wishlist' => $wishlist]);
        } else {
            return redirect('login');
        }
    }
}
