<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserDetailsResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;

class AuthController extends Controller
{
    public function login(Request $request) {
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $credentials = $request->only("email", "password");
        if (!Auth::attempt($credentials)) {
            throw new UnauthorizedException("", "Invalid credentials.");
        }

        $user = Auth::user();
        $user->token = $user->createToken($user->email)->plainTextToken;

        $userResource = new UserDetailsResource($user);

        return response()->json([
            "user" => $userResource
        ]);
    }

    public function register(RegisterUserRequest $request) {
        $validated = $request->validated();

        User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => Hash::make($validated["password"]),
        ]);

        return response()->json([
            "message" => "Registration successful."
        ]);
    }

    public function logout(Request $request) {

    }
}
