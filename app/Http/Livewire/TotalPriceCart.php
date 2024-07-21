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
    ];

    // public function mount()
    // {
    //     if (Auth::check()) {
    //         $query = DB::table('carts')
    //             ->join('products', 'carts.product_id', '=', 'products.id')
    //             ->select('carts.id')
    //             ->where('carts.user_id', Auth::id())
    //             ->get();

    //         foreach ($query as $key => $value) {
    //             array_push($this->cartsID, $value->id);
    //         }
    //     }

    //     if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
    //         $dataArray = json_decode($_COOKIE['carts'], true);

    //         foreach ($dataArray as $data) {
    //             array_push($this->cartsID, $data['cart_id']);
    //         }
    //     }

    //     $total = $this->calculateCartTotalPrice();

    //     if ($this->appliedDiscPrice) {
    //         $this->total = $total;
    //         $this->grandTotal = $total - $this->appliedDiscPrice;
    //     } else {
    //         $this->total = $total;
    //         $this->grandTotal = $total;
    //     }

    //     if (isset($_COOKIE['isVoucherUsed']) && $_COOKIE['isVoucherUsed'] == true) {
    //         $this->isVoucherUsed = true;
    //         $this->appliedDiscPrice = $_COOKIE['appliedDiscPrice'];
    //     } else {
    //         $this->reset('isVoucherUsed');
    //         $this->reset('appliedDiscPrice');
    //     }

    //     setcookie('totalPriceCart', $total, time() + (3600 * 2), '/');
    // }

    public function render()
    {
        if (Auth::check()) {
            $query = DB::table('carts')
                ->join('products', 'carts.product_id', 'products.product_id')
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

        $total = $this->calculateCartTotalPrice();

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

        setcookie('totalPriceCart', $total, time() + (3600 * 2), '/');

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

    // public function getTotalPrice()
    // {
    //     return $this->total;
    // }

    private function calculateCartTotalPrice()
    {
        $total = 0;

        if (Auth::check() && count($this->cartsID) > 0) {
            $total = DB::table('carts')
                ->join('detail_products', function ($join) {
                    $join->on('carts.product_id', '=', 'detail_products.product_id')
                        ->on('carts.size', '=', 'detail_products.size');
                })
                ->selectRaw('SUM(detail_products.price * carts.qty) AS price')
                ->where('carts.user_id', Auth::id())
                ->whereIn('carts.id', $this->cartsID)
                ->value('price');

            // if ($this->cartsID) {
            //     $query->whereIn('carts.id', $this->cartsID);
            // }

            // $total = $query->value('price');
        }

        // dd($this->cartsID);
        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts']) && count($this->cartsID) > 0) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            // $tempData = [];
            // if ($this->cartsID) {
            //     foreach ($dataArray as $key => $value) {
            //         if (in_array($value['cart_id'], $this->cartsID)) {
            //             array_push($tempData, $value);
            //         }
            //     }
            // }

            $results = DB::table('products')
                ->join('detail_products', 'products.product_id', 'detail_products.product_id')
                ->select('products.product_id', 'detail_products.size', 'detail_products.price AS price')
                // ->whereIn('detail_products.product_id', array_column($dataArray, 'product_id'))
                // ->where('detail_products.size', array_column($dataArray, 'size'))
                ->where(function ($query) use ($dataArray) {
                    foreach ($dataArray as $item) {
                        $query->orWhere(function ($q) use ($item) {
                            $q->where('products.product_id', $item['product_id'])
                                ->where('detail_products.size', $item['size']);
                        });
                    }
                })
                ->get();

            // if ($this->cartsID) {
            //     $query->whereIn('detail_products.product_id', array_column($dataArray, 'product_id'))
            //         ->where('detail_products.size', array_column($dataArray, 'size'));
            // }

            // $results = $query->get();

            // dd($results);
            // Processing and formatting the results...
            foreach ($results as &$result) {
                $productId = $result->product_id;

                foreach ($dataArray as $item) {
                    // dd($results);
                    if ($item['product_id'] == $productId && $item['size'] == $result->size) {
                        $total += $result->price * $item['quantity'];
                        break;
                    }
                }
            }
        }

        return $total;
    }
}
