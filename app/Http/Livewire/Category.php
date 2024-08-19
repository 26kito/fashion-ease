<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Category as ModelsCategory;
use App\Http\Controllers\Traits\AddToWishlist;

class Category extends Component
{
    use AddToWishlist;

    public $amount = 6;
    public $categoryId;

    public function render()
    {
        $totalProducts = Product::count();

        // $products = Product::when($this->categoryId, function ($query) {
        //     return $query->where('category_id', $this->categoryId);
        // })
        //     ->take($this->amount)
        //     ->get();

        $products = DB::table('products')
            ->leftJoin('detail_products', 'products.product_id', 'detail_products.product_id')
            ->selectRaw("products.id, products.name, products.product_id, products.image, products.code, IFNULL(MIN(detail_products.price), products.price) AS price")
            ->when($this->categoryId, function ($query) {
                return $query->where('products.category_id', $this->categoryId);
            })
            ->groupBy('products.product_id')
            ->take($this->amount)
            ->get();

        if ($this->categoryId) {
            $totalProducts = Product::where('category_id', '=', $this->categoryId)->count();
        }

        $category = ModelsCategory::get();

        return view('livewire.category', [
            'products' => $products,
            'category' => $category,
            'totalProducts' => $totalProducts
        ]);
    }

    public function load()
    {
        return $this->amount += 6;
    }

    public function refresh()
    {
        $this->reset('amount');
    }

    public function addToWishlist($productID)
    {
        $this->addToWishlistTrait($productID);
    }
}
