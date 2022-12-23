<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class DetailsProduct extends Component
{
    public $qty;
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

        if (!$size) {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Lu blom pilih barang asw'
            ]);
        }
        if (!$qty) {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Lu mau beli brp cok'
            ]);
        }
        if ($this->stock > 0) {
            $this->emit('addToCart', $productId, $size, $qty);
        } else {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Barang lu lagi kosong ni.'
            ]);
        }
    }

    public function increment()
    {
        if ($this->size) {
            if ($this->stock !== 0) {
                $this->qty++;
                if ($this->qty >= 5) {
                    return $this->qty = 5;
                }
            } else {
                $this->reset('qty');
            }
        } else {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Lu blom pilih barang asw'
            ]);
        }
    }

    public function decrement()
    {
        if ($this->size) {
            if ($this->stock !== 0) {
                $this->qty--;
                if ($this->qty <= 1) {
                    return $this->qty = 1;
                }
            } else {
                $this->reset('qty');
            }
        } else {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Lu blom pilih barang asw'
            ]);
        }
    }

    public function checkSize($defaultSize)
    {
        $productID = $this->products->id;
        $stock = DB::table('detail_products')->select('stock')->where('dp_id', $productID)->where('size', $defaultSize)->first();

        if ($stock !== null) $stock = $stock->stock;

        if ($stock === null) {
            $this->stock = 0;
            $this->reset('qty');
        } else {
            $this->stock = $stock;
        }
    }
}
