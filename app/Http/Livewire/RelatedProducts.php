<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;

class RelatedProducts extends Component
{
    public $relatedProducts;

    public function render()
    {
        return view('livewire.related-products');
    }
}
