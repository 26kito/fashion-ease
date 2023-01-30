<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function admin()
    {
        $data['title'] = "Dashboard";

        return view('admin.dashboard', $data);
    }

    public function home()
    {
        $data['title'] = "Dashboard";

        $data['products'] = Product::all();

        $data['LatestProducts'] = DB::table('products')
            ->join('detail_products', 'products.id', 'detail_products.dp_id')
            ->select('products.id', 'products.name', 'products.description', 'products.price', 'products.created_at')
            ->groupBy('products.id')
            ->orderBy('products.created_at', 'DESC')
            ->limit(3)
            ->get();

        return view('index', $data);
    }
}
