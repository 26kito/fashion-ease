<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\cart as TraitsCart;

class CartController extends Controller
{
    use TraitsCart;

    public function index()
    {
        $title = 'Cart';
        $totalOrders = $this->cart();

        $wishlist = DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->count();

        return view('cart', ['title' => $title, 'totalOrders' => $totalOrders, 'wishlist' => $wishlist]);
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

            // $this->availStock = $this->carts->filter(fn ($cart) => $cart->AvailStock);
        }

        // if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
        //     $dataArray = json_decode($_COOKIE['carts'], true);

        //     $results = DB::table('products')
        //         ->join('detail_products', 'products.id', '=', 'detail_products.dp_id')
        //         ->select(
        //             'products.id AS ProductID',
        //             'products.product_id',
        //             'products.name AS ProdName',
        //             'products.image',
        //             DB::raw('COALESCE(detail_products.stock, 0) AS AvailStock'),
        //             DB::raw('products.price AS price'),
        //             DB::raw('products.price * detail_products.stock AS total_price'),
        //             'detail_products.size'
        //         )
        //         ->whereIn('detail_products.dp_id', array_column($dataArray, 'product_id'))
        //         ->whereIn('detail_products.size', array_column($dataArray, 'size'))
        //         ->get();

        //     // Processing and formatting the results...
        //     foreach ($results as &$result) {
        //         $productId = $result->ProductID;

        //         foreach ($dataArray as $item) {
        //             if ($item['product_id'] == $productId) {
        //                 $result->CartID = $item['cart_id'];
        //                 $result->qty = $item['quantity'];
        //                 break;
        //             }
        //         }
        //     }

        //     $this->carts = $results;
        //     $this->availStock = $this->carts->filter(fn ($cart) => $cart->AvailStock);
        // }

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
        // if (isset($_COOKIE['totalPriceCart'])) {
        //     $totalPrice = $_COOKIE['totalPriceCart'];
        // }

        // return $totalPrice;
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
        
        // if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts']) && count($this->cartsID) > 0) {
        //     $dataArray = json_decode($_COOKIE['carts'], true);

        //     $tempData = [];
        //     if ($this->cartsID) {
        //         foreach ($dataArray as $key => $value) {
        //             if (in_array($value['cart_id'], $this->cartsID)) {
        //                 array_push($tempData, $value);
        //             }
        //         }
        //     }

        //     $query = DB::table('products')
        //         ->join('detail_products', 'products.id', '=', 'detail_products.dp_id')
        //         ->select('products.id AS ProductID', 'products.price AS price');

        //     if ($this->cartsID) {
        //         $query->whereIn('detail_products.dp_id', array_column($tempData, 'product_id'));
        //     }

        //     $results = $query->get();

        //     // Processing and formatting the results...
        //     foreach ($results as &$result) {
        //         $productId = $result->ProductID;

        //         foreach ($dataArray as $item) {
        //             if ($item['product_id'] == $productId) {
        //                 $total += $result->price * $item['quantity'];
        //                 break;
        //             }
        //         }
        //     }
        // }

        // $total = $total;

        return response()->json($cartsID);
    }
}
