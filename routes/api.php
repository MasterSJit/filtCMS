<?php

use EthickS\FiltCMS\Http\Controllers\Api\BlogApiController;
use EthickS\FiltCMS\Http\Controllers\Api\CategoryApiController;
use EthickS\FiltCMS\Http\Controllers\Api\CommentApiController;
use EthickS\FiltCMS\Http\Controllers\Api\PageApiController;
use Illuminate\Support\Facades\Route;

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

// FiltCMS API - Read Only
Route::prefix('filtcms')->name('api.filtcms.')->group(function () {
    // Blog Routes
    Route::prefix('blogs')->name('blogs.')->group(function () {
        Route::get('/', [BlogApiController::class, 'index'])->name('index');
        Route::get('/{slug}', [BlogApiController::class, 'show'])->name('show');
    });

    // Page Routes
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('/', [PageApiController::class, 'index'])->name('index');
        Route::get('/{slug}', [PageApiController::class, 'show'])->name('show');
    });

    // Category Routes
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryApiController::class, 'index'])->name('index');
        Route::get('/{slug}', [CategoryApiController::class, 'show'])->name('show');
    });

    // Comment Routes
    Route::prefix('comments')->name('comments.')->group(function () {
        Route::get('/', [CommentApiController::class, 'index'])->name('index');
        Route::get('/{id}', [CommentApiController::class, 'show'])->name('show');
    });
});
