<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Http\Controllers\Traits\addToWishlist;

class ProductsList extends Component
{
    use addToWishlist;

    public $products;    

    public function mount() {
        $this->products = Product::all();
    }

    public function render() {
        return view('livewire.products-list');
    }

    public function addToCart($id) {
        // Emit u/ lempar function, param 1 = nama, param 2 opsional
        $this->emit('addToCart', $id);
    }

    public function addToWishlist($productId) {
        $this->addToWishlistTrait($productId);
    }
}
