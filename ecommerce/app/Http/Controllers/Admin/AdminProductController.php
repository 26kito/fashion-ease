<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\Product;

class AdminProductController extends Controller
{
    // Insert Data
    public function insert() {
        $categories = Category::get();
        $data['title'] = 'Insert Data';
        return view('admin.product.insert', compact('categories'), $data);
    }

    public function insertAction(Request $request) {
        $validated = $request->validate([
            // 'id' => 'required|integer',
            'category_id' => 'required|integer',
            'code' => 'required|integer|unique:products',
            'name' => 'required|string',
            'stock' => 'required|integer',
            'varian' => 'required',
            'description' => 'required',
            'image' => 'image|file|max:1024'
        ]);

        // Cara 1. Ditampung dulu di var
        /* $code = $request->input('code');
        $name = $request->input('name');
        $stock = $request->input('stock');
        $varian = $request->input('varian');
        $category = $request->input('category'); */

        // Cara 2. Langsung
        $product = new Product;
        $product->id = $request->input('id');
        $product->category_id = $request->input('category_id');
        $product->code = $request->input('code');
        $product->name = $request->input('name');
        $product->stock = $request->input('stock');
        $product->varian = $request->input('varian');
        $product->description = $request->input('description');
        $product->image = $request->file('productsImage')->store('products-images');
        $product->save();
        
        // Product::create($validated);

        return back()->with('message', 'Data berhasil di tambahkan');
    }
    // End of Insert Data

    // Update Data
    public function edit(Request $request, $id) {
        $data['title'] = 'Update Data';
        $product = Product::find($id);
        $categories = Category::get();
        return view('admin.product.update', compact('categories', 'product'), $data);
    }

    public function update(Request $request, $id) {
        $product= Product::find($id); // ini untuk get data sesuai ID
        // Validation
        $validated = $request->validate([
            'id' => 'required|integer',
            'category_id' => 'required',
            'code' => 'required|integer|'.Rule::unique('products')->ignore($product->code, 'code'),
            'name' => 'required|string',
            'stock' => 'required|integer',
            'varian' => 'required',
            'description' => 'required',
            'image' => 'required'
        ]);

        $product->id = $request->input('id');
        $product->category_id = $request->input('category_id');
        $product->code = $request->input('code');
        $product->name = $request->input('name');
        $product->stock = $request->input('stock');
        $product->varian = $request->input('varian');
        $product->description = $request->input('description');
        $product->image = $request->input('image');
        $product->save();

        return back()->with('message', 'Data berhasil di ubah');
    }
    // End of Update Data

    // Delete Data
    public function delete($id) {
        $data = Product::find($id);
        $data->delete();

        return back()->with('message', 'Data berhasil di hapus');
    }
    // End of Delete Data
}
