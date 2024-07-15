<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $title = 'Category List - ';

        // return view('category', ['title' => $title]);
        return view('category')->with(['title' => $title]);
    }
}
