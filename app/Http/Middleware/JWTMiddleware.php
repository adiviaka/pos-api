<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Exception;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\ValidAt;
use Lcobucci\Clock\SystemClock;
use App\Models\User;

class JWTMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized: No token provided'], 401);
        }

        try {
            // Set up JWT configuration
            $config = Configuration::forSymmetricSigner(
                new \Lcobucci\JWT\Signer\Hmac\Sha256(),
                \Lcobucci\JWT\Signer\Key\InMemory::plainText(env('JWT_SECRET'))
            );

            // Parse the token
            $parsedToken = $config->parser()->parse($token);

            // Validate the token
            $constraints = [
                new IssuedBy(env('APP_URL')), // Ensure the token was issued by your app
                new ValidAt(SystemClock::fromUTC()), // Check if the token is valid at the current time
            ];

            if (!$config->validator()->validate($parsedToken, ...$constraints)) {
                return response()->json(['message' => 'Unauthorized: Invalid token'], 401);
            }

            // Extract the user ID from the token and find the user
            $userId = $parsedToken->claims()->get('sub');
            $user = User::findOrFail($userId);

            // Attach user to the request
            $request->setUserResolver(function () use ($user) {
                return $user;
            });
        } catch (Exception $e) {
            return response()->json(['message' => 'Unauthorized: Token error', 'error' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
