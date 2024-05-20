<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        // Apply the 'auth:api' middleware to every method except the 'login' method
        $this->middleware('auth:api', ['except' => ['login']]);
    }


    // Method to handle user login
    public function login()
    {
        // Retrieve email and password from the request
        $credentials = request(['email', 'password']);
        // Attempt to authenticate and create a token using the provided credentials
        $token = auth()->attempt($credentials);
        // If authentication fails, return a 401 Unauthorized response
        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    // Method to handle user logout
    public function logout()
    {
        // Log the user out (invalidate the token)
        auth()->logout();
        // Return a success message upon logout
        return response()->json(['message' => 'Successfully logged out']);
    }

    // Helper method to format the response with the token details
    private function respondWithToken($token)
    {
        // Return a JSON response containing the token details
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
