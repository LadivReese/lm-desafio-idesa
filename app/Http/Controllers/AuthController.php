<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:150',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => password_hash($request->input('password'), PASSWORD_BCRYPT),
            'api_token' => Str::random(60),
        ]);

        return response()->json([    
            'id' => $user->id,
            'email' => $user->email,
            'token' => $user->api_token
        ], 201);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        /** @var User|null $user */
        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !password_verify($request->input('password'), $user->password)) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // rotate token
        $user->api_token = Str::random(60);
        $user->save();

        return response()->json([
            'user' => $user,
            'token' => $user->api_token
        ]);
    }
}
