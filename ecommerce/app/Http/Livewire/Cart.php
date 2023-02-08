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
            ->select('orders.order_id AS OrderID', 'order_items.id AS OrderItemsID', 'products.id AS productID', 'products.name as prodName', 'products.image', 'order_items.price', 'order_items.size', 'order_items.qty')
            ->where('orders.user_id', Auth::id())
            ->get();

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
        // $orderItems = $this->getOrderItems();
        // return $this->successResponse(200, 'Pesanan berhasil dihapus', $orderItems);
    }
}
