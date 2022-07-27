<?php

namespace App\Http\Livewire;

use Livewire\Component;

class DetailsProduct extends Component
{

    public $qty = 1;
    public $products;
    public $size;

    public function render() {
        return view('livewire.details-product');
    }
    
    public function addToCart() {
        $productId = $this->products->id;
        $size = $this->size;
        $qty = $this->qty;

        $this->emit('addToCart', $productId, $size, $qty);
    }

    public function increment() {
        $this->qty++;
        if ( $this->qty >= 5 ) {
            return $this->qty = 5;
        }
    }

    public function decrement() {
        $this->qty--;
        if ( $this->qty <= 1 ) {
            return $this->qty = 1;
        }
    }
}
