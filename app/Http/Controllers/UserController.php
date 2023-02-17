<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $user = User::where('name', $request->name)->first();
        return ($user);
    }
    public function index_auth()
    {
        $users = User::all();
        return ($users[0]);
    }

    public function register(Request $request)
    {

        $user =  User::create([
            'name' => request('name'),
            'role' => 'client',
            'email' => null,
            'phone' => request('phone'),
            'password' => Hash::make(request('password')),
            'api_token' => Str::random(120),
        ]);
        return (["api_token" => $user['api_token'], "success" => true]);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $user->api_token = Str::random(80);
            $user->save();
            return response()->json([
                'success' => true,
                'api_token' => $user->api_token,
                'message' => 'User logged in successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password'
            ]);
        }
    }

    public function checkme(Request $request)
    {
        $api_token = $request->header('Authorization');
        $user = User::where('api_token', $api_token)->first();

        if ($api_token != null && $user) {
            return response()->json([
                'user_auth' => true,
            ]);
        } else {
            return response()->json([
                'user_auth' => false,
            ]);
        }
    }
    //auth check is checkme using request data instead of request header
    public function auth_check(Request $request)
    {
        $api_token = $request->api_token;
        $user = User::where('api_token', $api_token)->first();
        if ($api_token != null && $user) {
            return response()->json([
                'user_auth' => true,
            ]);
        } else {
            return response()->json([
                'user_auth' => false,
            ]);
        }
    }
    public function logout(Request $request)
    {
        $api_token = $request->header('Authorization');
        $user = User::where('api_token', $api_token)->first();
        Auth::logout($user);
        if ($user) {
            $user->api_token = null;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ]);
        }
    }
}
