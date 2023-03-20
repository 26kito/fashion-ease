<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\addToWishlist;

class SearchResult extends Component
{
    use addToWishlist;

    public $products;
    public $categoryID;
    public $message = "";
    public $keyword;
    public $amount = 6;
    public $totalProduct;
    public $categoryCollapse = false;
    public $priceCollapse = false;
    public $minPriceFilter;
    public $maxPriceFilter;
    public $sortByPrice;

    public function render()
    {
        $baseProducts = $this->getBaseProducts();

        $this->products = $baseProducts->get();

        $this->totalProduct = $this->getTotalProductCount($baseProducts);

        $category = $this->getProductCategories($baseProducts);

        if ($this->categoryID) {
            $this->products = $this->getProductsByCategory($baseProducts);
        }

        if ($this->minPriceFilter || $this->maxPriceFilter) {
            $this->products = $this->getProductsByPriceFilter($baseProducts);
        }

        if ($this->sortByPrice) {
            $this->products = $this->getProductsSortByPrice($baseProducts);
        }

        $this->totalProduct = $this->products->count();

        // Limit the number of products returned based on $this->amount
        $this->products = $this->products->take($this->amount);

        return view('livewire.search-result', [
            'category' => $category
        ]);
    }

    protected function getBaseProducts()
    {
        $keyword = $this->keyword;

        return DB::table('products')->where('name', 'LIKE', "%$keyword%");
    }

    protected function getTotalProductCount($baseProducts)
    {
        return $baseProducts->count();
    }

    protected function getProductCategories($baseProducts)
    {
        $products = $baseProducts->get();

        if ($products->isEmpty()) {
            $this->message = "Produk yang kamu cari gaada nih:(";
        }

        $category = collect($products)->pluck('category_id')->unique();
        return DB::table('categories')->whereIn('id', $category)->get();
    }

    protected function getProductsByCategory($baseProducts)
    {
        return $baseProducts->where('category_id', $this->categoryID)->get();
    }

    protected function getProductsByPriceFilter($baseProducts)
    {
        $products = $baseProducts;

        if ($this->minPriceFilter) {
            $minPriceFilter = intval($this->minPriceFilter);
            $products = $baseProducts->where('price', '>=', $minPriceFilter);
        }

        if ($this->maxPriceFilter) {
            $maxPriceFilter = intval($this->maxPriceFilter);
            $products = $baseProducts->where('price', '<=', $maxPriceFilter);
        }

        return $products->get();
    }

    protected function getProductsSortByPrice($baseProducts)
    {
        return $baseProducts->orderBy('price', $this->sortByPrice)->get();
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

    public function setSortByPrice($sortBy)
    {
        $this->sortByPrice = ($sortBy == 'lowest') ? 'ASC' : 'DESC';
    }
}
