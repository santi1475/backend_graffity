<?php
use App\Models\Company;
// Controllers
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\User\userController;
use App\Http\Controllers\Client\CompanyController;
use App\Http\Controllers\Product\CategorieController;
use App\Http\Controllers\Product\ProductController;

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

    Route::post("categories/{id}",[CategorieController::class,"update"]);
    Route::resource("categories",CategorieController::class);

    Route::resource("company",CompanyController::class);

    Route::resource("products",ProductController::class);
});
