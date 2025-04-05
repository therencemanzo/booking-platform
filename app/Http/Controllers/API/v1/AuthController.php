<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    
    public function login(Request $request)
    {

        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (auth()->attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Login successful',
                'user' => auth()->user(),
                'token' => auth()->user()->createToken('API Token')->plainTextToken,
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
