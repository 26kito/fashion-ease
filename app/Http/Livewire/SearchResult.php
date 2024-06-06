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
    public $sortByPriceOptions = [
        'lowest' => 'Harga Terendah',
        'highest' => 'Harga Tertinggi',
    ];
    public $sortByPrice;
    public $selectedFilters = [];

    public function render()
    {
        $baseProducts = $this->getBaseProducts();

        $this->products = $baseProducts->get();

        $this->totalProduct = $this->getTotalProductCount($baseProducts);

        $category = $this->getProductCategories($baseProducts);

        if ($this->categoryID) {
            $this->products = $this->getProductsByCategory($baseProducts);
            $this->setSelectedFilter('CategoryFilter');
        }

        if ($this->minPriceFilter || $this->maxPriceFilter) {
            $this->products = $this->getProductsByPriceFilter($baseProducts);
            $this->setSelectedFilter('MinimumPriceFilter');
            $this->setSelectedFilter('MaximumPriceFilter');
        }

        if ($this->sortByPrice) {
            $sortBy = ($this->sortByPrice == 'lowest') ? 'ASC' : 'DESC';
            $this->products = $this->getProductsSortByPrice($baseProducts, $sortBy);
            $this->setSelectedFilter('SortByPrice');
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

    protected function getProductsSortByPrice($baseProducts, $sortBy)
    {
        return $baseProducts->orderBy('price', $sortBy)->get();
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

    public function setSelectedFilter($filter)
    {
        $filterExists = in_array($filter, $this->selectedFilters);

        if ($filter == 'MinimumPriceFilter' && $this->minPriceFilter && !$filterExists && !in_array('Harga Minimum', $this->selectedFilters)) {
            $this->selectedFilters[] = 'Harga Minimum';
        }
        if ($filter == 'MaximumPriceFilter' && $this->maxPriceFilter && !$filterExists && !in_array('Harga Maksimum', $this->selectedFilters)) {
            $this->selectedFilters[] = 'Harga Maksimum';
        }
        if ($filter == 'CategoryFilter' && $this->categoryID && !$filterExists && !in_array('Kategori', $this->selectedFilters)) {
            $this->selectedFilters[] = 'Kategori';
        }
        if (($filter == 'SortByPrice') && $this->sortByPrice !== null && !in_array('Urutkan Berdasarkan Harga', $this->selectedFilters)) {
            $this->selectedFilters[] = 'Urutkan Berdasarkan Harga';
        }
    }

    public function removeFilter($filter)
    {
        $index = array_search($filter, $this->selectedFilters);

        if ($index === false) {
            return; // exit early if filter not found
        }

        unset($this->selectedFilters[$index]);
        $this->selectedFilters = array_values($this->selectedFilters);

        switch ($filter) {
            case 'Kategori':
                $this->reset('categoryID');
                break;
            case 'Harga Minimum':
                $this->reset('minPriceFilter');
                break;
            case 'Harga Maksimum':
                $this->reset('maxPriceFilter');
                break;
            case 'Urutkan Berdasarkan Harga':
                $this->reset('sortByPrice');
                break;
        }
    }
}
