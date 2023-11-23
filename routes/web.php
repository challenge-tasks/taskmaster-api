<?php

use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    throw new Exception('test');
});

Route::redirect('/', '/admin');
