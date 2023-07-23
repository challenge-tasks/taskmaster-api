<?php

use Illuminate\Support\Facades\Route;

Route::get('deploy', function () {
    \Illuminate\Support\Facades\Artisan::call('passport:install');
    \Illuminate\Support\Facades\Artisan::call('passport:client --personal');
});
