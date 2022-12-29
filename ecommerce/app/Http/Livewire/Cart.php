<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $orderItems = [];
    public $total;

    public function mount()
    {
        $this->orderItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('orders.id AS OrderID', 'order_items.id AS OrderItemsID', 'products.id AS productID', 'products.name as prodName', 'products.image', 'products.price', 'order_items.size', 'order_items.qty')
            ->where('orders.user_id', '=', Auth::id())
            ->get()->toArray();
    }

    public function render()
    {
        return view('livewire.cart');
    }
}
