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
            ->selectRaw("products.*, MAX(detail_products.size) AS size, MAX(detail_products.stock) AS stock")
            ->groupBy('products.id')
            ->havingRaw("SUM(detail_products.stock) != 0")
            ->orderByDesc('products.created_at')
            ->take(5)
            ->get()->toArray();

        return view('livewire.latest-products-home');
    }

    public function addToWishlist($productID)
    {
        $this->addToWishlistTrait($productID);
    }
}
