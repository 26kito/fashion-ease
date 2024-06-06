<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TotalPriceCart extends Component
{
    public $grandTotal;
    public $total;
    public $cartsID = [];
    public $appliedDiscPrice;
    public $isVoucherUsed;

    protected $listeners = [
        'refreshTotalPrice' => '$refresh',
        'setCart' => 'setCart',
        'setAppliedDiscPrice' => 'setAppliedDiscPrice',
        'setVoucher' => 'setVoucher',
    ];

    public function mount()
    {
        if (Auth::check()) {
            $query = DB::table('carts')
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->select('carts.id')
                ->where('carts.user_id', Auth::id())
                ->get();

            foreach ($query as $key => $value) {
                array_push($this->cartsID, $value->id);
            }
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            foreach ($dataArray as $data) {
                array_push($this->cartsID, $data['cart_id']);
            }
        }

        $total = 0;

        if (Auth::check() && count($this->cartsID) > 0) {
            $query = DB::table('carts')
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->selectRaw('SUM(products.price * carts.qty) AS price')
                ->where('carts.user_id', Auth::id());

            if ($this->cartsID) {
                $query->whereIn('carts.id', $this->cartsID);
            }

            $total = $query->value('price');
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts']) && count($this->cartsID) > 0) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            $tempData = [];
            if ($this->cartsID) {
                foreach ($dataArray as $key => $value) {
                    if (in_array($value['cart_id'], $this->cartsID)) {
                        array_push($tempData, $value);
                    }
                }
            }

            $query = DB::table('products')
                ->join('detail_products', 'products.id', '=', 'detail_products.dp_id')
                ->select('products.id AS ProductID', 'products.price AS price');

            if ($this->cartsID) {
                $query->whereIn('detail_products.dp_id', array_column($tempData, 'product_id'));
            }

            $results = $query->get();

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

        if ($this->appliedDiscPrice) {
            $this->total = $total;
            $this->grandTotal = $total - $this->appliedDiscPrice;
        } else {
            $this->total = $total;
            $this->grandTotal = $total;
        }

        if (isset($_COOKIE['appliedDiscPrice'])) {
            $this->isVoucherUsed = true;
            $this->appliedDiscPrice = $_COOKIE['appliedDiscPrice'];
        } else {
            $this->reset('isVoucherUsed');
            $this->reset('appliedDiscPrice');
        }

        setcookie('totalPriceCart', $total, time() + (3600 * 2), '/');
    }

    public function render()
    {
        $total = 0;

        if (Auth::check() && count($this->cartsID) > 0) {
            $query = DB::table('carts')
                ->join('products', 'carts.product_id', '=', 'products.id')
                ->selectRaw('SUM(products.price * carts.qty) AS price')
                ->where('carts.user_id', Auth::id());

            if ($this->cartsID) {
                $query->whereIn('carts.id', $this->cartsID);
            }

            $total = $query->value('price');
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts']) && count($this->cartsID) > 0) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            $tempData = [];
            if ($this->cartsID) {
                foreach ($dataArray as $key => $value) {
                    if (in_array($value['cart_id'], $this->cartsID)) {
                        array_push($tempData, $value);
                    }
                }
            }

            $query = DB::table('products')
                ->join('detail_products', 'products.id', '=', 'detail_products.dp_id')
                ->select('products.id AS ProductID', 'products.price AS price');

            if ($this->cartsID) {
                $query->whereIn('detail_products.dp_id', array_column($tempData, 'product_id'));
            }

            $results = $query->get();

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

        if ($this->appliedDiscPrice) {
            $this->total = $total;
            $this->grandTotal = $total - $this->appliedDiscPrice;
        } else {
            $this->total = $total;
            $this->grandTotal = $total;
        }

        if (isset($_COOKIE['isVoucherUsed']) && $_COOKIE['isVoucherUsed'] == true) {
            $this->isVoucherUsed = true;
            $this->appliedDiscPrice = $_COOKIE['appliedDiscPrice'];
        } else {
            $this->reset('isVoucherUsed');
            $this->reset('appliedDiscPrice');
        }

        setcookie('totalPriceCart', $this->total, time() + (3600 * 2), '/');
        
        return view('livewire.total-price-cart');
    }

    public function setCart($cartID)
    {
        $this->cartsID = $cartID;
    }

    public function setAppliedDiscPrice($appliedDiscPrice)
    {
        if ($appliedDiscPrice == 0) {
            $this->reset('appliedDiscPrice');
        }

        $this->appliedDiscPrice = $appliedDiscPrice;
    }

    public function getTotalPrice()
    {
        return $this->total;
    }
}
