<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index() {
        $data['title'] = 'Cart';
        if ( Auth::check() ) {
            $data['order_items'] = DB::table('order_items')
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                    ->join('products', 'order_items.product_id', '=', 'products.id')
                                    ->select('products.name as prodName', 'products.image', 'products.price', 'order_items.size', 'order_items.qty')
                                    ->where('orders.user_id', '=', Auth::id())
                                    ->get();
            $data['total_orders'] = User::withCount('orderItems')->where('id', Auth::id())->first();
            $data['total'] = 0;
            foreach ( $data['order_items'] as $row ) {
                $data['total'] = $data['total'] + ( $row->price*$row->qty );
            };
            return view('cart', $data);
        };
    }
}
