<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductsController extends Controller
{
    public function index($productID)
    {
        $title = 'Products';
        $products = Product::with('detailsProduct')->where('product_id', $productID)->first();
        $relatedProducts = Product::where('category_id', $products->category_id)->get();
        $defaultSize = ['S', 'M', 'L', 'XL'];

        return view('products', [
            'title' => $title, 
            'products' => $products, 
            'relatedProducts' => $relatedProducts, 
            'defaultSize' => $defaultSize
        ]);
    }
}
