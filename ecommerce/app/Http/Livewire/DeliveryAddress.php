<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryAddress extends Component
{
    protected $listeners = ['saveDeliveryAddress' => 'saveDeliveryAddress'];

    public function render()
    {
        $getUserAddress = DB::table('users')
            ->leftJoin('user_addresses', 'users.id', 'user_addresses.user_id')
            ->leftJoin('provinces', 'user_addresses.province', 'provinces.province_id')
            ->leftJoin('cities', 'user_addresses.city', 'cities.city_id')
            ->where('users.id', Auth::id())
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.phone_number',
                'user_addresses.id',
                'user_addresses.address',
                'user_addresses.is_default',
                'provinces.province_name',
                'cities.city_name'
            );

        $checkAddress = $getUserAddress->first();

        if (!$checkAddress->address) {
            $userInfo = $getUserAddress->first();
        } else {
            $userInfo = $getUserAddress->where('is_default', 1)->first();
        }

        return view('livewire.delivery-address', ['userInfo' => $userInfo]);
    }

    public function saveDeliveryAddress($data)
    {
        $address = $data['address'];
        $province = $data['province'];
        $city = $data['city'];

        DB::table('user_addresses')
            ->insert([
                'user_id' => Auth::id(),
                'address' => $address,
                'province' => $province,
                'city' => $city,
                'is_default' => 1
            ]);

        $this->emit('setAddress');

        return $this->dispatchBrowserEvent('toastr', [
            'status' => 'success',
            'message' => 'Berhasil menambahkan alamat!'
        ]);
    }
}
