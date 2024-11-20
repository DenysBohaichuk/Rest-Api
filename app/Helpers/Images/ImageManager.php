<?php

namespace App\Helpers\Images;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ImageManager
{


    /**
     * Загрузка нових зображень та видалення старих
     *
     * @param array $existingImages
     * @param Collection $newImages
     * @param string $storagePath
     * @return array
     */
    public function updateImages(array $existingImages, Collection $newImages, string $storagePath)
    {
        // Завантажуємо нові зображення
        $newImagePaths = $this->uploadImages($newImages, $storagePath);

        // Отримуємо список існуючих зображень, які не прийшли в запиті
        $imagesToDelete = collect($existingImages)
            ->filter(function ($existingImage) use ($newImagePaths) {
                return !in_array($existingImage, $newImagePaths);
            })
            ->toArray();

        // Видаляємо непотрібні зображення
        $this->deleteImages($imagesToDelete);

        // Повертаємо список шляхів для всіх зображень продукту (старі + нові)
        return $newImagePaths;
    }


    /**
     * Зберігає декілька зображення і повертає шляхи до них.
     *
     * @param Collection $images
     * @param string $storagePath
     * @return array
     */
    public function uploadImages(Collection $images, string $storagePath): array
    {
        return collect($images)->map(function ($image) use ($storagePath) {
            return $this->uploadImage($image, $storagePath);
        })->toArray();
    }


    /**
     * Зберігає одне зображення і повертає шляхи до них.
     *
     * @param UploadedFile $image
     * @param string $storagePath
     * @return false|string
     */
    public function uploadImage(UploadedFile $image, string $storagePath): bool|string
    {
        $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $path = config($storagePath);
        $path = empty($path) ? $storagePath : $path;
        return $image->storeAs($path, $filename, 'public');
    }


    /**
     * Метод для видалення зображень
     *
     * @param array $imagePaths
     * @return void
     */
    public function deleteImages(array $imagePaths)
    {
        collect($imagePaths)->each(function ($path) {
            $this->deleteImage($path);
        });
    }

    /**
     * @param string $path
     * @return void
     */
    public function deleteImage(string $path): void
    {
        Storage::disk('public')->delete($path);
    }
}
