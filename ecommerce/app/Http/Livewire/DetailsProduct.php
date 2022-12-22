<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DetailsProduct extends Component
{
    public $qty = 1;
    public $products;
    public $size;
    public $defaultSize;
    public $stock;

    public function render()
    {
        return view('livewire.details-product');
    }

    public function addToCart()
    {
        $productId = $this->products->id;
        $size = $this->size;
        $qty = $this->qty;

        if ($this->stock !== 61) {
            $this->dispatchBrowserEvent('toastr', [
                'message' => 'Barang lu lagi kosong ni.'
            ]);
        } else {
            $this->emit('addToCart', $productId, $size, $qty);
        }
    }

    public function increment()
    {
        $this->qty++;
        if ($this->qty >= 5) {
            return $this->qty = 5;
        }
    }

    public function decrement()
    {
        $this->qty--;
        if ($this->qty <= 1) {
            return $this->qty = 1;
        }
    }

    public function checkSize($defaultSize)
    {
        $productID = $this->products->id;
        $stock = DB::table('detail_products')->select('stock')->where('dp_id', $productID)->where('size', $defaultSize)->first();

        if ($stock !== null) $stock = $stock->stock;

        if ($stock == null) {
            $this->stock = "abis dek stoknya";
        } else {
            $this->stock = $stock;
        }
    }
}
