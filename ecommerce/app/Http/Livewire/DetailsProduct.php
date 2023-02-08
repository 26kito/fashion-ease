<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Foreach_;

class DetailsProduct extends Component
{
    public $qty;
    public $products;
    public $size;
    public $defaultSize;
    public $stock;

    public function render()
    {
        if ($this->stock == null) {
            $products = DB::table('detail_products')
                ->where('dp_id', $this->products->id)
                ->select('size', 'stock')
                ->get();

            foreach ($products as $row) {
                $this->stock += $row->stock;
            }
        }

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
        if ($this->size) {
            if ($this->stock !== '0') {
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
            if ($this->qty) {
                if ($this->stock !== '0') {
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
        $this->reset('qty');
        $productID = $this->products->id;
        $stock = DB::table('detail_products')
            ->where('dp_id', $productID)
            ->where('size', $defaultSize)
            ->sum('stock');

        if ($stock === 0) {
            $this->stock = '0';
        } else {
            $this->stock = $stock;
        }
    }
}
