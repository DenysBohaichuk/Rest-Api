<?php
namespace App\Services\Api\User;

use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use App\Helpers\Images\ImageManager as ImageHelper;

class ImageProcessingService
{
    protected static $TYNIFY_API_KEY;
    protected ImageManager $imageManager;
    protected ImageHelper $imageHelper;

    public function __construct(ImageManager $imageManager, ImageHelper $imageHelper)
    {
        self::$TYNIFY_API_KEY = env('TINYPNG_API_KEY');

        $this->imageManager = $imageManager;
        $this->imageHelper = $imageHelper;
    }

    /**
     * Основний метод для обробки аватара.
     */
    public function processAvatar(UploadedFile $file, string $storagePath, $width = 70, $height = 70, $quality = 90): string
    {
        // Змінюємо розмір і якість зображення
        $resizedImage = $this->resizeImage($file, $width, $height, $quality);

        // Оптимізуємо зображення через TinyPNG
        $optimizedImage = $this->optimizeImageWithTinyPng($resizedImage);

        // Зберігаємо зображення у сховище
        $path = $this->saveImageToStorage($optimizedImage, $storagePath);

        return $path;
    }

    /**
     * Змінює розмір зображення та повертає буфер зображення.
     */
    private function resizeImage(UploadedFile $file, int $width, int $height, int $quality): string
    {
        $image = $this->imageManager->read($file->getPathname());

        $image->resize($width, $height);

        $image->crop($width, $height);

        // Використовуємо JPEG-енкодер для отримання буфера
        return (string) $image->toJpeg($quality);
    }

    /**
     * Оптимізує зображення через TinyPNG і повертає буфер оптимізованого зображення.
     */
    private function optimizeImageWithTinyPng(string $imageBuffer): string
    {
        \Tinify\setKey(self::$TYNIFY_API_KEY);

        return \Tinify\fromBuffer($imageBuffer)->toBuffer();
    }

    /**
     * Зберігає зображення у сховище та повертає шлях до файлу.
     */
    private function saveImageToStorage(string $optimizedImage, string $storagePath): string
    {
        $projectTempDir = storage_path('app/temp');

        if (!is_dir($projectTempDir)) {
            mkdir($projectTempDir, 0755, true);
        }

        // Створюємо шлях для тимчасового файлу
        $tempFilePath = $projectTempDir . '/' . uniqid() . '.jpg';

        $image = $this->imageManager->read($optimizedImage);

        $image->save($tempFilePath);

        // Створюємо об'єкт UploadedFile
        $optimizedFile = new UploadedFile(
            $tempFilePath, // Шлях до тимчасового файлу
            basename($tempFilePath), // Ім'я файлу
            mime_content_type($tempFilePath), // MIME-тип файлу
            null, // Розмір (визначається автоматично)
            true // Це тестовий файл
        );

        $path = $this->imageHelper->uploadImage($optimizedFile, $storagePath);

        // Видаляємо тимчасовий файл після збереження
        unlink($tempFilePath);

        return $path;
    }
}
