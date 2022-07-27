<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{

    public function index() {
        $data['title'] = 'Checkout';
        if ( Auth::check() ) {
            $id = Auth::user()->id;
            $data['order_items'] = DB::table('order_items')
                                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                                    ->join('products', 'order_items.product_id', '=', 'products.id')
                                    ->select('orders.id as orderId', 'products.name as prodName', 'products.image', 'products.price', 'order_items.qty')
                                    ->where('orders.user_id', '=', $id)
                                    ->get();
                                    
            $data['total'] = 0;
            foreach ( $data['order_items'] as $row ) {
                $data['total'] = $data['total'] + ($row->price*$row->qty);
            }
            return view('checkout', $data);
        };
    }
}
