<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
        // Remove role_id from public registration
    ]);

    $validated['password'] = Hash::make($validated['password']);
    
    // Assign a default role for new registrations
    $validated['role_id'] = Role::where('name', 'student')->first()->id; // or whatever default role

    $user = User::create($validated);

    // Optionally auto-login the user
    $token = $user->createToken('auth-token')->plainTextToken;

    return response()->json([
        'message' => 'Registration successful',
        'user' => $user->load('role'),
        'token' => $token
    ], 201);
}

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->is_active) {
            throw ValidationException::withMessages([
                'email' => ['This account is inactive.'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->load('role'),
            'abilities' => $user->abilities(),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->load('role'),
            'abilities' => $request->user()->abilities(),
        ]);
    }
}
