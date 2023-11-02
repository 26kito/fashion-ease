<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Promo extends Component
{
    public $vouchers;

    public function render()
    {
        $this->vouchers = DB::table('vouchers')->orderBy('id', 'ASC')->get();

        foreach ($this->vouchers as $row) {
            $row->selected = false;
        }

        return view('livewire.promo');
    }
}
