<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        // régi tokenek törlése
        $user->tokens()->delete();

        // új token generálás
        $token = $user->createToken('LoginToken')->plainTextToken;

        return response()->json([
            'token' => $token
        ], 200);
    }

    public function index(Request $request)
    {
        $users = User::all();
        return response()->json([
            'users' => $users,
        ], 200);
    }
}
