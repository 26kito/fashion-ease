<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $title = 'Cart';
        $totalOrders = 0;

        if (Auth::check()) {
            $totalOrders = DB::table('carts')->where('user_id', Auth::id())->count('product_id');
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $totalOrders = count(json_decode($_COOKIE['carts']));
        }

        $wishlist = DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->count();

        return view('cart', ['title' => $title, 'totalOrders' => $totalOrders, 'wishlist' => $wishlist]);
    }
}
