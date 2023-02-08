<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class LatestProducts extends Component
{
    public $products;

    public function mount()
    {
        $this->products = DB::table('products')
            ->select('*')
            ->orderBy('created_at', 'DESC')
            ->take(5)
            ->get();
        // $this->products = Product::latest('id')->get();
    }

    public function render()
    {
        return view('livewire.latest-products');
    }

    public function addToCart($id)
    {
        // Emit u/ lempar function, param 1 = nama, param 2 opsional
        $this->emit('addToCart', $id);
    }
}
