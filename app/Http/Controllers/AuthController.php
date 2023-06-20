<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $attrs = $request->validate([
            'name' => 'required|string',
            'gender' => 'required|string',
            'index_no' => 'required',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $attrs['name'],
            'email' => $attrs['email'],
            'password' => Hash::make($attrs['password']),
            'gender' => $attrs['gender'],
            'index_no' => $attrs['index_no'],
        ]);

        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ], 201);
    }
    public function login(Request $request){
        $attrs = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:6'
        ]);

        if(!Auth::attempt($attrs)){
            return response([
                'message' => 'Invalid credentials'
            ], 403);
        }


        $user = Auth::user();

        return response([
            'user' => $user,
            'token' => $user->createToken('secret')->plainTextToken
        ], 200);
    }
}
