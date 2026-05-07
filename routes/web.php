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

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/change-password', function () {
    return view('auth.change-password');
});

Route::get('/suspended', function () {
    return view('auth.suspended');
});

Route::get('/agency/clients', function () {
    return view('agency.clients.index');
});

Route::get('/agency/clients/create', function () {
    return view('agency.clients.create');
});

Route::get('/agency/clients/show', function () {
    return view('agency.clients.show');
});

Route::get('/agency/campaigns/create', function () {
    return view('agency.campaigns.create');
});

Route::get('/agency/campaigns/show', function () {
    return view('agency.campaigns.show');
});

Route::get('/admin/users/show', function () {
    return view('admin.users.show');
});

Route::get('/admin/prompts', function () {
    return view('admin.prompts.index');
});

Route::get('/admin/settings', function () {
    return view('admin.settings.index');
});

Route::get('/admin/logs', function () {
    return view('admin.logs.index');
});

Route::get('/admin/usage', function () {
    return view('admin.usage.index');
});