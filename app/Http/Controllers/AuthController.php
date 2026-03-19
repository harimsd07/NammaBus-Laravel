<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    public function listUsers(){
        $users = User::all();

        return response()->json([
            'status' => 200,
            'message' => 'User Details Fetched',
            'count' => $users->count(),
            'data' => $users
        ]);
    }
    public function login(Request $request) {
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        return response()->json([
            'token' => $user->createToken('auth_token')->plainTextToken,
            'role' => $user->role, // Ensure your DB has a 'role' column
            'user' => $user
        ]);
    }
    return response()->json(['message' => 'Unauthorized'], 401);
}

public function register(Request $request) {
    // Logic: Validation including name, email, and specific role constraints
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:8',
        'role' => 'required|string|in:student,driver',
        'driver_key' => 'nullable|string', // Logic: Added to catch the driver verification code
    ]);

    // Logic: Tackling unauthorized driver registration
    if ($validated['role'] === 'driver') {
        $secretKey = "ADMIN_SECRET_2026"; // Logic: This must match your Flutter app key
        if ($request->driver_key !== $secretKey) {
            return response()->json(['error' => 'Invalid Driver Verification Code'], 403);
        }
    }

    $user = \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => $validated['role'],
    ]);

    return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
}


public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();

            // Logic: Create or update the user in your Railway MySQL database
            $user = User::updateOrCreate([
                'email' => $socialUser->getEmail(),
            ], [
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'password' => Hash::make(Str::random(24)),
                'role' => 'student',
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            /*
            |--------------------------------------------------------------------------
            | Final Redirect Logic [Added to send the token back to the Flutter app]
            |--------------------------------------------------------------------------
            | Instead of returning JSON, we redirect to a custom deep link that your
            | Flutter app will recognize to complete the login process.
            */
            $query = http_build_query([
                'token' => $token,
                'name' => $user->name,
                'role' => $user->role,
            ]);

            return redirect("bustrack://login-callback?" . $query);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Authentication failed'], 401);
        }
    }

}
