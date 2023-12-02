<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ImageService;

class ImageController extends Controller
{
    /**
     * @OA\Get(
     *     path="/uploads/resize/{image}/{size}",
     *     tags={"Изображения"},
     *     summary="Сжатие изображения",
     *     @OA\Parameter(
     *          description="Изображеие",
     *          in="path",
     *          name="image",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="tasks/OnqsRZuQ3XyyJVYIiR9pwU4H4ic0aK-metaMS5qcGc=-.jpg", value="tasks/OnqsRZuQ3XyyJVYIiR9pwU4H4ic0aK-metaMS5qcGc=-.jpg", summary="tasks/OnqsRZuQ3XyyJVYIiR9pwU4H4ic0aK-metaMS5qcGc=-.jpg"),
     *     ),
     *     @OA\Parameter(
     *          description="Размеры",
     *          in="path",
     *          name="size",
     *          required=true,
     *          @OA\Schema(type="string"),
     *          @OA\Examples(example="500x500", value="500x500", summary="500x500"),
     *     ),
     *     @OA\Response(response="200", description="Успешно"),
     *     @OA\Response(response="404", description="Не найдено")
     * )
     */
    public function resize(string $folder, string $image, string $size)
    {
        $availableFolders = [
            'tasks',
            'tasks/additional',
        ];

        $originalImagePath = $folder . '/' . $image;
        $originalImageFullPath = public_path('uploads/' . $originalImagePath);

        if (! file_exists($originalImageFullPath)) {
            abort(404);
        }

        if (! in_array($folder, $availableFolders) || ! $size) {
            return response()->file($originalImageFullPath);
        }

        $size = explode('x', $size);
        $width = $size[0] ?? null;
        $height = $size[1] ?? null;

        if (is_null($width) || is_null($height)) {
            return response()->file($originalImageFullPath);
        }

        $resizedImagePath = (new ImageService())->resizeImage($originalImagePath, $width, $height);
        $resizedImageFullPath = public_path('uploads/' . $resizedImagePath);

        if (! $resizedImagePath) {
            return response()->file($originalImageFullPath);
        }

        return response()->file($resizedImageFullPath);
    }
}
