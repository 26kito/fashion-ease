<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    public function index($productName, $productCode, $productID)
    {
        // $products = Product::with('detailsProduct')->where('product_id', $productID)->first();
        $data = DB::table('products')
            ->join('detail_products', 'products.product_id', 'detail_products.product_id')
            ->where('detail_products.product_id', $productID)
            ->select('products.product_id', 'products.name', 'products.image', 'products.description', DB::raw('MIN(detail_products.price) AS price'))
            ->orderBy('detail_products.price', 'desc')
            ->first();

        $products = collect($data);
        // $relatedProducts = Product::where('category_id', $products->category_id)->take(8)->get();
        // $defaultSize = ['S', 'M', 'L', 'XL'];
        $productName = DB::table('products')->where('product_id', $productID)->pluck('name')->first();
        $title = ucwords($productName) . " | ";

        return view('products')->with(['title' => $title, 'products' => $products]);
    }
}
