<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirect(){
        return Socialite::driver('google')->redirect();
    }

    public function callbackGoogle(){
        try{
            $google_user = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $google_user->getEmail())->first();

            if(!$user){
                $new_user = User::create([
                    'username' => explode('@', $google_user->getEmail())[0],
                    'fullname' => $google_user->getName(),
                    'email' => $google_user->getEmail(),
                    'password' => "default",
                ]);

                $user = $new_user;
            }

            $token = auth()->login($user);

            return response()->json(
                [
                    'access_token' => $token,
                    'token_type' => 'bearer'
                ]
                );
        }
        catch(\Throwable $th){
            dd('Something went wrong! '. $th->getMessage());
        }
    }

}
