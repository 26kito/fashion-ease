<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VoucherController extends Controller
{
    public function index()
    {
        $title = 'Voucher List';
        $headingNavbar = 'Voucher List';
        $data = DB::table('vouchers')->orderBy('id', 'asc')->paginate(30);

        return view('admin.voucher.list', ['title' => $title, 'vouchers' => $data, 'headingNavbar' => $headingNavbar]);
    }

    public function insertView()
    {
        $title = 'Create Voucher';

        return view('admin.voucher.insert', ['title' => $title]);
    }

    public function insertAction(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vouchercode' => 'unique:vouchers,code'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $title = $request->vouchername;
        $code = $request->vouchercode;
        $quota = $request->quota;
        $startdate = date("Y-m-d H:i:s", strtotime($request->startdate));
        $enddate = date("Y-m-d H:i:s", strtotime($request->enddate));
        $disctype = $request->disctype;
        $discval = $request->discval;
        $minprice = $request->minimumprice;
        $maxdisc = $request->maxdiscount;
        $createddate = date('Y-m-d H:i:s');

        DB::table('vouchers')->insert([
            'title' => $title,
            'code' => $code,
            'quota' => $quota,
            'is_active' => 1,
            'start_date' => $startdate,
            'end_date' => $enddate,
            'minimum_price' => $minprice,
            'discount_type' => $disctype,
            'discount_value' => $discval,
            'max_discount_amount' => $maxdisc,
            'created_at' => $createddate
        ]);

        return back()->with('toastr', 'Berhasil');
    }

    public function getVoucher(Request $request)
    {
        $keyword = $request->keyword;
        // $totalPriceCart = request()->totalPriceCart;

        $data = DB::table('vouchers')->where('code', $keyword)->first();

        if ($data) {
            // $voucher = $this->checkVoucher($data->code);
            $currentDate = now()->format('Y-m-d H:i:s');
            $totalPriceCart = isset($_COOKIE['totalPriceCart']) ? $_COOKIE['totalPriceCart'] : 0;

            if (
                $data->is_active == 1 && ($currentDate >= $data->start_date && $currentDate <= $data->end_date) &&
                ($totalPriceCart >= $data->minimum_price) && $data->quota != 0
            ) {
                $data->available = true;
            } else {
                $data->available = false;
            }

            return response()->json(['status' => 'ok', 'data' => $data]);
        } else {
            $status = 'error';
            $message = 'Voucher tidak ditemukan';

            return response()->json(['status' => $status, 'message' => $message]);
        }
    }

    protected function checkVoucher(Request $request)
    {
        $voucherCode = $request->voucherCode;
        $totalPriceCart = $request->totalPriceCart;
        $currentDate = now()->format('Y-m-d H:i:s');
        
        $voucher = DB::table('vouchers')->where('code', $voucherCode)->first();

        if (
            $voucher->is_active == 1 && ($currentDate >= $voucher->start_date && $currentDate <= $voucher->end_date) &&
            ($totalPriceCart >= $voucher->minimum_price) && $voucher->quota != 0
        ) {
            $voucher->available = true;
        } else {
            $voucher->available = false;
        }

        return $voucher;
    }

    protected function isVoucherAvailable($voucher, $currentDate, $totalPriceCart)
    {
        return $voucher->is_active == 1 &&
            $currentDate >= $voucher->start_date &&
            $currentDate <= $voucher->end_date &&
            $totalPriceCart >= $voucher->minimum_price;
    }

    public function applyVoucher(Request $request)
    {
        // Assuming $vouchers is your array of voucher data
        // $selectedVoucher = collect($vouchers["rows"])->firstWhere("id", $voucherId);
        $totalPriceCart =  $request->totalPriceCart;
        $voucherID = $request->voucherID;
        $selectedVoucher = DB::table('vouchers')->where('id', $voucherID)->first();

        if ($selectedVoucher) {
            $discountValue = $this->calculateDiscount($totalPriceCart, $selectedVoucher);
            $totalPrice = $totalPriceCart - $discountValue;

            return response()->json([
                "total_price" => $totalPriceCart,
                "discount_applied_format" => rupiah($discountValue),
                "discount_applied" => $discountValue,
                "discounted_price" => $totalPrice,
            ]);
        } else {
            return response()->json(["error" => "No voucher found with ID {$voucherID}"], 404);
        }
    }

    protected function calculateDiscount($totalPriceCart, $voucher)
    {
        $currentDate = Carbon::now()->format('Y-m-d H:i:s');

        // Check if the voucher is active
        if ($voucher->is_active == 1 && ($currentDate >= $voucher->start_date && $currentDate <= $voucher->end_date)) {
            // Check if the total price meets the minimum requirement
            if ($totalPriceCart >= $voucher->minimum_price) {
                // Calculate discount based on voucher type
                if ($voucher->discount_type == "FIXED") {
                    $discountAmount = min($voucher->discount_value, $totalPriceCart);
                }

                if ($voucher->discount_type == "PERCENTAGE") {
                    $discountAmount = min(($voucher->discount_value / 100) * $totalPriceCart, $voucher->max_discount_amount ?? PHP_FLOAT_MAX);
                }

                return $discountAmount;
            }
        }

        return 0;
    }
}
