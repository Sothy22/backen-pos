<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Trait\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponseTrait;

    // Register method
    public function register (Request $req) {
        $validate = $req->validate([
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'string|in:admin,user',
        ]);

        // created user
        $user = User::create([
            'name' => $validate['name'],
            'email' => $validate['email'],
            'password' => Hash::make($validate['password']),
            'role' => $validate['role'],
        ]);

        return $this->apiResponseSuccess($user, 'Register successfully', 201);
    }

    // login
    public function login (Request $req) {
        $validate = $req->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // 1. find user in db
        $user = User::where('email', $validate['email'])->first();
        if (!$user) {
            return $this->apiNotFound('User Not Found');
        }

        // 2. check password
        // Hash::check($validate['password'], $user->password); // return true or false
        if (!Hash::check($validate['password'], $user->password)) {
            return $this->apiResponseError('Incorrect Password.', 401);
        }

        // 3. Generate Token
        $resultToken = $user->createToken('authToken');
        $token = $resultToken->plainTextToken;

        // 4. Cookie
        $cookie = Cookie('authToken', $token, 60*24*7, "/", null, true, false, true, 'Strict');

        return $this->apiResponseSuccess([
            'token' => $token,
            'user' => $user,
            'message' => 'User Login Successfully'
        ], 200)->withCookie($cookie);
    }

    // log out method

    public function logout(Request $req) {
        $user = $req->user()->currentAccessToken()->delete();

        $cookie = Cookie::forget('authToken'); // delete token

        return $this->apiResponseSuccess($user, 'User is log out suceessfully', 200)->withCookie($cookie);
    }

    // me method (Get current user)
    public function me (Request $req) {
        $user = $req->user();
        return $this->apiResponseSuccess($user, 'Get user successsfully', 200);
    }
}
