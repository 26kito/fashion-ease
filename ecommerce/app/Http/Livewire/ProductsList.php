<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\addToWishlist;

class ProductsList extends Component
{
    use addToWishlist;

    public $amount = 8;
    public $products;
    public $category = NULL;

    public function render()
    {
        $categoryID = $this->category;
        $totalProducts = Product::count();

        if ($this->category == NULL) {
            $products = $this->products =
                Product::take($this->amount)->get();
        } else {
            $products = $this->products =
                Product::where('category_id', $categoryID)->take($this->amount)->get();

            $totalProducts = Product::where('category_id', $categoryID)->count();
        }

        $productCategories = DB::table('categories')
            ->select('id', 'name')
            ->get();

        return view('livewire.products-list', ['products' => $products, 'totalProducts' => $totalProducts, 'productCategories' => $productCategories]);
    }

    public function addToCart($id)
    {
        // Emit u/ lempar function, param 1 = nama, param 2 opsional
        $this->emit('addToCart', $id);
    }

    public function addToWishlist($productID)
    {
        $this->addToWishlistTrait($productID);
    }

    public function load()
    {
        return $this->amount += 8;
    }

    public function category($categoryID)
    {
        $this->category = $categoryID;
        $this->reset('amount');
    }
}
