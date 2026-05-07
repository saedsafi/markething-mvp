<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/first-login', function () {
    return view('auth.first-login');
});

Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});

Route::get('/agency/dashboard', function () {
    return view('agency.dashboard');
});