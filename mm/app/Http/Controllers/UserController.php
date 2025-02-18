<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    //registration
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'fullname' => 'required',
        ]);

        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->fullname = $request->fullname;

        $user->save();

        return response()->json([
            'message' => 'Successfully created user!',
            'user' => $user
        ], 201);
    }
}
