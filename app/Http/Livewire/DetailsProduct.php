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
    public $defaultStock;
    public $stock;
    public $isDisabled = false;

    public function render()
    {
        if ($this->stock == null) {
            $stock = DB::table('detail_products')
                ->where('dp_id', $this->products->id)
                ->sum('stock');

            $this->stock = $stock;
        }

        $this->defaultStock = DB::table('detail_products')
            ->where('dp_id', $this->products->id)
            ->sum('stock');

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
            $this->reset('qty');
            $this->emit('addToCart', $productId, $size, $qty);
        }
    }

    public function increment()
    {
        if (!$this->size) {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Kamu belum memilih size nih'
            ]);
        }

        if ($this->stock == 0) {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Size yang kamu pilih habis nih :('
            ]);
        }

        if ($this->qty >= 5) {
            return $this->qty = 5;
        }

        if ($this->qty < $this->stock) {
            $this->qty++;
        }
    }

    public function decrement()
    {
        if (!$this->size) {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Kamu belum memilih size nih'
            ]);
        }

        if (!$this->qty) {
            return;
        }

        if ($this->stock == 0) {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Size yang kamu pilih habis nih :('
            ]);
        }

        $this->qty--;

        if ($this->qty <= 1) {
            return $this->qty = 1;
        }
    }

    public function checkSize($defaultSize)
    {
        $this->reset('qty');

        $productID = $this->products->id;

        $stock = DB::table('detail_products')
            ->where('dp_id', $productID)
            ->where('size', $defaultSize)
            ->sum('stock');

        $this->stock = $stock === 0 ? '0' : $stock;
    }
}
