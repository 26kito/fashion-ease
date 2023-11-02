<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TotalPriceCart extends Component
{
    public $total;
    public $cartsID = [];

    protected $listeners = [
        'refreshTotalPrice' => '$refresh',
        'setCart' => 'setCart'
    ];

    public function render()
    {
        $total = 0;

        if (Auth::check()) {
            $query = DB::table('carts')
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->selectRaw('SUM(products.price * carts.qty) AS price')
                ->where('carts.user_id', Auth::id());

            if ($this->cartsID) {
                $query->whereIn('carts.id', $this->cartsID);
            }

            $total = $query->value('price');
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            $results = DB::table('products')
                ->join('detail_products', 'products.id', '=', 'detail_products.dp_id')
                ->select('products.id AS ProductID', 'products.price AS price')
                ->whereIn('detail_products.dp_id', array_column($dataArray, 'product_id'))
                ->get();

            // Processing and formatting the results...
            foreach ($results as &$result) {
                $productId = $result->ProductID;

                foreach ($dataArray as $item) {
                    if ($item['product_id'] == $productId) {
                        $total += $result->price * $item['quantity'];
                        break;
                    }
                }
            }
        }

        $this->total = rupiah($total);

        return view('livewire.total-price-cart');
    }

    public function setCart($cartID)
    {
        $this->cartsID = $cartID;
    }
}
