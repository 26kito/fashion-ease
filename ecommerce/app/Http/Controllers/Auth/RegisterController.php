<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'firstname' => 'required|max:20',
            'lastname' => 'max:20',
            'gender' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8'
        ]);

        if ($validate->passes()) {
            $firstname = $request->firstname;
            $lastname = $request->lastname;
            $gender = $request->gender;
            $email = $request->email;
            $password = Hash::make($request->password);

            User::insert([
                'first_name' => $firstname,
                'last_name' => $lastname,
                'gender' => $gender,
                'email' => $email,
                'password' => $password,
                'role_id' => 2
            ]);

            return redirect()->route('login');
        } else {
            return redirect()->back()->withInput()->withErrors($validate);
        }
    }
}
