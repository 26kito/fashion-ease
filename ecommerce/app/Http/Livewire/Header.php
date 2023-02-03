<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\cart as TraitsCart;

class Header extends Component
{
    use TraitsCart;

    public $keyword = '';

    public $listeners = [
        'addToCart' => 'addToCart'
    ];


    public function render()
    {
        if (strlen($this->keyword) >= 3) {
            $products = DB::table('products')
                ->join('categories', 'products.category_id', 'categories.id')
                ->select('products.id AS ProductID', 'products.name AS ProductName', 'categories.name AS CategoryName')
                ->where('products.name', 'LIKE', '%' . $this->keyword . '%')
                ->get();
        } else {
            $products = "";
        }

        return view('livewire.header', [
            'cartQty' => $this->cart(),
            'productsSearch' => $products
        ]);
    }

    public function addToCart($productId, $size, $qty)
    {
        ['addToCart' => $this->addToCartTrait($productId, $size, $qty)];
    }

    public function search()
    {
        $keyword = $this->keyword;
    }
}
