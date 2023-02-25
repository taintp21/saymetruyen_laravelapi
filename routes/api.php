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


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Private
$privateRoutes = ['store', 'update', 'destroy'];
Route::delete('the-loai/{the_loai}', [CategoryController::class, 'delete'])->name('the-loai.delete');
Route::delete('truyen-tranh/{truyen_tranh}', [ComicController::class, 'delete'])->name('truyen-tranh.delete');
Route::delete('tin-tuc/{tin_tuc}', [PostController::class, 'delete'])->name('tin-tuc.delete');

//Public
Route::apiResources([
    'the-loai' => CategoryController::class,
    'truyen-tranh' => ComicController::class,
    'tin-tuc' => PostController::class,
], ['except' => $privateRoutes]);

//Will develop later
Route::apiResources([
    'the-loai' => CategoryController::class,
    'truyen-tranh' => ComicController::class,
    'tin-tuc' => PostController::class,
], ['only' => $privateRoutes]);
