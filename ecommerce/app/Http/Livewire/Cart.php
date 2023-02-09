<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Cart extends Component
{
    public $carts = [];
    public $page;
    public $stock;

    public function mount($page)
    {
        $this->page = $page;
    }

    public function render()
    {
        $carts = DB::table('carts')
            ->join('products', 'carts.product_id', 'products.id')
            ->where('carts.user_id', Auth::id())
            ->select(
                'carts.id AS CartID',
                'products.id AS ProductID',
                'products.product_id',
                'products.name as prodName',
                'products.image',
                DB::raw("products.price * carts.qty AS price"),
                'carts.size',
                'carts.qty'
            )
            ->get();
        // $carts[0]->stock = 'haha';
        // dd($carts);

        foreach ($carts as $row) {
            $availStock = DB::table('detail_products')
                ->where('dp_id', $row->ProductID)
                ->where('size', $row->size)
                ->selectRaw("SUM(stock) AS stock")
                ->first();

            $carts['stock'] = $availStock->stock;
        }

        return view('livewire.cart');
    }

    public function remove($CartID, $ProductID)
    {
        $data = DB::table('carts')
            ->where('carts.id', $CartID)
            ->where('carts.user_id', Auth::id())
            ->where('carts.product_id', $ProductID)
            ->first();

        if ($data) {
            DB::table('carts')
                ->where('carts.id', $CartID)
                ->where('carts.user_id', Auth::id())
                ->where('carts.product_id', $ProductID)
                ->delete();

            $this->dispatchBrowserEvent('toastr', [
                'status' => 'success',
                'message' => 'Pesanan berhasil dihapus'
            ]);

            $this->emit('refreshTotalPrice');
            $this->emit('refreshCart');

            return redirect($this->page);
        } else {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Gagal menghapus pesanan'
            ]);
        }
    }

    public function checkSize($ProductID, $size)
    {
        $res = DB::table('detail_products')
            ->where('dp_id', $ProductID)
            ->where('size', $size)
            ->sum('stock');

        return $res;
    }

    public function increment($OrderItemsID, $ProductID)
    {
        $orderItems = DB::table('carts')->where('id', $OrderItemsID)->where('product_id', $ProductID)->where('user_id', Auth::id())->first();
        $size = $orderItems->size;
        $qty = $orderItems->qty + 1;

        $availSize = $this->checkSize($ProductID, $size);

        if ($availSize > 0 && $qty <= $availSize) {
            DB::table('carts')->where('id', $OrderItemsID)->update(['qty' => $qty]);
            $this->emit('refreshTotalPrice');
        }
    }

    public function decrement($OrderItemsID, $ProductID)
    {
        $orderItems = DB::table('carts')->where('id', $OrderItemsID)->where('product_id', $ProductID)->where('user_id', Auth::id())->first();
        $size = $orderItems->size;
        $qty = $orderItems->qty - 1;

        $availSize = $this->checkSize($ProductID, $size);

        if ($availSize > 0 && $qty <= $availSize && $qty > 0) {
            DB::table('carts')->where('id', $OrderItemsID)->update(['qty' => $qty]);
            $this->emit('refreshTotalPrice');
        }
    }
}
