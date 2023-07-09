<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    dd(\App\Enums\RoleEnum::cases());
});
