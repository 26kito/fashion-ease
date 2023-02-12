<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TotalPriceCheckout extends Component
{
    public $cartItemsID;
    public $total;

    public function render()
    {
        $total = DB::table('carts')
            ->join('products', 'carts.product_id', 'products.id')
            ->where('carts.user_id', Auth::id())
            ->whereIn('carts.id', $this->cartItemsID)
            ->selectRaw("SUM(products.price * carts.qty) AS price")
            ->first();

        $this->total = rupiah($total->price);

        return view('livewire.total-price-checkout');
    }
}
