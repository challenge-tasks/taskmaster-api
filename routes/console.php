<?php

use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('encode-images-to-webp', function () {
    \App\Models\Task::query()
        ->with('images')
        ->chunkById(300, function ($tasks) {
            foreach ($tasks as $task) {
                $images = $task->images->pluck('image')->push($task->image)->toArray();

                foreach ($images as $image) {
                    if (! file_exists(public_path('uploads/' . $image))) {
                        continue;
                    }

                    $originalImageFullPath = public_path('uploads/' . $image);
                    $originalImageExtension = pathinfo($image, PATHINFO_EXTENSION);

                    $encodedImageFullPath = str_replace($originalImageExtension, 'webp', $image);
                    $encodedImageFullPath = public_path('uploads/' . $encodedImageFullPath);

                    \Intervention\Image\Facades\Image::make($originalImageFullPath)
                        ->encode('webp')
                        ->save($encodedImageFullPath);
                }
            }
        });
});

Artisan::command('change-images-extension-in-db', function () {
    \App\Models\Task::query()
        ->chunkById(300, function ($tasks) {
            foreach ($tasks as $task) {
                $originalImageExtension = pathinfo($task->image, PATHINFO_EXTENSION);

                $task->image = str_replace($originalImageExtension, 'webp', $task->image);
                $task->save();
            }
        });

    \App\Models\TaskImage::query()
        ->chunkById(300, function ($tasks) {
            foreach ($tasks as $task) {
                $originalImageExtension = pathinfo($task->image, PATHINFO_EXTENSION);

                $task->image = str_replace($originalImageExtension, 'webp', $task->image);
                $task->save();
            }
        });
});
