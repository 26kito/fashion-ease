<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\addToWishlist;

class SearchResult extends Component
{
    use addToWishlist;

    public $categoryID;
    public $message = "";
    public $keyword;
    public $amount = 6;
    public $totalProduct;
    public $categoryCollapse = false;
    public $priceCollapse = false;
    public $minPriceFilter;
    public $maxPriceFilter;

    public function render()
    {
        $keyword = $this->keyword;

        $baseProducts = DB::table('products')->where('name', 'LIKE', "%$keyword%");

        $products = $baseProducts->get();

        $this->totalProduct = DB::table('products')->where('name', 'LIKE', "%$keyword%")->count();

        $category = collect($products)->pluck('category_id')->unique();
        $category = DB::table('categories')->whereIn('id', $category)->get();

        if ($products->isEmpty()) {
            $this->message = "Produk yang kamu cari gaada nih:(";
        }

        if ($this->categoryID) {
            $products = $baseProducts
                ->where('category_id', $this->categoryID)
                ->take($this->amount)
                ->get();

            $this->totalProduct = $products->count();
        }

        if ($this->minPriceFilter || $this->maxPriceFilter) {
            $products = $baseProducts;

            if ($this->minPriceFilter) {
                $minPriceFilter = intval($this->minPriceFilter);
                $products = $products->where('price', '>=', $minPriceFilter);
            }

            if ($this->maxPriceFilter) {
                $maxPriceFilter = intval($this->maxPriceFilter);
                $products = $products->where('price', '<=', $maxPriceFilter);
            }

            $products = $products->take($this->amount)->get();
        }

        return view('livewire.search-result', [
            'products' => $products,
            'category' => $category
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

    public function setCategoryCollapse()
    {
        $this->categoryCollapse = !$this->categoryCollapse;
    }

    public function setPriceCollapse()
    {
        $this->priceCollapse = !$this->priceCollapse;
    }
}
