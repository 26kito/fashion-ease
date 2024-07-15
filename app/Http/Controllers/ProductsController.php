<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductsController extends Controller
{
    public function index($productName, $productCode, $productID)
    {
        $products = Product::with('detailsProduct')->where('product_id', $productID)->where('code', $productCode)->first();
        $relatedProducts = Product::where('category_id', $products->category_id)->take(8)->get();
        $defaultSize = ['S', 'M', 'L', 'XL'];
        $title = ucwords($products->name) . " - ";

        return view('products', [
            'title' => $title,
            'products' => $products,
            'relatedProducts' => $relatedProducts,
            'defaultSize' => $defaultSize
        ]);
    }
}
