<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function usersList()
    {
        $data['title'] = 'Users List';
        $data['users'] = User::where('level', 'USER')->paginate(30);
        // $data['users'] = $data['users']->firstItem();

        return view('admin.user.list', $data);
    }
}
