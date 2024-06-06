<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class PromoContent extends Component
{
    public $vouchers;

    public function render()
    {
        $this->vouchers = DB::table('vouchers')->where('is_active', 1)->orderBy('id', 'ASC')->get();

        $this->updateAvailability();

        return view('livewire.promo-content');
    }

    public function updateAvailability()
    {
        $currentDate = now()->format('Y-m-d H:i:s');
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
    }
}
