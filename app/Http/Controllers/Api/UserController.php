<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\User\StoreRequest;
use App\Services\Api\User\Service;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="API Documentation",
 *     description="Документація для API управління користувачами.",
 * )
 *
 * @OA\Server(
 *     url="/api",
 *     description="Основний сервер API"
 * )
 */
class UserController extends ApiController
{
    protected Service $userService;

    public function __construct(Service $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Отримати список користувачів",
     *     description="Повертає список користувачів з пагінацією.",
     *     operationId="getUsers",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Номер сторінки для пагінації",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список користувачів",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                     @OA\Property(property="profile_image", type="string", example="images/users/12345.jpg")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        return $this->userService->getAllUsers();
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Отримати інформацію про користувача",
     *     description="Повертає деталі про конкретного користувача за його ID.",
     *     operationId="getUserById",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID користувача",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Дані користувача",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="profile_image", type="string", example="images/users/12345.jpg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Користувача не знайдено",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="code", type="integer", example=404),
     *                 @OA\Property(property="message", type="string", example="User not found")
     *             )
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        return $this->userService->getUserById($id);
    }

    /**
     * @OA\Post(
     *     path="/users",
     *     summary="Додати нового користувача",
     *     description="Створює нового користувача з необов'язковим зображенням.",
     *     operationId="createUser",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="profile_image", type="string", format="binary", example="base64_encoded_image")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Користувач успішно створений",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="profile_image", type="string", example="images/users/12345.jpg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Помилка валідації",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="error", type="object",
     *                 @OA\Property(property="code", type="integer", example=422),
     *                 @OA\Property(property="message", type="string", example="Validation error")
     *             )
     *         )
     *     )
     * )
     */
    public function store(StoreRequest $request)
    {
        return $this->userService->createUser($request);
    }
}
