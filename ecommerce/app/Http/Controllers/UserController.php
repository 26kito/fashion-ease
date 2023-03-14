<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $data = DB::table('user_addresses')
            ->where('user_id', Auth::id())
            ->select('id', 'address', 'is_default')
            ->orderBy('is_default', 'DESC')
            ->get();

        return response()->json(['data' => $data]);
    }
}
