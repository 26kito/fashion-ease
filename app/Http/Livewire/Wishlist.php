<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\cart as TraitsCart;

class Wishlist extends Component
{
    use TraitsCart;

    public $page;
    public $wishlists = [];
    public $ProductID;
    public $size;
    public $totalWishlist;

    public $listeners = [
        'setSize' => 'setSize',
        'refreshWishlist' => '$refresh'
    ];

    public function render()
    {
        $this->totalWishlist = DB::table('wishlists')->where('user_id', Auth::id())->count();

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

            $row->sizeAndStock = array_combine($newSize, $newStock);
        }

        return view('livewire.wishlist');
    }

    public function setSize($data)
    {
        $toArr = explode(', ', $data);
        $ProductID = $toArr[0];
        $size = $toArr[1];
        $this->ProductID = $ProductID;
        $this->size = $size;
    }

    public function addToCart($ProductID, $size)
    {
        if ($ProductID != $this->ProductID && !$this->size) {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Kamu belum memilih size nih'
            ]);
        }

        $checkStock = DB::table('detail_products')
            ->where('dp_id', $ProductID)
            ->where('size', $size)
            ->first();

        if ($checkStock->stock > 0) {
            // $this->emit('addToCart', $ProductID, $size, 1);
            $this->addToCartTrait($ProductID, $size, 1);
            $this->emit('refreshCart');
        }
    }

    public function remove($wishlistID, $productID)
    {
        DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->where('id', $wishlistID)
            ->where('product_id', $productID)
            ->delete();

        return $this->dispatchBrowserEvent('toastr', [
            'status' => 'error',
            'message' => 'Berhasil menghapus produk dari wishlist kamu'
        ]);
    }
}
