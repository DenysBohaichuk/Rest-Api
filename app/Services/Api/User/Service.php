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
        return User::paginate(6);
    }


    public function getUserById($id)
    {
        return User::findOrFail($id);
    }


    public function createUser($request)
    {
        $avatar = $request->file('profile_image');

        $avatarPath = $this->imageProcessingService->processAvatar($avatar, 'avatars');

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'profile_image' => $avatarPath,
        ]);
    }
}
