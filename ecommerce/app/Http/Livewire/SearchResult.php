<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\addToWishlist;

class SearchResult extends Component
{
    use addToWishlist;

    public $categoryID;
    public $keyword;
    public $amount = 6;
    public $totalProduct;

    public function render()
    {
        $keyword = $this->keyword;

        $baseProducts = DB::table('products')->where('name', 'LIKE', "%$keyword%");

        $totalAllProduct = DB::table('products')
            ->where('name', 'LIKE', "%$keyword%")
            ->count();

        $products = (clone $baseProducts)->get();

        $productCategoryID = [];
        foreach ($products as $row) {
            array_push($productCategoryID, $row->category_id);
        }

        $this->totalProduct = $totalAllProduct;

        $category = DB::table('categories')
            ->whereIn('id', $productCategoryID)
            ->get();

        if ($products->isEmpty()) {
            $message = "Produk yang kamu cari gaada nih:(";
        } else {
            $message = "";
        }

        $categoryID = $this->categoryID;
        if ($categoryID) {
            $products = (clone $baseProducts)
                ->where('category_id', $categoryID)
                ->take($this->amount)
                ->get();

            $totalProductByCategory = (clone $baseProducts)
                ->where('category_id', $categoryID)
                ->count();
            $this->totalProduct = $totalProductByCategory;
        } else {
            $products = (clone $baseProducts)->take($this->amount)->get();
        }
        return view('livewire.search-result', [
            'products' => $products,
            'category' => $category,
            'message' => $message
        ]);
    }

    public function load()
    {
        $this->amount += 6;
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
