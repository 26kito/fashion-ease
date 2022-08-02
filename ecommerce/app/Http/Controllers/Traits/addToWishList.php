<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

trait addToWishlist {
    public function addToWishlistTrait($productId) {
        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
        ]);
        $this->dispatchBrowserEvent('toastr', [
            'message' => 'Successfully Added To Wishlist!'
        ]);
    }
}