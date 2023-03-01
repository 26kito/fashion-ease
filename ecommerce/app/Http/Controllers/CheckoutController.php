<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            $cartItemsID = $request->cartid;
            $title = 'Checkout';

            if ($cartItemsID) {
                $orderItems = DB::table('carts')
                    ->join('products', 'carts.product_id', 'products.id')
                    ->where('carts.user_id', Auth::id())
                    ->whereIn('carts.id', $cartItemsID)
                    ->select(
                        'carts.id AS CartID',
                        'carts.user_id',
                        'products.id AS ProductID',
                        'products.product_id',
                        'products.name AS ProdName',
                        'products.image',
                        DB::raw("products.price * carts.qty AS Price"),
                        'carts.size',
                        'carts.qty'
                    )
                    ->get();

                $paymentMethod = DB::table('payment_method')
                    ->get();

                return view('checkout', ['title' => $title, 'orderItems' => $orderItems, 'cartItemsID' => $cartItemsID, 'paymentMethod' => $paymentMethod]);
            } else {
                $status = 400;

                return redirect()->back()->with('error', $status);
            }
        };
    }

    public function saveOrder(Request $request)
    {
        DB::transaction(function () use ($request) {
            $data = $request->data;
            $orderDate = date('Y-m-d');
            $shipmentDate = date('Y-m-d');
            $total = 0;
            $shipmentFee = $request->shippingCost;
            $grandTotal = 0;

            foreach ($data as $row) {
                $userID = $row['user_id'];
                $total += $row['Price'];
            }

            $grandTotal = $total + $shipmentFee;

            $getMaxOrderID = DB::table('orders')
                ->max('order_id');

            if ($getMaxOrderID == null) {
                $num = '000001';
            } else {
                $num = substr($getMaxOrderID, -6);
                $num = intval($num + 1);
            }

            $orderID = 'PO' . '-' . date('Ymdhi') . $userID . substr('000000' . $num, strlen($num));

            DB::table('orders')
                ->insert([
                    'order_id' => $orderID,
                    'user_id' => $userID,
                    'order_date' => $orderDate,
                    'shipment_date' => $shipmentDate,
                    'total' => $total,
                    'shipment_fee' => $shipmentFee,
                    'grand_total' => $grandTotal,
                    'payment_method_id' => 1
                ]);

            foreach ($data as $row) {
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
