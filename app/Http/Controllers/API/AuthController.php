<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
        // Method to handle user authentication and token generation
        public function generateToken(Request $request)
        {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            $user = \App\Models\User::where('email', $request->email)->first();
    
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
    
            $token = $user->createToken('my-app-token')->plainTextToken;
    
            return response()->json(['token' => $token], 200);
        }
    
        // Method to handle user logout and token revocation
        public function logout(Request $request)
        {
            // Revoke all tokens...
            $request->user()->tokens()->delete();
    
            // // Revoke the current token
            $request->user()->currentAccessToken()->delete();
    
            return response()->json(['message' => 'You have been successfully logged out.'], 200);
        }
}
