<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryInfo extends Component
{
    public $serviceDelivery;
    public $custAddress;
    public $choosenDeliveryCourier;
    public $choosenServiceName;
    public $choosenServiceFee;
    public $choosenServiceEtd;

    protected $listeners = [
        'setAddress' => '$refresh',
        'refreshDeliveryService' => 'refreshDeliveryService',
        'setDeliveryService' => 'setDeliveryService',
        'setDeliveryCourier' => 'setDeliveryCourier',
    ];

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

    public function setDeliveryService($data)
    {
        $temp = json_decode($data);
        $this->choosenServiceName = $temp->serviceName;
        $this->choosenServiceFee = $temp->serviceFee;
        $this->choosenServiceEtd = $temp->etd;

        $this->emit('setShippingFee', $this->choosenServiceFee);
    }

    public function setDeliveryCourier($data)
    {
        $this->choosenDeliveryCourier = strtoupper($data);
    }

    public function refreshDeliveryService()
    {
        $this->reset('choosenServiceName');
    }
}
