<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cart extends Component
{
    public function render() {
        $order_items = DB::table('order_items')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->join('products', 'order_items.product_id', '=', 'products.id')
                        ->select('products.name as prodName', 'products.image', 'products.price', 'order_items.size', 'order_items.qty')
                        ->where('orders.user_id', '=', Auth::id())
                        ->get();
        return view('livewire.cart', ['order_items' => $order_items]);
    }
}
