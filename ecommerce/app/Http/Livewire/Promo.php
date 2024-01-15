<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Promo extends Component
{
    public $keyword;
    public $message;
    public $vouchers;
    public $totalPriceCart;
    public $selected = [];

    public $listeners = [
        'setSelectedVoucher' => 'setSelectedVoucher',
        'refreshVoucher' => '$refresh',
        'setTotalPriceCart' => 'setTotalPriceCart'
    ];

    public function render()
    {
        return view('livewire.promo');
    }

    public function updateAvailability()
    {
        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        $totalPriceCart = 0;

        if (isset($_COOKIE['totalPriceCart'])) {
            $totalPriceCart = $_COOKIE['totalPriceCart'];
        }

        if (isset($_COOKIE['totalPriceCart']) && $this->totalPriceCart) {
            $totalPriceCart = $this->totalPriceCart;
        }

        foreach ($this->vouchers as &$row) {
            if ($row->is_active == 1 && ($currentDate >= $row->start_date && $currentDate <= $row->end_date) && ($totalPriceCart >= $row->minimum_price)) {
                $row->available = true;
            } else {
                $row->available = false;
            }
        }

        return $this->vouchers;
    }

    public function setSelectedVoucher($voucherID)
    {
        array_push($this->selected, $voucherID);
    }

    public function setTotalPriceCart($data)
    {
        $this->totalPriceCart = $data;
    }

    public function searchVoucher()
    {
        $data = DB::table('vouchers')->where('code', $this->keyword)->first();

        if ($data) {
            $voucher = $this->checkVoucher($data->code);

            $this->vouchers = [
                'code' => $voucher->code,
                'available' => $voucher->available,
            ];
        } else {
            return $this->dispatchBrowserEvent('toastr', [
                'status' => 'error',
                'message' => 'Voucher tidak ditemukan'
            ]);
        }
    }

    public function checkVoucher($voucherCode)
    {
        $voucher = DB::table('vouchers')->where('code', $voucherCode)->first();

        $currentDate = Carbon::now()->format('Y-m-d H:i:s');

        if (
            $voucher->is_active == 1 && ($currentDate >= $voucher->start_date && $currentDate <= $voucher->end_date) &&
            ($this->totalPriceCart >= $voucher->minimum_price) && $voucher->quota != 0
        ) {
            $voucher->available = true;
        } else {
            $voucher->available = false;
        }

        return $voucher;
    }
}
