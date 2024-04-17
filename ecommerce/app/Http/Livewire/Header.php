<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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

    public function mount()
    {
        if (!Auth::check() && !isset($_COOKIE['cart_id'])) { // if user is not login and there is no cookie cart_id then set a cookie
            $cartID = md5(uniqid(rand(), true));
            setcookie('cart_id', $cartID, time() + (3600 * 2), '/'); // Set cookie to expire in 2 hour
        }
    }

    public function render()
    {
        $this->productsSearch = '';

        if (strlen($this->keyword) >= 3) {
            $this->productsSearch = DB::table('products')
                ->join('categories', 'products.category_id', 'categories.id')
                ->select('products.id', 'products.product_id', 'products.name AS ProductName', 'products.code', 'categories.name AS CategoryName')
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
