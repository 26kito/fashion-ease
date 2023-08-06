<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $title = 'Cart';

        $totalOrders = DB::table('carts')->where('user_id', Auth::id())->count('product_id');

        $wishlist = DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->count();

        return view('cart', ['title' => $title, 'totalOrders' => $totalOrders, 'wishlist' => $wishlist]);
    }
}
