<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\AddToWishlist;

class LatestProducts extends Component
{
    use AddToWishlist;

    public $products;

    public function render()
    {
        $this->products = DB::table('products')
            ->join('detail_products', 'products.product_id', 'detail_products.product_id')
            ->selectRaw("products.id, products.name, detail_products.product_id, products.image, products.code, MIN(detail_products.price) AS price")
            ->groupBy('detail_products.product_id')
            ->havingRaw("SUM(detail_products.stock) > 0")
            ->orderBy('detail_products.price', 'desc')
            ->orderByDesc('products.created_at')
            ->take(5)
            ->get();

        return view('livewire.latest-products');
    }

    public function addToWishlist($productID)
    {
        $this->addToWishlistTrait($productID);

        $this->emit('refreshWishlist');
    }
}
