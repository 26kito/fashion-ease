<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Http\Controllers\Traits\addToWishlist;

class ProductsList extends Component
{
    use addToWishlist;
    public $amount = 12;
    public $products;    

    public function render() {
        $totalProducts = Product::count();
        $products = $this->products = Product::take($this->amount)->get();
        return view('livewire.products-list', ['products' => $products, 'totalProducts' => $totalProducts]);
    }

    public function addToCart($id) {
        // Emit u/ lempar function, param 1 = nama, param 2 opsional
        $this->emit('addToCart', $id);
    }

    public function addToWishlist($productId) {
        $this->addToWishlistTrait($productId);
    }

    public function load() {
        return $this->amount += 8;
    }
}
