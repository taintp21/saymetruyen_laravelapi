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

Route::apiResource('the-loai', CategoryController::class);
Route::delete('the-loai/{the_loai}/delete', [CategoryController::class, 'delete'])->name('the-loai.delete');
Route::get('the-loai/thung-rac', [CategoryController::class, 'trashed'])->name('the-loai.trashed');

Route::apiResource('truyen-tranh', ComicController::class);
Route::delete('truyen-tranh/{truyen_tranh}/delete', [ComicController::class, 'delete'])->name('truyen-tranh.delete');
Route::get('truyen-tranh/thung-rac', [ComicController::class, 'trashed'])->name('truyen-tranh.trashed');

Route::apiResource('tin-tuc', PostController::class);
Route::delete('tin-tuc/{tin_tuc}/delete', [PostController::class, 'delete'])->name('tin-tuc.delete');
Route::get('tin-tuc/thung-rac', [PostController::class, 'trashed'])->name('tin-tuc.trashed');

