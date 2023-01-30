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
                'message' => 'Kamu belum memilih size nih'
            ]);
        }
        if (!$qty) {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Masukin jumlah yang ingin dibeli ya'
            ]);
        }

        if ($this->stock > 0) {
            $this->emit('addToCart', $productId, $size, $qty);
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
                return $this->dispatchBrowserEvent('toastr', [
                    'status' => 'error',
                    'message' => 'Size yang kmu pilih habis nih :('
                ]);
            }
        } else {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Kamu belum memilih size nih'
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
                return $this->dispatchBrowserEvent('toastr', [
                    'status' => 'error',
                    'message' => 'Size yang kmu pilih habis nih :('
                ]);
            }
        } else {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Kamu belum memilih size nih'
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
