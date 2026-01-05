<?php
use Illuminate\Support\Facades\Route;
// Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\userController;

Route::group([
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/me', [AuthController::class, 'me'])
    ->middleware('auth:api')
    ->name('me');
});

Route::group([
    'middleware' => 'auth:api'
], function ($router) {
    Route::resource("roles",RoleController::class);
    Route::post("users/{id}",[userController::class,"update"]);
    Route::resource("users",userController::class);

});
