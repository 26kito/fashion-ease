<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category as ModelsCategory;
use App\Models\Product;
use App\Http\Controllers\Traits\AddToWishlist;

class Category extends Component
{
    use AddToWishlist;

    public $amount = 6;
    public $categoryId;

    public function render()
    {
        $totalProducts = Product::count();

        $products = Product::when($this->categoryId, function ($query) {
            return $query->where('category_id', $this->categoryId);
        })
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
