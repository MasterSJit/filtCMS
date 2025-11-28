<?php

use EthickS\FiltCMS\Http\Controllers\BlogController;
use EthickS\FiltCMS\Http\Controllers\CategoryController;
use EthickS\FiltCMS\Http\Controllers\PageController;
use EthickS\FiltCMS\Http\Controllers\ScheduledPublishController;
use Illuminate\Support\Facades\Route;

Route::get('/__{slug}__', [PageController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('filtcms.page.show');

// Blog routes
Route::get('/blogs', [BlogController::class, 'index'])
    ->name('filtcms.blog.index');

Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('filtcms.blog.show');

// Category routes
Route::get('/categories', [CategoryController::class, 'index'])
    ->name('filtcms.category.index');

Route::get('/category/{slug}', [CategoryController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('filtcms.category.show');

// Scheduled publish trigger (can be called via URL)
Route::get('/publish-scheduled-content', [ScheduledPublishController::class, 'publish'])
    ->name('filtcms.publish.scheduled');
