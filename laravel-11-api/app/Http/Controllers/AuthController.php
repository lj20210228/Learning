<?php

namespace App\Http\Controllers;

use App\Models\User;
//use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        /* $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confired',
        ]);*/
        $validated = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;

            //return 
            return response()->json([
                'access_token' => $token,
                'user' => $user,

            ], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
    public function login(Request $request)
    {
        $validated = Validator::make($request->all(), [

            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
        ]);
        if ($validated->fails()) {
            return response()->json($validated->errors(), 403);
        }
        $credentials = ['email' => $request->email, 'password' => $request->password];
        try {
            if (!auth()->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 403);
            }
            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'access_token' => $token,
                'user' => $user,

            ], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception->getMessage()], 403);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->currentAccesstoken()->delete();
        return response()->json([
            'message' => 'user has been loged out successfully'

        ], 200);
    }
}
