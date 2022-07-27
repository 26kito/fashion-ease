<?php

namespace App\Http\Controllers\Traits;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait cart {

    public function cart() {
        if ( Auth::check() ) {
            $userId = Auth::user()->id;
            $cartQty = DB::table( 'users' )
                        ->join( 'orders', 'users.id' , '=' , 'orders.user_id' )
                        ->join( 'order_items', 'orders.id' , '=' , 'order_items.order_id' )
                        ->select( DB::raw( 'count(order_items.order_id) as qty' ) )
                        ->where( 'orders.user_id', '=', $userId )
                        ->first();
            return $cartQty;
        } elseif ( !Auth::check() ) {
            $arr = [];
            return $arr;
        };
    }

    public function TraitAddToCart($productId, $size, $qty) {
        if ( Auth::check() ) {
            $userId = Auth::user()->id;
            $order = Order::where('user_id', '=', $userId)->first();
            // Cek user ada order ga, klo ada cus proses
            if ( $order ) {
                orderItem::updateOrCreate(
                    ['order_id' => $order->id, 'product_id' => $productId, 'size' => $size],
                    ['qty' => $qty]
                );
            // Klo user blm pernah melakukan / gaada order, bkin order
            } else {
                Order::create([
                    'user_id' => $userId,
                    'order_date' => now()->toDateString()
                ]);
            }
        } elseif ( !Auth::check() ) {
            return redirect()->route('login');
            $arr = [];
            return $arr;
        };
    }
}