<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService
{
    public function uploadAsWebp(UploadedFile $file, string $folder): string
    {
        $originalName = $file->getFilename();
        $originalExtension = $file->getClientOriginalExtension();

        $encodedName = str_replace($originalExtension, 'webp', $originalName);
        $encodedImage = Image::make($file)->encode('webp');

        Storage::put($folder . '/' . $encodedName, $encodedImage->getEncoded());

        return $folder . '/' . $encodedName;
    }
}
