<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Cart extends Component
{
    public $order_items;

    public function render() {
        return view('livewire.cart');
    }
}
