<?php

use App\Http\Controllers\Api\V1\ImageController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::get('uploads/resize/{folder}/{image}/{size}', [ImageController::class, 'resize'])
    ->where('image', '.*')
    ->where('size', '[0-9]+x[0-9]+');
