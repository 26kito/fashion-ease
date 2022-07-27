<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function admin()
    {
        $data['title'] = "Dashboard";
        return view('admin.dashboard', $data);
    }

    public function home() {
        $data['title'] = "Dashboard";
        $data['products'] = Product::all();
        return view('index', $data);
    }

}
