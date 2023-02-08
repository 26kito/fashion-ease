<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\addToWishlist;

class LatestProducts extends Component
{
    use addToWishlist;

    public $products;

    public function render()
    {
        $this->products = DB::table('products')
            ->orderBy('created_at', 'DESC')
            ->take(5)
            ->get();

        return view('livewire.latest-products');
    }

    public function addToCart($id)
    {
        // Emit u/ lempar function, param 1 = nama, param 2 opsional
        $this->emit('addToCart', $id);
    }

    public function addToWishlist($productID)
    {
        $this->addToWishlistTrait($productID);
    }
}
