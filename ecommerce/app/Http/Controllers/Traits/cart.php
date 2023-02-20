<?php

namespace App\Http\Controllers\Traits;

use App\Models\Cart as Carts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

trait cart
{
    public function cart()
    {
        if (Auth::check()) {
            $cartQty = DB::table('carts')
                ->where('user_id', Auth::id())
                ->count('product_id');

            return $cartQty;
        }
    }

    public function addToCartTrait($productId, $size, $qty)
    {
        if (Auth::check()) {
            Carts::updateOrCreate(
                ['user_id' => Auth::id(), 'product_id' => $productId, 'size' => $size],
                ['qty' => $qty]
            );

            $this->dispatchBrowserEvent('toastr', [
                'status' => 'success',
                'message' => 'Berhasil menambahkan ke keranjang!'
            ]);
        } else {
            return redirect()->route('login');
        }
    }
}
