<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TotalPriceCart extends Component
{
    public $total;

    protected $listeners = ['refreshTotalPrice' => '$refresh'];

    public function render()
    {
        $total = DB::table('carts')
            ->join('products', 'carts.product_id', '=', 'products.id')
            ->where('carts.user_id', Auth::id())
            ->selectRaw('SUM(products.price * carts.qty) AS price')
            ->value('price');

        $this->total = rupiah($total);

        return view('livewire.total-price-cart');
    }
}
