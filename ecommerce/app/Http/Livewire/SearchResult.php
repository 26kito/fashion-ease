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
        $query = DB::table('products')->where('name', 'LIKE', "%$keyword%");

        $totalAllProduct = $query->count();
        $this->totalProduct = $totalAllProduct;

        $categoryID = $this->categoryID;
        if ($categoryID) {
            $query = $query->where('category_id', $categoryID);
        }

        $products = $query->take($this->amount)->get();

        if ($products->isEmpty()) {
            $message = "Produk yang kamu cari gaada nih:(";
        } else {
            $categoryIDs = $products->pluck('category_id')->unique()->toArray();
            $category = DB::table('categories')->whereIn('id', $categoryIDs)->get();
            $message = "";
        }

        return view('livewire.search-result', [
            'products' => $products,
            'category' => $category ?? [],
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
