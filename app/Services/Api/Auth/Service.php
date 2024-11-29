<?php
namespace App\Services\Api\Auth;

use Carbon\Carbon;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class Service
{
    public function buildCustomClaims(): array
    {
        return [
            'sub' => uniqid(),
            'type' => 'registration',
            'is_used' => false,
            'iat' => Carbon::now()->timestamp,
            'exp' => Carbon::now()->addMinutes(JWTAuth::factory()->getTTL())->timestamp,
        ];
    }


    public function generateJwtToken(array $customClaims): string
    {
        $payload = JWTAuth::factory()->customClaims($customClaims)->make();

        return JWTAuth::encode($payload)->get();
    }
}
