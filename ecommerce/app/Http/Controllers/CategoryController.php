<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        $data['title'] = 'Category';
        return view('category', $data);
    }
}
