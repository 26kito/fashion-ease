<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $title = 'Daftar Transaksi | ';

        return view('order')->with(['title' => $title]);
    }
}
