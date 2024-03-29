<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
   public function login(Request $request)
   {
    $request -> validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();
    
    if(!$user || ! Hash::check($request->password, $user->password)){
        throw ValidationException::withMessages([
            'account' => 'thats wrong dude'
        ]);
    }
    return $user->createToken('user login')->plainTextToken;
   }

   public function logout(Request $request)
   {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'you have been logged out'
        ]);
   }

   public function me()
   {
        $user = Auth::user();
        return response()->json($user);
   }

   public function register(Request $request){
        $user = User::create(Request(['username', 'firstname', 'lastname', 'balance', 'email', 'password']));
        auth()->login($user);
        return $user->createToken('user login')->plainTextToken;
   }

}
