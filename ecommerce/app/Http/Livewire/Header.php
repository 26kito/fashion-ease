<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Http\Controllers\Traits\cart as TraitsCart;

class Header extends Component
{
    use TraitsCart;

    public $listeners = [
        'addToCart' => 'addToCart'
    ];

    public function render()
    {
        return view('livewire.header', ['cartQty' => $this->cart()]);
    }

    public function addToCart($productId, $size, $qty) {
        [ 'addToCart' => $this->TraitAddToCart($productId, $size, $qty) ];
    }
}
