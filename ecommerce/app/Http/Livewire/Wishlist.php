<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Wishlist extends Component
{
    public $wishlists = [];

    public function render()
    {
        $this->wishlists = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', 'products.id')
            // ->leftJoin('detail_products', function ($join) {
            //     $join->on('wishlists.product_id', 'detail_products.dp_id')
            //         ->on('detail_products.size', 'carts.size');
            // })
            ->where('wishlists.user_id', Auth::id())
            ->select(
                'wishlists.id AS WishlistID',
                'products.id AS ProductID',
                'products.product_id',
                'products.name AS ProdName',
                'products.price',
                DB::raw("DATE_FORMAT(wishlists.created_at, '%Y-%m-%d') AS created_at"),
                // DB::raw("IFNULL(detail_products.stock, 0) AS AvailStock"),
                'products.image'
            )
            ->get();

        return view('livewire.wishlist');
    }
}
