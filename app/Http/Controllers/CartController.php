<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\Cart as TraitsCart;

class CartController extends Controller
{
    use TraitsCart;

    public function index()
    {
        $title = 'Your Cart - ';
        $totalOrders = $this->cart();

        $wishlist = DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->count();

        return view('cart')->with(['title' => $title, 'totalOrders' => $totalOrders, 'wishlist' => $wishlist]);
    }

    public function getCartItems()
    {
        if (Auth::check()) {
            $carts = DB::table('carts')
                ->join('products', 'carts.product_id', 'products.id')
                ->leftJoin('detail_products', function ($join) {
                    $join->on('carts.product_id', 'detail_products.dp_id')
                        ->whereColumn('detail_products.size', 'carts.size');
                })
                ->where('carts.user_id', Auth::id())
                ->select(
                    'carts.id AS CartID',
                    'products.id AS ProductID',
                    'products.product_id',
                    'products.name AS ProdName',
                    DB::raw('COALESCE(detail_products.stock, 0) AS AvailStock'),
                    'products.image',
                    DB::raw('products.price * carts.qty AS price'),
                    'carts.user_id',
                    'carts.size',
                    'carts.qty'
                )
                ->get();
        }

        foreach ($carts as $row) {
            $row->formattedPrice = rupiah($row->price);
        }

        return response()->json($carts);
    }

    public function updateQty()
    {
        $status = request()->status;
        $cartItemsID = request()->cartID;
        $productID = request()->productID;

        if (Auth::check()) {
            $orderItems = DB::table('carts')->where('id', $cartItemsID)->where('product_id', $productID)->where('user_id', Auth::id())->first();
            $size = $orderItems->size;
            $qty = ($status == 'increment') ? $orderItems->qty + 1 : $orderItems->qty - 1;
        }

        $availSize = $this->checkSize($productID, $size);

        if ($availSize > 0 && $qty <= $availSize && $qty > 0) {
            if (Auth::check()) {
                DB::table('carts')->where('id', $cartItemsID)->where('product_id', $productID)->update(['qty' => $qty]);
                return response()->json('berhasil');
            }
        }
    }

    public function checkSize($productID, $size)
    {
        $res = DB::table('detail_products')
            ->where('dp_id', $productID)
            ->where('size', $size)
            ->sum('stock');

        return $res;
    }

    public function getTotalPrice()
    {
        $total = 0;
        $cartsID = request()->cartItemsID;

        if (Auth::check() && isset($cartsID) && count($cartsID) > 0) {
            $query = DB::table('carts')
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->selectRaw('SUM(products.price * carts.qty) AS price')
                ->where('carts.user_id', Auth::id());

            if ($cartsID) {
                $query->whereIn('carts.id', $cartsID);
            }

            $total = $query->value('price');
        }

        return response()->json($cartsID);
    }
}
