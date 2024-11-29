<?php
namespace App\Services\Api\User;

use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Models\Base\User;

class Service
{

    protected $imageProcessingService;

    public function __construct(ImageProcessingService $imageProcessingService)
    {
        $this->imageProcessingService = $imageProcessingService;
    }


    public function getAllUsers()
    {
        $users = User::
            with('positions')
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return [
            'page' => $users->currentPage(),
            'total_pages' => $users->lastPage(),
            'total_users' => $users->total(),
            'count' => $users->count(),
            'links' => [
                'next_url' => $users->nextPageUrl(),
                'prev_url' => $users->previousPageUrl(),
            ],
            'users' => UsersResource::collection($users),
            ];
    }


    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        return [
            'user' => new UserResource($user),
        ];
    }


    public function createUser($request)
    {
        $avatar = $request->file('photo');
        $avatarPath = $this->imageProcessingService->processAvatar($avatar, 'avatars');

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'photo' => $avatarPath,
        ]);

        $user->positions()->attach($request->position_id);

        return [
            'user_id' => $user->id,
            "message" => "New user successfully registered",
        ];
    }
}
