<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\Cart as TraitsCart;
use Midtrans\Snap as MidtransSnap;

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

        // if (Auth::check() && isset($_COOKIE['cart_id']) && isset($_COOKIE['carts']) && $validateCart == true) {
        //     $tempCart = json_decode($_COOKIE['carts']);
        //     $selectedCartsID = json_decode($_COOKIE['selectedCart']);
        //     $productsID = array();
        //     $productsSize = array();

        //     foreach ($tempCart as $row) {
        //         $tempCartID = $row->cart_id;

        //         if (in_array($tempCartID, $selectedCartsID)) {
        //             $productId = $row->product_id;
        //             $qty = $row->quantity;
        //             $size = $row->size;
        //             array_push($productsID, $row->product_id);
        //             array_push($productsSize, $row->size);
        //             $this->addToCartTrait($productId, $size, $qty);
        //         }
        //     }

        //     session()->forget('validateCart');
        //     // delete cookie
        //     setcookie('cart_id', '', time() - 1, '/');
        //     setcookie('carts', '', time() - 1, '/');
        //     setcookie('selectedCart', '', time() - 1, '/');

        //     $cartItemsID = DB::table('carts')
        //         ->where('user_id', Auth::id())
        //         ->whereIn('product_id', $productsID)
        //         ->whereIn('size', $productsSize)
        //         ->pluck('id')
        //         ->toArray();

        //     if (isset($_COOKIE['appliedDiscPrice'])) {
        //         $appliedDiscPrice = $_COOKIE['appliedDiscPrice'];
        //     } else {
        //         $appliedDiscPrice = 0;
        //     }

        //     $totalPriceCart = $_COOKIE['totalPriceCart'];
        //     $grandTotalPriceCart = $totalPriceCart - $appliedDiscPrice;
        // }

        $orderItems = $this->getOrderItems($cartItemsID);

        $paymentMethod = DB::table('payment_method')->get();
        $title = 'Checkout | ';

        return view('checkout', compact('orderItems', 'cartItemsID', 'paymentMethod', 'title', 'totalPriceCart', 'grandTotalPriceCart'));
    }

    private function getOrderItems(array $cartItemsID)
    {
        return Cart::join('products', 'carts.product_id', '=', 'products.product_id')
            ->leftJoin('detail_products', function ($join) {
                $join->on('carts.product_id', 'detail_products.product_id')
                    ->whereColumn('carts.size', 'detail_products.size');
            })
            ->select(
                'carts.id AS CartID',
                'carts.user_id',
                'products.id AS ProductID',
                'products.product_id',
                'products.name AS ProdName',
                'products.image',
                DB::raw('detail_products.price * carts.qty AS total_price'),
                'detail_products.price',
                'carts.size',
                'carts.qty'
            )
            ->where('carts.user_id', Auth::id())
            ->whereIn('carts.id', $cartItemsID)
            ->get();
    }

    public function saveOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            $total = collect($request->data)->sum('total_price');
            $shipmentFee = $request->shippingCost;
            $grandTotal = $total + $shipmentFee;
            $userID = $request->data[0]['user_id'];
            $orderDate = date('Y-m-d');
            // $paymentMethodID = $request->paymentMethodID;
            $shippingTo = $request->shippingTo;
            $discValue = $request->voucherFee;
            $getMaxOrderID = DB::table('orders')->max('order_id');
            $latestNum = substr($getMaxOrderID, -6);

            if ($getMaxOrderID == null || $latestNum == '999999') {
                $num = '000001';
            } else {
                $num = strval($latestNum + 1);
            }

            $orderID = 'OID' . '-' . date('Ymdhi') . $userID . substr('0000' . $num, strlen($num));

            $params = array(
                'transaction_details' => array(
                    'order_id' => $orderID,
                    'gross_amount' => $grandTotal,
                ),
                'customer_details' => array(
                    'first_name' => Auth::user()->first_name,
                    'last_name' => Auth::user()->last_name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone_number,
                ),
            );

            $midtrans = MidtransSnap::createTransaction($params);

            DB::table('orders')
                ->insert([
                    'order_id' => $orderID,
                    'user_id' => $userID,
                    'order_date' => $orderDate,
                    'status_order_id' => 'SO007',
                    'shipment_date' => null,
                    'total' => $total,
                    'shipment_fee' => $shipmentFee,
                    'shipping_to' => $shippingTo,
                    'discount' => $discValue,
                    'grand_total' => $grandTotal,
                    // 'payment_method_id' => $paymentMethodID
                    'midtrans_token' => $midtrans->token,
                    'midtrans_redirect_url' => $midtrans->redirect_url
                ]);

            foreach ($request->data as $row) {
                DB::table('order_items')
                    ->insert([
                        'order_id' => $orderID,
                        'product_id' => $row['product_id'],
                        'size' => $row['size'],
                        'price' => $row['price'],
                        'qty' => $row['qty']
                    ]);

                DB::table('carts')
                    ->where('product_id', $row['product_id'])
                    ->where('size', $row['size'])
                    ->where('user_id', $userID)
                    ->delete();
            }

            DB::commit();

            return response()->json(['status' => 'Success', 'message' => 'Pesanan berhasil dibuat', 'data' => $midtrans->token], 200);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['status' => 'Failed', 'message' => 'Error'], 500);
        }
    }

    // public function paymentStatus()
    // {
    //     $title = 'Payment | ';

    //     // if (!isset($_COOKIE['payment'])) {
    //     //     return redirect()->route('home');
    //     // }

    //     // setcookie('payment', '', time() - 1, '/');

    //     return view('after-payment')->with('title', $title);
    // }

    public function redirectPayment()
    {
        $title = 'Payment | ';

        return view('payment-success')->with('title', $title);
    }
}
