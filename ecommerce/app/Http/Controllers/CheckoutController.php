<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItemsID = request()->cartid;

        if (!is_array($cartItemsID) || empty($cartItemsID)) {
            return redirect()->back()->with('status', 400);
        }

        $orderItems = $this->getOrderItems($cartItemsID);

        $paymentMethod = DB::table('payment_method')->get();
        $title = 'Checkout';

        return view('checkout', compact('orderItems', 'cartItemsID', 'paymentMethod', 'title'));
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
            $orderDate = $shipmentDate = date('Y-m-d');
            $paymentMethodID = $request->paymentMethodID;
            $shippingTo = $request->shippingTo;

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
                    'shipment_date' => $shipmentDate,
                    'total' => $total,
                    'shipment_fee' => $shipmentFee,
                    'shipping_to' => $shippingTo,
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
}
