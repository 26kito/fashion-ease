<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TotalPriceCart extends Component
{
    public $total;

    protected $listeners = ['refreshTotalPrice' => '$refresh'];

    public function render()
    {
        $total = 0;

        if (Auth::check()) {
            $total = DB::table('carts')
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->where('carts.user_id', Auth::id())
                ->selectRaw('SUM(products.price * carts.qty) AS price')
                ->value('price');
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
}
