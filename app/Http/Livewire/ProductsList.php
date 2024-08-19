<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\AddToWishlist;

class ProductsList extends Component
{
    use AddToWishlist;

    public $amount = 8;
    public $products;
    public $totalProducts;
    public $productCategories;
    public $category = NULL;

    public function render()
    {
        $categoryID = $this->category;

        // $query = Product::query();
        $query = DB::table('products')
            ->leftJoin('detail_products', 'products.product_id', 'detail_products.product_id')
            ->selectRaw("products.id, products.name, products.product_id, products.image, products.code, IFNULL(MIN(detail_products.price), products.price) AS price")
            ->groupBy('products.product_id');

        if ($categoryID !== null) {
            $query->where('category_id', $categoryID);
        }

        $totalProductsQuery = clone $query;
        $this->totalProducts = $totalProductsQuery->get()->count();
        $query->take($this->amount);
        $this->products = $query->get();

        $this->productCategories = DB::table('categories')
            ->select('id', 'name')
            ->take(6)->get();

        return view('livewire.products-list');
        // return view('livewire.products-list', [
        //     'products' => $products,
        //     'totalProducts' => $totalProducts,
        //     'productCategories' => $productCategories,
        // ]);
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
