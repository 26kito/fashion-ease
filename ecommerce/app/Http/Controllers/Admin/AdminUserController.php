<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    // Insert Data
    public function insert() {
        $users = User::get();
        $data['title'] = 'Insert Data';
        return view('admin.user.insert', compact('users'), $data);
    }

    public function insertAction(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|unique:users|email',
            'password' => 'required'
        ]);
        
        $user = new User;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->level = $request->input('level');
        // $user->password = $request->input('password');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('message', 'Data berhasil di tambahkan');
    }
    // End of Insert Data

    // Update Data
    public function edit(Request $request, $id) {
        $data['title'] = 'Update Data';
        $user = User::find($id);
        return view('admin.user.update', compact('user'), $data);
    }

    public function update(Request $request, $id) {
        $user= User::find($id); // ini untuk get data sesuai ID
        // Validation
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|'.Rule::unique('users')->ignore($user->email, 'email'),
        ]);

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        return back()->with('message', 'Data berhasil di ubah');
    }
    // End of Update Data

    // Delete Data
    public function delete($id) {
        $data = User::find($id);
        $data->delete();

        return back()->with('message', 'Data berhasil di hapus');
    }
    // End of Delete Data
}
