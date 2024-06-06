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

        $query = Product::query();
        $query->take($this->amount);

        if ($categoryID !== null) {
            $query->where('category_id', $categoryID);
        }

        $products = $this->products = $query->get();
        $totalProducts = $query->count();

        $productCategories = DB::table('categories')
            ->select('id', 'name')
            ->take(6)->get();

        return view('livewire.products-list', [
            'products' => $products,
            'totalProducts' => $totalProducts,
            'productCategories' => $productCategories,
        ]);
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
