<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\User\ShowRequest;
use App\Http\Requests\Api\User\StoreRequest;
use App\Services\Api\User\Service;
use Illuminate\Http\Request;


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
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="page",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="total_pages",
     *                 type="integer",
     *                 example=10
     *             ),
     *             @OA\Property(
     *                 property="total_users",
     *                 type="integer",
     *                 example=55
     *             ),
     *             @OA\Property(
     *                 property="count",
     *                 type="integer",
     *                 example=6
     *             ),
     *             @OA\Property(
     *                 property="links",
     *                 type="object",
     *                 @OA\Property(property="next_url", type="string", example="http://rest-api.test/api/v1/users?page=2"),
     *                 @OA\Property(property="prev_url", type="string", example="null")
     *             ),
     *             @OA\Property(
     *                 property="users",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=75),
     *                     @OA\Property(property="name", type="string", example="User Name"),
     *                     @OA\Property(property="email", type="string", example="post@gmail.com"),
     *                     @OA\Property(property="phone", type="string", example="+380734888999"),
     *                     @OA\Property(property="position", type="string", nullable=true, example="Content manager"),
     *                     @OA\Property(property="position_id", type="string", example="2"),
     *                     @OA\Property(property="registration_timestamp", type="integer", example=1732819981),
     *                     @OA\Property(property="photo", type="string", example="http://rest-api.test/storage/avatars/1732819981_6748bc0d1435e.jpg")
     *                 )
     *             ),
     *             @OA\Property(property="first_page_url", type="string", example="http://rest-api.test/api/v1/users?page=1"),
     *             @OA\Property(property="from", type="integer", example=1),
     *             @OA\Property(property="last_page", type="integer", example=10),
     *             @OA\Property(property="last_page_url", type="string", example="http://rest-api.test/api/v1/users?page=10"),
     *             @OA\Property(property="next_page_url", type="string", example="http://rest-api.test/api/v1/users?page=2"),
     *             @OA\Property(property="path", type="string", example="http://rest-api.test/api/v1/users"),
     *             @OA\Property(property="per_page", type="integer", example=6),
     *             @OA\Property(property="prev_page_url", type="string", example="null"),
     *             @OA\Property(property="to", type="integer", example=6),
     *             @OA\Property(property="total", type="integer", example=55)
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
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="john.doe@example.com"),
     *                 @OA\Property(property="phone", type="string", example="+380957398462"),
     *                 @OA\Property(property="position", type="string", example="Security"),
     *                 @OA\Property(property="position_id", type="integer", example=2),
     *                 @OA\Property(property="photo", type="string", example="http://rest-api.test/storage/avatars/1732818938_6748b7faaff78.jpg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Невірний запит або не коректні дані",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(
     *                 property="fails",
     *                 type="object",
     *                 @OA\Property(property="id", type="array", items=@OA\Items(type="string"), example={"The id must be a valid integer."})
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Користувача не знайдено",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="User not found")
     *         )
     *     )
     * )
     */
    public function show(ShowRequest $request, $id)
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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "email", "phone", "position_id", "photo"},
     *                 @OA\Property(property="name", type="string", description="Ім'я користувача, повинно бути від 2 до 60 символів", example="John Doe"),
     *                 @OA\Property(property="email", type="string", description="Електронна пошта користувача, має бути валідною згідно з RFC2822", example="john.doe@example.com"),
     *                 @OA\Property(property="phone", type="string", description="Номер телефону користувача, має починатись з коду України +380", example="+380123456789"),
     *                 @OA\Property(property="position_id", type="integer", description="Ідентифікатор посади користувача. Список можна отримати через GET api/v1/positions", example=1),
     *                 @OA\Property(property="photo", type="string", format="binary", description="Фото користувача у форматі JPEG/JPG, мінімальний розмір 70x70px, розмір не більше 5MB")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Користувач успішно створений",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="user_id", type="integer", example=23),
     *             @OA\Property(property="message", type="string", example="New user successfully registered")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Токен закінчився",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="The token expired.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Помилка валідації",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation failed"),
     *             @OA\Property(property="fails", type="object", additionalProperties={
     *                 @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name must be at least 2 characters.")),
     *                 @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email must be a valid email address.")),
     *                 @OA\Property(property="phone", type="array", @OA\Items(type="string", example="The phone field is required.")),
     *                 @OA\Property(property="position_id", type="array", @OA\Items(type="string", example="The position id must be an integer.")),
     *                 @OA\Property(property="photo", type="array", @OA\Items(type="string", example="The photo may not be greater than 5 Mbytes."))
     *             })
     *         )
     *     )
     * )
     */
    public function store(StoreRequest $request)
    {
        return $this->userService->createUser($request);
    }
}
