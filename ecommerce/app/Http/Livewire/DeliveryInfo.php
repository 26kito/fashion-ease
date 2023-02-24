<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryInfo extends Component
{
    public $serviceDelivery;
    public $custAddress;
    public $delivery;

    protected $listeners = ['setAddress' => '$refresh'];
    
    public function render()
    {
        $this->serviceDelivery = DB::table('couriers')->get();

        $this->custAddress = DB::table('user_addresses')
            ->where('user_id', Auth::id())
            ->get();
        
        return view('livewire.delivery-info');
    }

    public function dehydrate()
    {
        $this->dispatchBrowserEvent('addressChanged', $this->custAddress);
    }
}
