<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function usersList()
    {
        $title = 'Users List';
        $users = User::where('role_id', 2)->paginate(30);

        return view('admin.user.list', ['title' => $title, 'users' => $users]);
    }

    public function getUserAddress()
    {
        $data = Auth::user();

        return response()->json($data);
    }
}
