<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category as ModelsCategory;
use App\Models\Product;
use App\Http\Controllers\Traits\addToWishlist;

class Category extends Component
{
    use addToWishlist;

    public $amount = 6;
    public $categoryId;

    public function render()
    {
        $totalProducts = Product::count();

        if (!$this->categoryId) {
            $products = Product::take($this->amount)->get();
        } else {
            $products = Product::where('category_id', '=', $this->categoryId)->take($this->amount)->get();
            $totalProducts = Product::where('category_id', '=', $this->categoryId)->count();
        }

        $category = ModelsCategory::get();

        return view('livewire.category', ['products' => $products, 'category' => $category, 'totalProducts' => $totalProducts]);
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
