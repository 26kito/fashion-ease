<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index($productName, $productCode, $productID)
    {
        $data = DB::table('products')
            // ->join('detail_products', 'products.product_id', 'detail_products.product_id')
            // ->where('detail_products.product_id', $productID)
            ->leftJoin('detail_products', 'products.product_id', 'detail_products.product_id')
            ->where('products.product_id', $productID)
            ->select('products.product_id', 'products.name', 'products.image', 'products.description', DB::raw('IFNULL(MIN(detail_products.price), products.price) AS price'))
            ->orderBy('detail_products.price', 'desc')
            ->first();

        $products = collect($data);
        $title = ucwords($productName) . " | ";

        return view('products')->with(['title' => $title, 'products' => $products]);
    }
}
