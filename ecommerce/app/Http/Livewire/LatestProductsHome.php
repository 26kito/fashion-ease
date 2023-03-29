<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\addToWishlist;

class LatestProductsHome extends Component
{
    use addToWishlist;

    public $products;

    public function render()
    {
        $this->products = DB::table('products')
            ->join('detail_products', 'products.id', 'detail_products.dp_id')
            ->groupBy('products.id')
            ->havingRaw("SUM(detail_products.stock) != 0")
            ->orderByDesc('products.created_at')
            ->take(5)
            ->get()->toArray();

        return view('livewire.latest-products-home');
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
