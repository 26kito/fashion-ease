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
        $sizeSubquery = DB::table('detail_products')
            ->selectRaw("dp_id, GROUP_CONCAT(size SEPARATOR ', ') AS size, GROUP_CONCAT(stock SEPARATOR ', ') AS stock")
            ->groupBy('dp_id');

        $this->wishlists = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', 'products.id')
            ->joinSub($sizeSubquery, 'sizeSubquery', function ($join) {
                $join->on('sizeSubquery.dp_id', 'wishlists.product_id');
            })
            ->where('wishlists.user_id', Auth::id())
            ->select(
                'wishlists.id AS WishlistID',
                'products.id AS ProductID',
                'products.product_id',
                'products.name AS ProdName',
                'products.price',
                'sizeSubquery.size',
                'sizeSubquery.stock',
                DB::raw("DATE_FORMAT(wishlists.created_at, '%Y-%m-%d') AS created_at"),
                'products.image'
            )
            ->get()->toArray();

        foreach ($this->wishlists as $row) {
            $newSize = explode(', ', $row->size);
            $row->size = $newSize;

            $newStock = explode(', ', $row->stock);
            $row->stock = $newStock;
        }

        return view('livewire.wishlist');
    }

    public function addToCart($wishlistID, $ProductID)
    {
        dd("$wishlistID dan $ProductID");
    }
}
