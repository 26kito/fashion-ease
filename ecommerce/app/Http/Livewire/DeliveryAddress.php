<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryAddress extends Component
{
    public function render()
    {
        $userInfo = DB::table('users')
            ->where('id', Auth::id())
            ->first();

        return view('livewire.delivery-address', ['userInfo' => $userInfo]);
    }
}
