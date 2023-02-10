<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    public function usersList()
    {
        $title = 'Users List';
        $users = User::where('role_id', 2)->paginate(30);

        return view('admin.user.list', ['title' => $title, 'users' => $users]);
    }
}
