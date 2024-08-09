<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Cart as CartModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\AddToWishlist;

class Cart extends Component
{
    use AddToWishlist;

    public $carts = [];
    public $availStock;
    public $cartID;
    public $productID;
    public $selected = [];
    public $selectAll;

    public $listeners = [
        'addAllCartItemsToWishlist' => 'addAllCartItemsToWishlist',
        'removeAllCartItems' => 'removeAllCartItems',
        'addCartItemToWishlist' => 'addCartItemToWishlist',
        'removeCartItem' => 'removeCartItem',
        'cartUpdated' => '$refresh',
    ];

    public function mount()
    {
        if (Auth::check()) {
            $this->carts = DB::table('carts')
                ->join('products', 'carts.product_id', 'products.product_id')
                ->leftJoin('detail_products', function ($join) {
                    $join->on('carts.product_id', 'detail_products.product_id')
                        ->whereColumn('carts.size', 'detail_products.size');
                })
                ->where('carts.user_id', Auth::id())
                ->select(
                    'carts.id AS CartID',
                    'products.id AS ProductID',
                    'products.product_id',
                    'products.name AS ProdName',
                    DB::raw('COALESCE(detail_products.stock, 0) AS AvailStock'),
                    'products.image',
                    DB::raw('detail_products.price * carts.qty AS price'),
                    'carts.size',
                    'carts.qty'
                )
                ->get();

            $this->availStock = $this->carts->filter(fn ($cart) => $cart->AvailStock);
            $getAll = DB::table('carts')->where('user_id', Auth::id())->pluck('id')->toArray();
            $this->selected = $this->availStock->whereIn('CartID', $getAll)->pluck('CartID');
            $this->selectAll = true;
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $dataArray = json_decode($_COOKIE['carts'], true);
            $getAll = [];

            $results = DB::table('products')
                ->leftJoin('detail_products', function ($join) {
                    $join->on('products.product_id', '=', 'detail_products.product_id');
                })
                ->select(
                    'products.id AS ProductID',
                    'products.product_id',
                    'products.name AS ProdName',
                    'products.image',
                    DB::raw('COALESCE(detail_products.stock, 0) AS AvailStock'),
                    DB::raw('detail_products.price AS price'),
                    DB::raw('detail_products.price * detail_products.stock AS total_price'),
                    'detail_products.size'
                )
                ->where(function ($query) use ($dataArray) {
                    foreach ($dataArray as $item) {
                        $query->orWhere(function ($q) use ($item) {
                            $q->where('products.product_id', $item['product_id'])
                                ->where('detail_products.size', $item['size']);
                        });
                    }
                })
                ->get();

            // Processing and formatting the results...
            foreach ($results as &$result) {
                $productId = $result->product_id;
                $size = $result->size;

                foreach ($dataArray as $item) {
                    if ($item['product_id'] == $productId && $item['size'] == $size) {
                        $result->CartID = $item['cart_id'];
                        $result->qty = $item['quantity'];
                        $getAll[] = $item['cart_id'];
                        break;
                    }
                }
            }

            $this->carts = $results;
            $this->availStock = $this->carts->filter(fn ($cart) => $cart->AvailStock);
            $this->selected = $this->availStock->whereIn('CartID', $getAll)->pluck('CartID');
            $this->selectAll = true;
        }
    }

    public function render()
    {
        if (Auth::check()) {
            $this->carts = DB::table('carts')
                ->join('products', 'carts.product_id', 'products.product_id')
                ->leftJoin('detail_products', function ($join) {
                    $join->on('carts.product_id', 'detail_products.product_id')
                        ->whereColumn('carts.size', 'detail_products.size');
                })
                ->where('carts.user_id', Auth::id())
                ->select(
                    'carts.id AS CartID',
                    'products.id AS ProductID',
                    'products.product_id',
                    'products.name AS ProdName',
                    DB::raw('COALESCE(detail_products.stock, 0) AS AvailStock'),
                    'products.image',
                    DB::raw('detail_products.price * carts.qty AS price'),
                    'carts.size',
                    'carts.qty'
                )
                ->get();

            $this->availStock = $this->carts->filter(fn ($cart) => $cart->AvailStock);
            // $getAll = DB::table('carts')->where('user_id', Auth::id())->pluck('id')->toArray();
            // $this->selected = $this->availStock->whereIn('CartID', $getAll)->pluck('CartID');
            // $this->selectAll = true;
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $dataArray = json_decode($_COOKIE['carts'], true);
            $getAll = [];

            $results = DB::table('products')
                ->leftJoin('detail_products', function ($join) {
                    $join->on('products.product_id', '=', 'detail_products.product_id');
                })
                ->select(
                    'products.id AS ProductID',
                    'products.product_id',
                    'products.name AS ProdName',
                    'products.image',
                    DB::raw('COALESCE(detail_products.stock, 0) AS AvailStock'),
                    DB::raw('detail_products.price AS price'),
                    DB::raw('detail_products.price * detail_products.stock AS total_price'),
                    'detail_products.size'
                )
                ->where(function ($query) use ($dataArray) {
                    foreach ($dataArray as $item) {
                        $query->orWhere(function ($q) use ($item) {
                            $q->where('products.product_id', $item['product_id'])
                                ->where('detail_products.size', $item['size']);
                        });
                    }
                })
                ->get();

            // Processing and formatting the results...
            foreach ($results as &$result) {
                $productId = $result->product_id;
                $size = $result->size;

                foreach ($dataArray as $item) {
                    if ($item['product_id'] == $productId && $item['size'] == $size) {
                        $result->CartID = $item['cart_id'];
                        $result->qty = $item['quantity'];
                        $getAll[] = $item['cart_id'];
                        break;
                    }
                }
            }

            $this->carts = $results;
            $this->availStock = $this->carts->filter(fn ($cart) => $cart->AvailStock);
            // $this->selected = $this->availStock->whereIn('CartID', $getAll)->pluck('CartID');
            // $this->selectAll = true;
        }

        return view('livewire.cart');
    }

    public function initProp($cartID, $productID)
    {
        $this->cartID = $cartID;
        $this->productID = $productID;
    }

    public function addAllCartItemsToWishlist()
    {
        $this->addAllCartItemsToWishlistTrait();
    }

    public function removeAllCartItems()
    {
        if (Auth::check()) {
            CartModel::where('user_id', Auth::id())->delete();

            $this->emit('refreshTotalPrice');
            $this->emit('refreshCart');
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $newArray = [];

            setcookie('carts', json_encode($newArray), time() + (3600 * 2), '/');
        }

        return redirect()->route('cart')->with('status', 200);
    }

    public function addCartItemToWishlist()
    {
        $this->addToWishlistTrait($this->productID);
    }

    public function removeCartItem()
    {
        if (Auth::check()) {
            $cart = CartModel::where('id', $this->cartID)
                ->where('user_id', Auth::id())
                ->where('product_id', $this->productID)
                ->first();

            if (!$cart) {
                return $this->dispatchBrowserEvent('toastr', [
                    'status' => 'error',
                    'message' => 'Gagal menghapus pesanan'
                ]);
            }

            $cart->delete();

            $this->emit('refreshTotalPrice');
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            $newArray = [];
            foreach ($dataArray as $item) {
                if ($item['cart_id'] != $this->cartID) {
                    $newArray[] = $item;
                }
            }

            setcookie('carts', json_encode($newArray), time() + (3600 * 2), '/');
        }

        $this->emit('refreshCart');

        return redirect()->route('cart')->with('status', 200);
    }

    public function checkSize($ProductID, $size)
    {
        $res = DB::table('detail_products')
            ->where('product_id', $ProductID)
            ->where('size', $size)
            ->sum('stock');

        return $res;
    }

    public function updateQty($status, $OrderItemsID, $ProductID)
    {
        if (Auth::check()) {
            $orderItems = DB::table('carts')->where('id', $OrderItemsID)->where('product_id', $ProductID)->where('user_id', Auth::id())->first();
            $size = $orderItems->size;
            $qty = ($status == 'increment') ? $orderItems->qty + 1 : $orderItems->qty - 1;
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            foreach ($dataArray as &$row) {
                if ($row['product_id'] == $ProductID && $row['cart_id'] == $OrderItemsID) {
                    $size = $row['size'];
                    $qty = ($status == 'increment') ? $row['quantity'] + 1 : $row['quantity'] - 1;
                }
            }
        }

        $availSize = $this->checkSize($ProductID, $size);

        if ($availSize > 0 && $qty <= $availSize && $qty > 0) {
            if (Auth::check()) {
                DB::table('carts')->where('id', $OrderItemsID)->update(['qty' => $qty]);
            }

            if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
                $dataArray = json_decode($_COOKIE['carts'], true);

                foreach ($dataArray as &$row) {
                    if ($row['product_id'] == $ProductID && $row['cart_id'] == $OrderItemsID) {
                        $row['quantity'] = $qty;
                    }
                }

                setcookie('carts', json_encode($dataArray), time() + (3600 * 2), '/');
            }

            $this->emit('refreshTotalPrice');
            // $this->emit('refreshVoucher');
            $this->emit('cartUpdated');
        }
    }

    public function updatedSelectAll($value)
    {
        if (Auth::check() && $value) {
            $getAll = DB::table('carts')->where('user_id', Auth::id())->pluck('id')->toArray();
            $this->selected = $this->availStock->whereIn('CartID', $getAll)->pluck('CartID');

            $this->emit('setCart', $this->selected);
        } else if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts']) && $value) {
            $dataArray = json_decode($_COOKIE['carts'], true);
            $cartsID = array();

            foreach ($dataArray as $data) {
                array_push($cartsID, $data['cart_id']);
            }

            $this->selected = $this->availStock->whereIn('CartID', $cartsID)->pluck('CartID');

            $this->emit('setCart', $this->selected);
        } else {
            $this->reset('selected');
            $this->emit('setCart', []);
        }
    }

    public function updatedSelected()
    {
        $availStock = $this->availStock->count();

        $this->emit('setCart', $this->selected);

        if (count($this->selected) == $availStock) {
            $this->selectAll = true;
        } else {
            $this->reset('selectAll');
        }
    }
}
