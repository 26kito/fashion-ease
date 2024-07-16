<?php

namespace App\Http\Controllers\Traits;

use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait AddToWishlist
{
    public function addAllCartItemsToWishlistTrait()
    {
        if (!Auth::check()) {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Kamu belum login nih'
            ]);
        }

        $checkCart = DB::table('carts')->where('user_id', Auth::id())->get()->toArray();

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
        if (!Auth::check()) {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Kamu belum login nih'
            ]);
        }

        $today = date("Y-m-d H:i:s");

        Wishlist::updateOrCreate(
            ['user_id' => Auth::id(), 'product_id' => $productID],
            ['product_id' => $productID, 'updated_at' => $today]
        );

        $this->dispatchBrowserEvent('toastr', [
            'status' => 'success',
            'message' => 'Berhasil menambahkan ke wishlist!'
        ]);
    }
}
