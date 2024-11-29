<?php

namespace App\Http\Controllers\Api;

use App\Services\Api\Auth\Service;

class AuthController extends ApiController
{

    public Service $authService;

    public function __construct(Service $authService)
    {
        $this->authService = $authService;
    }

    /**
     * @OA\Get(
     *     path="/token",
     *     summary="Отримати новий токен",
     *     description="Метод повертає токен, який необхідний для реєстрації нового користувача. Токен дійсний 40 хвилин і може бути використаний лише для одного запиту. Для наступної реєстрації потрібно отримати новий токен.",
     *     operationId="getToken",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="JSON об'єкт, який містить токен",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="token", type="string", example="eyJpdiI6Im9mV1NTMlFZQTlJeWlLQ3liVks1MGc9PSIsInZhbHVlIjoiRTJBbUR4dHp1dWJ3ekQ4bG85WVZya3ZpRGlMQ0g5ZHk4M05UNUY4Rmd3eFM3czc2UDRBR0E4SDR5WXlVTG5DUDdSRTJTMU1KQ2lUQmVZYXZZOHJJUVE9PSIsIm1hYyI6ImE5YmNiODljZjMzMTdmMDc4NjEwN2RjZTVkNzBmMWI0ZDQyN2YzODI5YjQxMzE4MWY0MmY0ZTQ1OGY4NTkyNWQifQ==")
     *         )
     *     )
     * )
     */
    public function getToken()
    {
        $customClaims = $this->authService->buildCustomClaims();

        $token = $this->authService->generateJwtToken($customClaims);

        return [
            'token' => $token,
        ];
    }



}
