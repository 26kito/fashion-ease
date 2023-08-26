<?php

namespace App\Http\Livewire;

use App\Models\Cart as CartModel;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\addToWishlist;

class Cart extends Component
{
    use addToWishlist;

    public $page;
    public $carts = [];
    public $availStock;
    public $cartID;
    public $productID;
    public $selected = [];
    public $selectAll = false;

    public $listeners = [
        'addAllCartItemsToWishlist' => 'addAllCartItemsToWishlist',
        'removeAllCartItems' => 'removeAllCartItems',
        'addCartItemToWishlist' => 'addCartItemToWishlist',
        'removeCartItem' => 'removeCartItem',
        'cartUpdated' => '$refresh',
    ];

    public function mount($page)
    {
        $this->page = $page;
    }

    public function render()
    {
        if (Auth::check()) {
            $this->carts = DB::table('carts')
                ->join('products', 'carts.product_id', 'products.id')
                ->leftJoin('detail_products', function ($join) {
                    $join->on('carts.product_id', 'detail_products.dp_id')
                        ->whereColumn('detail_products.size', 'carts.size');
                })
                ->where('carts.user_id', Auth::id())
                ->select(
                    'carts.id AS CartID',
                    'products.id AS ProductID',
                    'products.product_id',
                    'products.name AS ProdName',
                    DB::raw('COALESCE(detail_products.stock, 0) AS AvailStock'),
                    'products.image',
                    DB::raw('products.price * carts.qty AS price'),
                    'carts.size',
                    'carts.qty'
                )
                ->get();

            $this->availStock = $this->carts->filter(fn ($cart) => $cart->AvailStock);
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            $results = DB::table('products')
                ->join('detail_products', 'products.id', '=', 'detail_products.dp_id')
                ->select(
                    'products.id AS ProductID',
                    'products.product_id',
                    'products.name AS ProdName',
                    'products.image',
                    DB::raw('COALESCE(detail_products.stock, 0) AS AvailStock'),
                    DB::raw('products.price AS price'),
                    DB::raw('products.price * detail_products.stock AS total_price'),
                    'detail_products.size'
                )
                ->whereIn('detail_products.dp_id', array_column($dataArray, 'product_id'))
                ->whereIn('detail_products.size', array_column($dataArray, 'size'))
                ->get();

            // Processing and formatting the results...
            foreach ($results as &$result) {
                $productId = $result->ProductID;

                foreach ($dataArray as $item) {
                    if ($item['product_id'] == $productId) {
                        $result->CartID = $item['cart_id'];
                        $result->qty = $item['quantity'];
                        break;
                    }
                }
            }

            $this->carts = $results;
            $this->availStock = $this->carts->filter(fn ($cart) => $cart->AvailStock);
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

        return redirect($this->page)->with('status', 200);
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

            if ($cart) {
                $cart->delete();

                $this->emit('refreshTotalPrice');
                $this->emit('refreshCart');

                return redirect($this->page)->with('status', 200);
            } else {
                return $this->dispatchBrowserEvent('toastr', [
                    'status' => 'error',
                    'message' => 'Gagal menghapus pesanan'
                ]);
            }
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            $newArray = [];
            foreach ($dataArray as $item) {
                if ($item['cart_id'] != $this->cartID) {
                    $newArray[] = $item;
                }
            }

            $this->emit('refreshCart');

            setcookie('carts', json_encode($newArray), time() + (3600 * 2), '/');

            return redirect($this->page)->with('status', 200);
        }
    }

    public function checkSize($ProductID, $size)
    {
        $res = DB::table('detail_products')
            ->where('dp_id', $ProductID)
            ->where('size', $size)
            ->sum('stock');

        return $res;
    }

    public function increment($OrderItemsID, $ProductID)
    {
        if (Auth::check()) {
            $orderItems = DB::table('carts')->where('id', $OrderItemsID)->where('product_id', $ProductID)->where('user_id', Auth::id())->first();
            $size = $orderItems->size;
            $qty = $orderItems->qty + 1;
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            foreach ($dataArray as &$row) {
                if ($row['product_id'] == $ProductID) {
                    $size = $row['size'];
                    $qty = $row['quantity'] + 1;
                }
            }
        }

        $availSize = $this->checkSize($ProductID, $size);

        if ($availSize > 0 && $qty <= $availSize) {
            if (Auth::check()) {
                DB::table('carts')->where('id', $OrderItemsID)->update(['qty' => $qty]);
            }

            if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
                $dataArray = json_decode($_COOKIE['carts'], true);

                foreach ($dataArray as &$row) {
                    if ($row['product_id'] == $ProductID) {
                        $row['quantity'] = $qty;
                    }
                }

                setcookie('carts', json_encode($dataArray), time() + (3600 * 2), '/');
            }

            $this->emit('cartUpdated');
            $this->emit('refreshTotalPrice');
        }
    }

    public function decrement($OrderItemsID, $ProductID)
    {
        if (Auth::check()) {
            $orderItems = DB::table('carts')->where('id', $OrderItemsID)->where('product_id', $ProductID)->where('user_id', Auth::id())->first();
            $size = $orderItems->size;
            $qty = $orderItems->qty - 1;
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts'])) {
            $dataArray = json_decode($_COOKIE['carts'], true);

            foreach ($dataArray as &$row) {
                if ($row['product_id'] == $ProductID) {
                    $size = $row['size'];
                    $qty = $row['quantity'] - 1;
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
                    if ($row['product_id'] == $ProductID) {
                        $row['quantity'] = $qty;
                    }
                }

                setcookie('carts', json_encode($dataArray), time() + (3600 * 2), '/');
            }

            $this->emit('cartUpdated');
            $this->emit('refreshTotalPrice');
        }
    }

    public function updatedSelectAll($value)
    {
        if (Auth::check()) {
            if (Auth::check() && $value) {
                $getAll = DB::table('carts')->where('user_id', Auth::id())->pluck('id')->toArray();
                $this->selected = $this->availStock->whereIn('CartID', $getAll)->pluck('CartID');
            } else {
                $this->reset('selected');
            }
        }

        if (!Auth::check()) {
            if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts']) && $value) {
                $dataArray = json_decode($_COOKIE['carts'], true);
                $cartsID = array();

                foreach ($dataArray as $data) {
                    array_push($cartsID, $data['cart_id']);
                }

                $this->selected = $this->availStock->whereIn('CartID', $cartsID)->pluck('CartID');
            } else {
                $this->reset('selected');
            }
        }
    }

    public function updatedSelected($value)
    {
        $availStock = $this->availStock->count();

        if (count($this->selected) == $availStock) {
            $this->selectAll = true;
        } else {
            $this->reset('selectAll');
        }
    }
}
