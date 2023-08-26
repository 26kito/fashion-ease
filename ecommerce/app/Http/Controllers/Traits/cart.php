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

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $cartQty = count(json_decode($_COOKIE['carts']));

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

            // $this->dispatchBrowserEvent('toastr', [
            //     'status' => 'success',
            //     'message' => 'Berhasil menambahkan ke keranjang!'
            // ]);
        }

        if (!Auth::check() & isset($_COOKIE['cart_id'])) {
            // Retrieve existing items from the cookie, if any
            $cart = isset($_COOKIE['carts']) ? json_decode($_COOKIE['carts'], true) : [];

            // Add a new item to the list
            $cartItems = ['cart_id' => rand(1, 100), 'product_id' => $productId, 'quantity' => $qty, 'size' => $size];

            $productExists = false;
            // Check if the product ID already exists in the existing items
            if ($cart != null) {
                foreach ($cart as &$row) {
                    if ($row['product_id'] === $cartItems['product_id']) {
                        // Update the existing item
                        $row = $cartItems;
                        $productExists = true;
                        break;
                    }
                }
            }

            // If the product doesn't exist, add it to the list
            if (!$productExists) {
                $cart[] = $cartItems;
            }

            // Set the updated list of items in the cookie
            setcookie('carts', json_encode($cart), time() + (3600 * 2), '/');

            $this->emit('refreshCart');

            $this->dispatchBrowserEvent('toastr', [
                'status' => 'success',
                'message' => 'Berhasil menambahkan ke keranjang!'
            ]);
        }
    }
}
