<?php

namespace App\Http\Controllers\Traits;

use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait addToWishlist
{
    public function addAllCartItemsToWishlistTrait()
    {
        $checkCart = DB::table('carts')
            ->where('user_id', Auth::id())
            ->get()->toArray();

        $today = date("Y-m-d H:i:s");

        foreach ($checkCart as $row) {
            DB::table('wishlists')
                ->updateOrInsert(
                    ['user_id' => Auth::id(), 'product_id' => $row->product_id],
                    ['product_id' => $row->product_id, 'updated_at' => $today]
                );
        }

        $this->dispatchBrowserEvent('toastr', [
            'status' => 'success',
            'message' => 'Berhasil menambahkan ke wishlist!'
        ]);
    }

    public function addToWishlistTrait($productID)
    {
        $checkWishlist = DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->where('product_id', $productID)
            ->first();

        if ($checkWishlist == null) {
            Wishlist::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $productID],
                ['product_id' => $productID]
            );

            $this->dispatchBrowserEvent('toastr', [
                'status' => 'success',
                'message' => 'Berhasil menambahkan ke wishlist!'
            ]);
        } else {
            $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Produk sudah ada di daftar wishlist kamu'
            ]);
        }
    }
}
