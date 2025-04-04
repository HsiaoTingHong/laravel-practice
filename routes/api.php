<?php
// api.php 設定路由

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalController;
use App\Http\Controllers\TypeController; // 下方設定路由時這邊會自動新增

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// apiResource 為 laravel 內建的方法，將路由網址設定為 animals 字串，對應到 AnimalController 內相對應的方法
Route::apiResource('animals', AnimalController::class);
Route::apiResource('types', TypeController::class);
