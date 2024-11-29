<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\Position\Service;

class PositionController extends Controller
{

    protected Service $positionService;


    public function __construct(Service $positionService)
    {
        $this->positionService = $positionService;
    }
    /**
     * @OA\Get(
     *     path="/positions",
     *     summary="Отримати список доступних посад користувачів",
     *     description="Метод повертає список всіх доступних посад користувачів.",
     *     operationId="getPositions",
     *     tags={"Positions"},
     *     @OA\Response(
     *         response=200,
     *         description="Список доступних посад користувачів",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="positions",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Lawyer")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Посади не знайдено",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Positions not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Невірний запит",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Positions not found")
     *         )
     *     )
     * )
     */
    public function __invoke()
    {
        $positions =  $this->positionService->getAllPositions();

        return [
            'positions' => $positions,
        ];
    }
}
