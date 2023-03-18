<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\cart as TraitsCart;

class Header extends Component
{
    use TraitsCart;

    public $keyword = '';
    public $cartQty;
    public $productsSearch;

    public $listeners = [
        'addToCart' => 'addToCart',
        'refreshCart' => '$refresh'
    ];

    public function render()
    {
        $this->productsSearch = '';

        if (strlen($this->keyword) >= 3) {
            $this->productsSearch = DB::table('products')
                ->join('categories', 'products.category_id', 'categories.id')
                ->select('products.id', 'products.product_id', 'products.name AS ProductName', 'categories.name AS CategoryName')
                ->where('products.name', 'LIKE', '%' . $this->keyword . '%')
                ->take(3)->get();
        }

        $this->cartQty = $this->cart();

        return view('livewire.header');
    }

    public function addToCart($productId, $size, $qty)
    {
        ['addToCart' => $this->addToCartTrait($productId, $size, $qty)];
    }

    public function search()
    {
        if ($this->keyword == '') {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'info',
                'message' => 'Ketik apa yang mau kamu cari di kolom pencarian yaa'
            ]);
        }

        return redirect()->to("/search/$this->keyword");
    }
}
