<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ComicController;
use App\Http\Controllers\API\PostController;

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

//Private
$privateRoutes = ['store', 'update', 'destroy'];
Route::delete('the-loai/{the_loai}', [CategoryController::class, 'delete'])->name('the-loai.delete');
Route::delete('truyen-tranh/{truyen_tranh}', [ComicController::class, 'delete'])->name('truyen-tranh.delete');
Route::delete('tin-tuc/{tin_tuc}', [PostController::class, 'delete'])->name('tin-tuc.delete');

//Will develop later
Route::apiResource('the-loai', CategoryController::class)->only($privateRoutes);
Route::apiResource('truyen-tranh', ComicController::class)->only($privateRoutes);
Route::apiResource('tin-tuc', PostController::class)->only($privateRoutes);

//Public
Route::apiResource('the-loai', CategoryController::class)->except($privateRoutes);
Route::apiResource('truyen-tranh', ComicController::class)->except($privateRoutes);
Route::apiResource('tin-tuc', PostController::class)->except($privateRoutes);

