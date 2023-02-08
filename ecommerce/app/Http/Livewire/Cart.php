<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $orderItems = [];

    public function render()
    {
        $this->orderItems = DB::table('order_items')
            ->join('orders', 'order_items.order_id', 'orders.order_id')
            ->join('products', 'order_items.product_id', 'products.id')
            ->select('orders.order_id AS OrderID', 'order_items.id AS OrderItemsID', 'products.id', 'products.product_id', 'products.name as prodName', 'products.image', 'order_items.price', 'order_items.size', 'order_items.qty')
            ->where('orders.user_id', Auth::id())
            ->get()
            ->toArray();

        return view('livewire.cart');
    }

    public function remove($OrderID, $OrderItemsID)
    {
        $data = DB::table('order_items')
            ->where('order_items.order_id', $OrderID)
            ->where('order_items.id', $OrderItemsID)
            ->first();

        if ($data) {
            DB::table('order_items')
                ->where('order_items.order_id', $OrderID)
                ->where('order_items.id', $OrderItemsID)
                ->delete();
        } else {
            // return $this->failedResponse(200, 'Tidak ada data');
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Gagal menghapus pesanan'
            ]);
        }

        $this->dispatchBrowserEvent('toastr', [
            'status' => 'success',
            'message' => 'Pesanan berhasil dihapus'
        ]);

        $this->emit('refreshTotalPrice');
        $this->emit('refreshCart');
        // $orderItems = $this->getOrderItems();
        // return $this->successResponse(200, 'Pesanan berhasil dihapus', $orderItems);
    }

    public function checkSize($ProductID, $size)
    {
        $res = DB::table('detail_products')
            ->where('dp_id', $ProductID)
            ->where('size', $size)
            ->sum('stock');

        return $res;
    }

    public function increment($OrderItemsID)
    {
        $orderItems = DB::table('order_items')->where('id', $OrderItemsID)->first();
        $productID = $orderItems->product_id;
        $size = $orderItems->size;
        $qty = $orderItems->qty + 1;
        $product = DB::table('products')->where('id', $productID)->first();
        $price = $product->price * $qty;

        $availSize = $this->checkSize($productID, $size);

        if ($availSize > 0 && $qty <= $availSize) {
            DB::table('order_items')->where('id', $OrderItemsID)->update(['qty' => $qty, 'price' => $price]);
            $this->emit('refreshTotalPrice');
        }
    }

    public function decrement($OrderItemsID)
    {
        $orderItems = DB::table('order_items')->where('id', $OrderItemsID)->first();
        $productID = $orderItems->product_id;
        $size = $orderItems->size;
        $qty = $orderItems->qty - 1;
        $product = DB::table('products')->where('id', $productID)->first();
        $price = $product->price * $qty;

        $availSize = $this->checkSize($productID, $size);

        if ($availSize > 0 && $qty <= $availSize && $qty > 0) {
            DB::table('order_items')->where('id', $OrderItemsID)->update(['qty' => $qty, 'price' => $price]);
            $this->emit('refreshTotalPrice');
        }
    }
}
