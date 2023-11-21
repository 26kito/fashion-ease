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
        // $this->vouchers = DB::table('vouchers')->where('is_active', 1)->orderBy('id', 'ASC')->get();

        // foreach ($this->vouchers as $row) {
        //     $row->selected = false;
        // }

        $vouchers = DB::table('vouchers')->where('is_active', 1)->orderBy('id', 'ASC')->get();
        $currentDate = Carbon::now()->format('Y-m-d H:i:s');

        foreach ($vouchers as $row) {
            if ($row->is_active == 1 && $currentDate >= $row->start_date && $row->end_date <= $currentDate) {
                $row->available = true;
            } else {
                $row->available = false;
            }

            $row->selected = false;
        }

        $this->vouchers = $vouchers;

        return view('livewire.promo');
    }

    public function setSelectedVoucher($voucherID)
    {
        array_push($this->selected, $voucherID);
    }
}
