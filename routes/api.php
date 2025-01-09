<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;
use L5Swagger\Http\Controllers\SwaggerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('organizations')->group(function () {
    Route::get('/', [OrganizationController::class, 'index']);  // Список всех организаций
    Route::get('/{id}', [OrganizationController::class, 'show']); // Информация об организации
    Route::get('building/{buildingId}', [OrganizationController::class, 'byBuilding']); // Организации в здании
    Route::get('activity/{activityId}', [OrganizationController::class, 'byActivity']); // Организации по виду деятельности
    Route::get('search/{name}', [OrganizationController::class, 'search']); // Поиск по названию
    Route::get('searchActivityName/{activityName}', [OrganizationController::class, 'byActivityName']);  // Поиск по названию деятельности
    Route::get('radius/show', [OrganizationController::class, 'byRadius']);  // Поиск по названию деятельности
});

Route::get('/api/documentation', [SwaggerController::class, 'api']);
