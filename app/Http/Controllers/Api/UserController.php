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
     *                 type="object",
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="data", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
     *                         @OA\Property(property="name", type="string", example="John Doe"),
     *                         @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                         @OA\Property(property="phone", type="string", example="+380123456789"),
     *                         @OA\Property(property="profile_image", type="string", example="avatars/example.jpg")
     *                     )
     *                 ),
     *                 @OA\Property(property="first_page_url", type="string", example="http://example.com/api/users?page=1"),
     *                 @OA\Property(property="from", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *                 @OA\Property(property="last_page_url", type="string", example="http://example.com/api/users?page=5"),
     *                 @OA\Property(
     *                     property="links",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="url", type="string", example="http://example.com/api/users?page=2"),
     *                         @OA\Property(property="label", type="string", example="&laquo; Previous"),
     *                         @OA\Property(property="active", type="boolean", example=false)
     *                     )
     *                 ),
     *                 @OA\Property(property="next_page_url", type="string", example="http://example.com/api/users?page=2"),
     *                 @OA\Property(property="path", type="string", example="http://example.com/api/users"),
     *                 @OA\Property(property="per_page", type="integer", example=6),
     *                 @OA\Property(property="prev_page_url", type="string", example=null),
     *                 @OA\Property(property="to", type="integer", example=6),
     *                 @OA\Property(property="total", type="integer", example=30)
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
     *             required={"uuid", "name", "email", "phone"},
     *             @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *             @OA\Property(property="phone", type="string", example="0123456789"),
     *             @OA\Property(property="profile_image", type="string", format="binary", description="Зображення профілю у форматі Base64")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Користувач успішно створений",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="token", type="string", example="3|3EwdR9p5rMjrGAM5zKpgoXDDgqMqLCyqwCDrYYxgab481ddf"),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=48),
     *                     @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                     @OA\Property(property="phone", type="string", example="0123456789"),
     *                     @OA\Property(property="profile_image", type="string", example="avatars/example.jpg"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-11-22T00:52:47.000000Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-11-22T00:52:47.000000Z")
     *                 )
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
