<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $title = "Dashboard";

        return view('admin.dashboard', ['title' => $title]);
    }

    public function home()
    {
        $title = "Dashboard";

        $products = Product::all();

        $latestProducts = DB::table('products')
            ->join('detail_products', 'products.id', 'detail_products.dp_id')
            ->select('products.id', 'products.product_id', 'products.name', 'products.description', 'products.price', 'products.created_at')
            ->groupBy('products.id')
            ->orderBy('products.created_at', 'DESC')
            ->limit(3)
            ->get();

        return view('index', ['title' => $title, 'products' => $products, 'latestProducts' => $latestProducts]);
    }
}
