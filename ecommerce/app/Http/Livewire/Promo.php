<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Promo extends Component
{
    public $vouchers;
    public $selected = [];

    public $listeners = [
        'setSelectedVoucher' => 'setSelectedVoucher',
    ];

    public function render()
    {
        $vouchers = DB::table('vouchers')->where('is_active', 1)->orderBy('id', 'ASC')->get();

        $this->vouchers = $vouchers;
        $this->updateAvailability();

        return view('livewire.promo');
    }

    public function updateAvailability()
    {
        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        $totalPriceCart = 0;

        if (isset($_COOKIE['totalPriceCart'])) {
            $totalPriceCart = $_COOKIE['totalPriceCart'];
        }

        foreach ($this->vouchers as &$row) {
            if ($row->is_active == 1 && ($currentDate >= $row->start_date && $currentDate <= $row->end_date) && ($totalPriceCart >= $row->minimum_price)) {
                $row->available = true;
            } else {
                $row->available = false;
            }
        }

        $this->emit('refresh');
    }

    public function setSelectedVoucher($voucherID)
    {
        array_push($this->selected, $voucherID);
    }
}
