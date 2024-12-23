<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Configuration;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        try {
            $config = Configuration::forSymmetricSigner(
                new Sha256(),
                Key\InMemory::plainText(env('JWT_SECRET'))
            );

            $now = new \DateTimeImmutable();
            $token = $config->builder()
                ->issuedBy(env('APP_URL')) // Issuer
                ->permittedFor(env('APP_URL')) // Audience
                ->issuedAt($now) // Issued at
                ->expiresAt($now->modify('+1 hour')) // Expire in 1 hour
                ->relatedTo($user->id) // Set the "sub" (subject) claim
                ->getToken($config->signer(), $config->signingKey()); // Sign the token

            return response()->json([
                'token' => $token->toString(), // Convert token to string
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token generation failed', 'error' => $e->getMessage()], 500);
        }
    }
}
