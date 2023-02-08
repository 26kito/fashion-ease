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
        $total = DB::table('order_items')
            ->join('orders', 'order_items.order_id', 'orders.order_id')
            ->where('orders.user_id', Auth::id())
            ->sum('order_items.price');

        $this->total = rupiah($total);

        return view('livewire.total-price-cart');
    }
}
