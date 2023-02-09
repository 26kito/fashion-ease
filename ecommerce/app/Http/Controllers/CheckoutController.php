<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            $cartItemsID = $request->id;
            $title = 'Checkout';

            if ($cartItemsID) {
                $orderItems = DB::table('carts')
                    ->join('products', 'carts.product_id', 'products.id')
                    ->where('carts.user_id', Auth::id())
                    ->whereIn('carts.id', $cartItemsID)
                    ->select('orders.id as orderId', 'products.name as prodName', 'products.image', 'carts.size', 'order_items.price', 'order_items.qty')
                    ->get();

                return view('checkout', ['title' => $title, 'orderItems' => $orderItems]);
            } else {
                $status = 400;

                return redirect()->back()->with('error', $status);
            }
        };
    }
}
