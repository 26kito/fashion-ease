<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function searchResult(Request $request)
    {
        // Get param from url
        $keyword = request()->keyword;

        $title = "Result of $keyword";

        // $products = DB::table('products')
        //     ->where('name', 'LIKE', "%$keyword%")
        //     ->get();

        // $productsID = [];
        // foreach ($products as $row) {
        //     array_push($productsID, $row->category_id);
        // }

        // $categories = DB::table('categories')
        //     ->whereIn('id', $productsID)
        //     ->get();

        // if ($products->isEmpty()) {
        //     $message = "Produk yang kamu cari gaada nih:(";
        // } else {
        //     $message = "";
        // }

        return view('search-result', [
            'title' => $title,
            'keyword' => $keyword
        ]);
    }
}
