<?php

use Illuminate\Support\Facades\Route;

Route::get('deploy', function () {
    \Illuminate\Support\Facades\Artisan::call('migrate');
    \Illuminate\Support\Facades\Artisan::call('db:seed');
    \Illuminate\Support\Facades\Artisan::call('db:seed FakeSeeder');
});
