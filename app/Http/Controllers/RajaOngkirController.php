<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends Controller
{
    protected $rajaOngkirKey;
    protected $rajaOngkirUrl;

    public function __construct()
    {
        $this->rajaOngkirKey = $_ENV['RAJAONGKIR_API_KEY'];
        $this->rajaOngkirUrl = $_ENV['RAJAONGKIR_API_URL'];
    }

    public function getProvince()
    {
        $fetch = Http::withHeaders([
            'key' => $this->rajaOngkirKey
        ])
            ->acceptJson()
            ->get("$this->rajaOngkirUrl/province")
            ->json();

        $data = $fetch['rajaongkir']['results'];

        return response()->json($data);
    }

    public function getCity($provinceID)
    {
        $fetch = Http::withHeaders([
            'key' => $this->rajaOngkirKey
        ])
            ->acceptJson()
            ->get("$this->rajaOngkirUrl/city?province=$provinceID")
            ->json();

        $data = $fetch['rajaongkir']['results'];

        return response()->json($data);
    }

    public function checkCost(Request $request)
    {
        $origin = $request->origin;
        $destination = $request->destination;
        $weight = $request->weight;
        $courierCode = $request->courierCode;

        $fetch = Http::withHeaders([
            'key' => $this->rajaOngkirKey
        ])
            ->post("$this->rajaOngkirUrl/cost", [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $weight,
                'courier' => $courierCode
            ]);

        $data = $fetch['rajaongkir']['results'];

        return response()->json($data);
    }
}
