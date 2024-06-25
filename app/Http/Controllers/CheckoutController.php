<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\cart as TraitsCart;

class CheckoutController extends Controller
{
    use TraitsCart;

    public function index()
    {
        $cartItemsID = request()->cartid;
        $totalPriceCart = request()->total_price_cart;
        $grandTotalPriceCart = request()->grand_total_cart;
        $validateCart = session('validateCart');
        
        if ((!is_array($cartItemsID) || empty($cartItemsID)) && $validateCart == null) {
            return redirect()->back()->with('status', 400);
        }

        if (!Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts']) && (is_array($cartItemsID) || !empty($cartItemsID))) {
            setcookie('selectedCart', json_encode($cartItemsID), time() + (3600 * 2), '/');
            session(['validateCart' => true]);

            return redirect()->route('login');
        }

        if (Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts']) && $validateCart == true) {
            $tempCart = json_decode($_COOKIE['carts']);
            $selectedCartsID = json_decode($_COOKIE['selectedCart']);
            $productsID = array();
            $productsSize = array();

            foreach ($tempCart as $row) {
                $tempCartID = $row->cart_id;

                if (in_array($tempCartID, $selectedCartsID)) {
                    $productId = $row->product_id;
                    $qty = $row->quantity;
                    $size = $row->size;
                    array_push($productsID, $row->product_id);
                    array_push($productsSize, $row->size);
                    $this->addToCartTrait($productId, $size, $qty);
                }
            }

            session()->forget('validateCart');
            // delete cookie
            setcookie('cart_id', '', time() - 1, '/');
            setcookie('carts', '', time() - 1, '/');
            setcookie('selectedCart', '', time() - 1, '/');

            $cartItemsID = DB::table('carts')
                ->where('user_id', Auth::id())
                ->whereIn('product_id', $productsID)
                ->whereIn('size', $productsSize)
                ->pluck('id')
                ->toArray();

            if (isset($_COOKIE['appliedDiscPrice'])) {
                $appliedDiscPrice = $_COOKIE['appliedDiscPrice'];
            } else {
                $appliedDiscPrice = 0;
            }

            $totalPriceCart = $_COOKIE['totalPriceCart'];
            $grandTotalPriceCart = $totalPriceCart - $appliedDiscPrice;
        }

        $orderItems = $this->getOrderItems($cartItemsID);

        $paymentMethod = DB::table('payment_method')->get();
        $title = 'Checkout';

        return view('checkout', compact('orderItems', 'cartItemsID', 'paymentMethod', 'title', 'totalPriceCart', 'grandTotalPriceCart'));
    }

    private function getOrderItems(array $cartItemsID)
    {
        return Cart::join('products', 'carts.product_id', '=', 'products.id')
            ->select(
                'carts.id AS CartID',
                'carts.user_id',
                'products.id AS ProductID',
                'products.product_id',
                'products.name AS ProdName',
                'products.image',
                DB::raw('products.price * carts.qty AS Price'),
                'carts.size',
                'carts.qty'
            )
            ->where('carts.user_id', Auth::id())
            ->whereIn('carts.id', $cartItemsID)
            ->get();
    }

    public function saveOrder(Request $request)
    {
        DB::transaction(function () use ($request) {
            $total = collect($request->data)->sum('Price');
            $shipmentFee = $request->shippingCost;
            $grandTotal = $total + $shipmentFee;
            $userID = $request->data[0]['user_id'];
            $orderDate = date('Y-m-d');
            $paymentMethodID = $request->paymentMethodID;
            $shippingTo = $request->shippingTo;
            $discValue = $request->voucherFee;
            $getMaxOrderID = DB::table('orders')->max('order_id');
            $latestNum = substr($getMaxOrderID, -6);

            if ($getMaxOrderID == null || $latestNum == '999999') {
                $num = '000001';
            } else {
                $num = strval($latestNum + 1);
            }

            $orderID = 'PO' . '-' . date('Ymdhi') . $userID . substr('000000' . $num, strlen($num));

            DB::table('orders')
                ->insert([
                    'order_id' => $orderID,
                    'user_id' => $userID,
                    'order_date' => $orderDate,
                    'status_order_id' => 1,
                    'shipment_date' => null,
                    'total' => $total,
                    'shipment_fee' => $shipmentFee,
                    'shipping_to' => $shippingTo,
                    'discount' => $discValue,
                    'grand_total' => $grandTotal,
                    'payment_method_id' => $paymentMethodID
                ]);

            foreach ($request->data as $row) {
                DB::table('order_items')
                    ->insert([
                        'order_id' => $orderID,
                        'product_id' => $row['ProductID'],
                        'size' => $row['size'],
                        'price' => $row['Price'],
                        'qty' => $row['qty']
                    ]);

                DB::table('carts')
                    ->where('product_id', $row['ProductID'])
                    ->where('size', $row['size'])
                    ->where('user_id', $userID)
                    ->delete();
            }
        });

        return response()->json(['status' => 'Success', 'message' => 'Pesanan berhasil dibuat'], 200);
    }

    public function paymentStatus()
    {
        $title = 'Payment';

        if (!isset($_COOKIE['payment'])) {
            return redirect()->route('home');
        }

        setcookie('payment', '', time() - 1, '/');

        return view('after-payment')->with('title', $title);
    }
}
