<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Http\Middleware\BaseMiddleware;

class CustomJWTAuthenticate extends BaseMiddleware
{

    public function handle(Request $request, Closure $next)
    {
/*        try {
            //check if there is a token
            $this->checkForToken($request);
        } catch (\Exception) {
            return response()->json(['error' => 'The token expired.'], 401);
        }*/

        try {

            $token = $request->header('Authorization');
            $payload = JWTAuth::setToken($token)->parseToken()->getPayload();


            switch (true) {
                case $payload->get('type') !== 'registration':
                    return response()->json(['success' => false, 'message' => 'Invalid token.'], 401);

                case $payload->get('is_used') === true:
                    return response()->json(['success' => false, 'message' => 'Already used token.'], 401);
            }

            $newPayload = $payload->toArray();
            $newPayload['is_used'] = true;

            $updateToken = JWTAuth::setToken($token)
                ->factory()
                ->customClaims($newPayload)
                ->make();

            $newToken = JWTAuth::encode($updateToken);

            $response = $next($request);

            return $response->withHeaders([
                'Authorization' => 'Bearer ' . $newToken
            ]);
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 401);
        }
    }
}
