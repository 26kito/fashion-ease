<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryAddress extends Component
{
    public $userInfo;

    public function render()
    {
        $this->userInfo = DB::table('users')
            ->where('id', Auth::id())
            ->get();

        return view('livewire.delivery-address');
    }
}
