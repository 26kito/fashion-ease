<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TotalPriceCheckout extends Component
{
    public $cartItemsID;
    public $totalPriceCart;
    public $grandTotalPriceCart;
    public $total;
    public $shippingFee;
    public $voucherPrice;
    public $grandTotal;

    protected $listeners = ['setShippingFee' => 'setShippingFee'];

    public function render()
    {
        if (isset($_COOKIE['isVoucherUsed']) && $_COOKIE['isVoucherUsed'] == 'true') {
            $this->voucherPrice = intval($_COOKIE['appliedDiscPrice']);
        }

        $this->grandTotal = rupiah($this->grandTotalPriceCart);

        if ($this->shippingFee) {
            $grandTotal = $this->grandTotalPriceCart + $this->shippingFee;
            $this->grandTotal = rupiah($grandTotal);
        }

        return view('livewire.total-price-checkout');
    }


    public function setShippingFee($data)
    {
        $this->shippingFee = $data;
    }
}
