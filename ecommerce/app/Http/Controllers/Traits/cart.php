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

        // if (Auth::check()) {
        //     $order = Order::where('user_id', Auth::id())->first();
        //     // Cek user ada order ga, klo ada cus proses
        //     $prodPrice = DB::table('products')->select('price')->where('id', $productId)->first();
        //     $price = $prodPrice->price * $qty;

        //     if ($order) {
        //         OrderItem::updateOrCreate(
        //             ['order_id' => $order->order_id, 'product_id' => $productId, 'size' => $size],
        //             ['qty' => $qty, 'price' => $price]
        //         );

        //         $this->dispatchBrowserEvent('toastr', [
        //             'status' => 'success',
        //             'message' => 'Berhasil menambahkan ke keranjang!'
        //         ]);
        //         // Klo user blm pernah melakukan / gaada order, bkin order
        //     } else {
        //         // Generate Order ID
        //         $orderID = DB::table('orders')
        //             ->max('order_id');

        //         if ($orderID == null) {
        //             $number = '000001';
        //         } else {
        //             $number = substr($orderID, -6);
        //             $number = intval($number) + 1;
        //         }

        //         $orderID = 'SO' . '-' . date('ymdhi') . '-' . substr('000000' . $number, strlen($number));

        //         DB::table('orders')->insert([
        //             'order_id' => $orderID,
        //             'user_id' => Auth::id(),
        //             'shipment_date' => null,
        //             'order_date' => now()->toDateString()
        //         ]);

        //         DB::table('order_items')->insert([
        //             'order_id' => $orderID,
        //             'product_id' => $productId,
        //             'size' => $size,
        //             'qty' => $qty,
        //             'price' => $price
        //         ]);

        //         $this->dispatchBrowserEvent('toastr', [
        //             'status' => 'success',
        //             'message' => 'Berhasil menambahkan ke keranjang!'
        //         ]);
        //     }
        // } elseif (!Auth::check()) {
        //     return redirect()->route('login');
        // }
    }
}
