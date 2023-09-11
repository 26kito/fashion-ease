<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function index()
    {
        $title = 'Voucher List';
        $headingNavbar = 'Voucher List';
        $data = DB::table('vouchers')->paginate(30);

        return view('admin.voucher.list', ['title' => $title, 'vouchers' => $data, 'headingNavbar' => $headingNavbar]);
    }

    public function insertView()
    {
        $title = 'Create Voucher';

        return view('admin.voucher.insert', ['title' => $title]);
    }

    public function insertAction(Request $request)
    {
        $title = $request->vouchername;
        $code = $request->vouchercode;
        $quota = $request->quota;
        $startdate = date("Y-m-d H:i:s", strtotime($request->startdate));
        $enddate = date("Y-m-d H:i:s", strtotime($request->enddate));
        $disctype = $request->disctype;
        $discval = $request->discval;
        $maxdisc = $request->maxdiscount;
        $createddate = date('Y-m-d H:i:s');

        DB::table('vouchers')->insert([
            'title' => $title,
            'code' => $code,
            'quota' => $quota,
            'is_active' => 1,
            'start_date' => $startdate,
            'end_date' => $enddate,
            'discount_type' => $disctype,
            'discount_value' => $discval,
            'max_discount_amount' => $maxdisc,
            'created_at' => $createddate
        ]);

        return back()->with('toastr', 'Berhasil');
    }
}
