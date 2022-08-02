<?php

namespace App\Http\Controllers\Traits;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait cart {
    public function cart() {
        if ( Auth::check() ) {
            $cartQty = User::with('orders', 'orderItems')->withCount('orderItems as qty')
            ->where('id', Auth::id())->first();
            return $cartQty;
        }
    }

    public function addToCartTrait($productId, $size, $qty) {
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
            // $arr = [];
            // return $arr;
        };
    }
}