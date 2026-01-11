<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function issueToken(Request $request)
    {
        // Simple mock authentication for demo purposes
        // In a real app, you'd validate against the database properly
        
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Create or get a test user
        $user = User::firstOrCreate(
            ['email' => $request->email],
            ['password' => Hash::make($request->password), 'name' => 'Test User']
        );

        // Revoke old tokens if any to keep it clean
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('demo-token');

        return response()->json([
            'token' => $token->plainTextToken,
            'message' => 'Token generated successfully!',
            'user' => $user->name
        ]);
    }
}
