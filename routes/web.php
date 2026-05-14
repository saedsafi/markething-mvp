<?php

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Agency\AiAssistController;
use App\Http\Controllers\Admin\LlmLogController;
use App\Http\Controllers\Admin\PromptController;
use App\Http\Controllers\Agency\CampaignController;
use App\Http\Controllers\Agency\ClientController;
use App\Http\Controllers\Agency\DashboardController;
use App\Http\Controllers\Agency\PersonaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\FirstLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\PasswordController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Login
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (auth()->check()) {

        if (auth()->user()->isFounder()) {
            return redirect('/admin/dashboard');
        }

        return redirect('/agency/dashboard');
    }

    return app(LoginController::class)->show();
});

Route::get('/login', function () {

    if (auth()->check()) {

        if (auth()->user()->isFounder()) {
            return redirect('/admin/dashboard');
        }

        return redirect('/agency/dashboard');
    }

    return app(LoginController::class)->show();

})->name('login');

Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest')
    ->name('login.store');

Route::get('/suspended', function () {
    return view('auth.suspended');
})->name('suspended');

/*
|--------------------------------------------------------------------------
| Authenticated
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'active'])->group(function () {

    Route::get('/first-login', [FirstLoginController::class, 'show'])
        ->name('first-login');

    Route::post('/first-login', [FirstLoginController::class, 'update'])
        ->name('first-login.update');

    Route::post('/logout', LogoutController::class)
        ->name('logout');
});

Route::middleware(['auth', 'active', 'password.changed'])->group(function () {

    Route::get('/change-password', [PasswordController::class, 'show'])
        ->name('password.change');

    Route::post('/change-password', [PasswordController::class, 'update'])
        ->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'active',
    'password.changed',
    'founder'
])->prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard + Users
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [UserController::class, 'index'])
        ->name('admin.dashboard');

    Route::post('/users', [UserController::class, 'store'])
        ->name('admin.users.store');

    Route::get('/users/{user}', [UserController::class, 'show'])
        ->name('admin.users.show');

    Route::patch('/users/{user}', [UserController::class, 'update'])
        ->name('admin.users.update');

    Route::patch('/users/{user}/temporary-password', [UserController::class, 'issueTemporaryPassword'])
        ->name('admin.users.temporary-password');

    Route::patch('/users/{user}/suspend', [UserController::class, 'suspend'])
        ->name('admin.users.suspend');

    Route::patch('/users/{user}/reactivate', [UserController::class, 'reactivate'])
        ->name('admin.users.reactivate');

    /*
    |--------------------------------------------------------------------------
    | Prompt Editor
    |--------------------------------------------------------------------------
    */

    Route::get('/prompts', [\App\Http\Controllers\Admin\PromptController::class, 'index'])
        ->name('admin.prompts.index');

    Route::post('/prompts/versions', [\App\Http\Controllers\Admin\PromptController::class, 'storeVersion'])
        ->name('admin.prompts.versions.store');

    Route::patch('/prompts/versions/{version}/activate', [\App\Http\Controllers\Admin\PromptController::class, 'activateVersion'])
        ->name('admin.prompts.versions.activate');

    Route::post('/prompts/test', [\App\Http\Controllers\Admin\PromptController::class, 'test'])
        ->name('admin.prompts.test');

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/settings',
        [SettingController::class, 'index']
    )->name('admin.settings.index');
    
    Route::patch(
        '/settings',
        [SettingController::class, 'update']
    )->name('admin.settings.update');

    /*
    |--------------------------------------------------------------------------
    | LLM Logs
    |--------------------------------------------------------------------------
    */

    Route::get('/logs', [\App\Http\Controllers\Admin\LlmLogController::class, 'index'])
    ->name('admin.logs.index');

});

/*
|--------------------------------------------------------------------------
| Agency Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'active',
    'password.changed',
    'agency'
])->prefix('agency')->group(function () {

    Route::get('/dashboard', DashboardController::class)
    ->name('agency.dashboard');

    /*
|--------------------------------------------------------------------------
| Clients
|--------------------------------------------------------------------------
*/

Route::get('/clients', [\App\Http\Controllers\Agency\ClientController::class, 'index'])
->name('agency.clients.index');

Route::get('/clients/create', [\App\Http\Controllers\Agency\ClientController::class, 'create'])
->name('agency.clients.create');

Route::post('/clients', [\App\Http\Controllers\Agency\ClientController::class, 'store'])
->name('agency.clients.store');

Route::get('/clients/{client}', [\App\Http\Controllers\Agency\ClientController::class, 'show'])
->name('agency.clients.show');

Route::get('/clients/{client}/edit', [\App\Http\Controllers\Agency\ClientController::class, 'edit'])
->name('agency.clients.edit');

Route::patch('/clients/{client}', [\App\Http\Controllers\Agency\ClientController::class, 'update'])
->name('agency.clients.update');

Route::patch('/clients/{client}/deactivate', [\App\Http\Controllers\Agency\ClientController::class, 'deactivate'])
->name('agency.clients.deactivate');

Route::patch('/clients/{client}/reactivate', [\App\Http\Controllers\Agency\ClientController::class, 'reactivate'])
->name('agency.clients.reactivate');

/*
|--------------------------------------------------------------------------
| Personas
|--------------------------------------------------------------------------
*/

Route::post('/clients/{client}/personas', [\App\Http\Controllers\Agency\PersonaController::class, 'store'])
->name('agency.personas.store');

Route::patch('/personas/{persona}', [\App\Http\Controllers\Agency\PersonaController::class, 'update'])
->name('agency.personas.update');

Route::patch('/personas/{persona}/deactivate', [\App\Http\Controllers\Agency\PersonaController::class, 'deactivate'])
->name('agency.personas.deactivate');

Route::patch('/personas/{persona}/reactivate', [\App\Http\Controllers\Agency\PersonaController::class, 'reactivate'])
->name('agency.personas.reactivate');
    /*
    |--------------------------------------------------------------------------
    | Campaigns
    |--------------------------------------------------------------------------
    */
    Route::get('/campaigns/create', [\App\Http\Controllers\Agency\CampaignController::class, 'create'])
    ->name('agency.campaigns.create');

Route::post('/campaigns', [\App\Http\Controllers\Agency\CampaignController::class, 'store'])
    ->name('agency.campaigns.store');

Route::get('/campaigns/{campaign}', [\App\Http\Controllers\Agency\CampaignController::class, 'show'])
    ->name('agency.campaigns.show');
   /*
    |--------------------------------------------------------------------------
    | AI Assist
    |--------------------------------------------------------------------------
    */

    Route::post('/ai-assist', \App\Http\Controllers\Agency\AiAssistController::class)
    ->name('agency.ai-assist');
   
});