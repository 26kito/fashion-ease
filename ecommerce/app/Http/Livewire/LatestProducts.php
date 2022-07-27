<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;

class LatestProducts extends Component
{
    public $products;    

    public function mount() {
        $this->products = Product::latest('id')->get();
    }

    public function render()
    {
        return view('livewire.latest-products');
    }

    public function addToCart($id) {
        // Emit u/ lempar function, param 1 = nama, param 2 opsional
        $this->emit('addToCart', $id);
    }
}
