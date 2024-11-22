<?php
namespace App\Services\Api\User;

use App\Models\Base\User;
use Illuminate\Support\Facades\Storage;

class Service
{

    protected $imageProcessingService;

    public function __construct(ImageProcessingService $imageProcessingService)
    {
        $this->imageProcessingService = $imageProcessingService;
    }


    public function getAllUsers()
    {
        $users = User::paginate(6);
        return [
          'users' => $users,
        ];
    }


    public function getUserById($id)
    {
        return User::findOrFail($id);
    }


    public function createUser($request)
    {
        $avatar = $request->file('profile_image');
        $avatarPath = $this->imageProcessingService->processAvatar($avatar, 'avatars');

        $user = User::create([
            'uuid' => $request->uuid,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'profile_image' => $avatarPath,
        ]);

        $token = $user->createToken($user->uuid)->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
