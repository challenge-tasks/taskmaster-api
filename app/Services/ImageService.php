<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image;

class ImageService
{
    const QUALITY = 85;

    public function uploadAsWebp(UploadedFile $file, string $folder): string
    {
        $originalName = $file->getFilename();
        $originalExtension = $file->getClientOriginalExtension();

        $encodedName = str_replace($originalExtension, 'webp', $originalName);
        $encodedImage = Image::make($file)->encode('webp', self::QUALITY);

        Storage::put($folder . '/' . $encodedName, $encodedImage->getEncoded());

        return $folder . '/' . $encodedName;
    }

    public function resizeImage(string $originalImagePath, int $width, int $height): ?string
    {
        if (! $originalImagePath || str_starts_with($originalImagePath, 'http')) {
            return null;
        }

        $folder = $width . 'x' . $height;
        $resizedImagePath = $folder . '/' . $originalImagePath;

        if (Storage::disk('public_uploads')->exists($resizedImagePath)) {
            return $resizedImagePath;
        } else if (Storage::disk('public_uploads')->exists($originalImagePath)) {
            $originalImage = Storage::disk('public_uploads')->get($originalImagePath);

            Storage::disk('public_uploads')->put($resizedImagePath, $originalImage);

            $resizedImageFullPath = public_path('uploads/' . $resizedImagePath);

            Image::make($resizedImageFullPath)
                ->resize($width, $height, function (Constraint $constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($resizedImageFullPath, self::QUALITY);

            return $resizedImagePath;
        }

        return null;
    }
}
