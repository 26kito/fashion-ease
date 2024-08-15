<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    public function paymentNotificationURL(Request $request)
    {
        $signature = $request->signature_key;
        $serverKey = env('MIDTRANS_SERVER_KEY');

        // append string: order_id+status_code+gross_amount+serverkey
        $string = $request->order_id . $request->status_code . $request->gross_amount . $serverKey;
        $signatureKey = hash('sha512', $string);

        if ($signature !== $signatureKey) {
            return response()->json([
                "success" => false,
                "message" => "Invalid signature key",
            ]);
        }

        $invoice = DB::table('invoice')->insert([
            "transaction_time" => $request->transaction_time,
            "transaction_status" => $request->transaction_status,
            "transaction_id" => $request->transaction_id,
            "status_message" => $request->status_message,
            "status_code" => $request->status_code,
            "payment_type" => $request->payment_type,
            "fraud_status" => $request->fraud_status,
            "order_id" => $request->order_id,
            "merchant_id" => $request->merchant_id,
            "gross_amount" => $request->gross_amount,
            "raw" => json_encode($request->all()),
        ]);

        if ($request->transaction_status === 'settlement') {
            DB::table('orders')->where('order_id', $request->order_id)->where('status_order_id', 'SO007')->update(['status_order_id' => 'SO001']);
        }

        return response()->json(['status' => 'Success', 'message' => 'Pesanan berhasil dibuat', 'data' => $invoice], 200);
    }
}
