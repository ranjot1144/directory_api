<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\AdminController;
use Illuminate\Routing\Middleware\ThrottleRequests;


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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/logout', [UserController::class,'logout'])->middleware('auth:sanctum');
Route::post('/submit-website', [WebsiteController::class, 'store'])->middleware('auth');

//Route::get('/websites', [WebsiteController::class, 'index'])->middleware('auth');

// Apply rate limiting middleware to specific route(s)
Route::middleware(['api', 'throttle:60,1'])->group(function () {
    Route::get('/websites', 'WebsiteController@index');
});

Route::get('/websites/{id}', [WebsiteController::class, 'show'])->middleware('auth');
Route::post('/websites/{id}/vote', [WebsiteController::class, 'vote'])->middleware('auth');

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/websites/approve/{id}', [AdminController::class, 'approveWebsite']);
    Route::delete('/websites/remove/{id}', [AdminController::class, 'removeWebsite']);
});
