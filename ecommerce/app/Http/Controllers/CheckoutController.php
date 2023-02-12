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
            $cartItemsID = $request->cartid;
            $title = 'Checkout';

            if ($cartItemsID) {
                $orderItems = DB::table('carts')
                    ->join('products', 'carts.product_id', 'products.id')
                    ->where('carts.user_id', Auth::id())
                    ->whereIn('carts.id', $cartItemsID)
                    ->select(
                        'carts.id AS CartID',
                        'products.id AS ProductID',
                        'products.product_id',
                        'products.name AS ProdName',
                        'products.image',
                        DB::raw("products.price * carts.qty AS Price"),
                        'carts.size',
                        'carts.qty'
                    )
                    ->get();

                return view('checkout', ['title' => $title, 'orderItems' => $orderItems, 'cartItemsID' => $cartItemsID]);
            } else {
                $status = 400;

                return redirect()->back()->with('error', $status);
            }
        };
    }
}
