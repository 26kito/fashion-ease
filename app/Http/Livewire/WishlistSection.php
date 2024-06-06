<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WishlistSection extends Component
{
    public $wishlists = [];

    public function mount()
    {
        $this->wishlists = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', 'products.id')
            ->where('user_id', Auth::id())
            ->get();
    }

    public function render()
    {
        return view('livewire.wishlist-section');
    }
}
