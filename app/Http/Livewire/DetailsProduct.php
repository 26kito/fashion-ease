<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\Cart as TraitsCart;

class DetailsProduct extends Component
{
    use TraitsCart;

    public $qty;
    public $productID;
    public $products;
    public $size;
    public $defaultSize;
    public $defaultStock;
    public $stock;
    public $isDisabled = false;

    public function mount()
    {
        $this->defaultSize = DB::table('detail_products')
            ->join('product_sizes', 'detail_products.size', 'product_sizes.size')
            ->where('detail_products.product_id', $this->products['product_id'])
            ->orderBy('product_sizes.id', 'ASC')
            ->pluck('detail_products.size');
    }

    public function render()
    {
        $this->stock = ($this->stock) ?? DB::table('detail_products')
            ->where('product_id', $this->products['product_id'])
            ->sum('stock');

        $productsPrice = DB::table('detail_products')->where('product_id', $this->products['product_id'])->where('size', $this->size)->pluck('price')->first();
        $this->products['price'] = $productsPrice ?? $this->products['price'];

        return view('livewire.details-product');
    }

    public function addToCart()
    {
        $productId = $this->products['product_id'];
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
            // $this->emit('addToCart', $productId, $size, $qty);
            $this->addToCartTrait($productId, $size, $qty);
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

    public function checkSize($size)
    {
        $this->reset('qty');

        $stock = DB::table('detail_products')
            ->where('product_id', $this->products['product_id'])
            ->where('size', $size)
            ->sum('stock');

        $this->stock = ($stock === 0) ? '0' : $stock;
    }
}
