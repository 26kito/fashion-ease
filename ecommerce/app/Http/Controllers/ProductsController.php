<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductsController extends Controller
{
    // User
    public function index($id) {
        $data['title'] = 'Products';
        $data['products'] = Product::with('detailsProduct')->where('id', $id)->first();
        $data['relatedProducts'] = Product::where('category_id', $data['products']->category_id)->get();
        $data['defaultSize'] = ['S', 'M', 'L', 'XL'];
        return view('products', $data);
    }

    // Admin
    public function productsList() {
        $data['title'] = 'Products List';
        $data['products'] = Product::paginate(30);
        return view('admin.product.list', $data);
    }
}