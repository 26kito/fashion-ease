<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WishlistSection extends Component
{
    public $wishlists = [];
    public $totalWishlist;

    public $listeners = [
        'refreshWishlist' => '$refresh'
    ];

    // public function mount()
    // {
    //     $this->wishlists = DB::table('wishlists')
    //         ->join('products', 'wishlists.product_id', 'products.id')
    //         ->where('user_id', Auth::id())
    //         ->get();
    // }

    public function render()
    {
        $this->wishlists = DB::table('wishlists')
            ->join('products', 'wishlists.product_id', 'products.id')
            ->where('user_id', Auth::id())
            ->get();

        $this->totalWishlist = DB::table('wishlists')
            ->where('user_id', Auth::id())
            ->count();

        return view('livewire.wishlist-section');
    }
}
