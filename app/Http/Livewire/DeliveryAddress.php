<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DeliveryAddress extends Component
{
    protected $userAddressID;

    protected $listeners = [
        'saveDeliveryAddress' => 'saveDeliveryAddress',
        'changeDeliveryAddress' => 'changeDeliveryAddress',
    ];

    public function render()
    {
        $data = DB::table('users')
            ->select(
                'users.first_name',
                'users.last_name',
                'users.phone_number',
                'user_addresses.id',
                'user_addresses.address',
                'user_addresses.is_default',
                'provinces.province_name',
                'user_addresses.city AS cityID',
                'cities.city_name'
            )
            ->leftJoin('user_addresses', 'users.id', 'user_addresses.user_id')
            ->leftJoin('provinces', 'user_addresses.province', 'provinces.province_id')
            ->leftJoin('cities', 'user_addresses.city', 'cities.city_id')
            ->when($this->userAddressID, function ($query) {
                $query->where('user_addresses.id', $this->userAddressID);
            }, function ($query) {
                $query->where('users.id', Auth::id());
            })
            ->first();

        return view('livewire.delivery-address', ['userInfo' => $data]);
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

        return $this->dispatchBrowserEvent('toastr', [
            'status' => 'success',
            'message' => 'Berhasil menambahkan alamat!'
        ]);
    }

    public function changeDeliveryAddress($data)
    {
        $this->userAddressID = $data;

        // $this->emit('updatedCityIDFromDeliveryAddress', $data);
    }
}
