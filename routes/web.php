<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RestTestController;
use App\Http\Controllers\Blog\PostController;
use App\Http\Controllers\Blog\Admin\PostController as AdminPostController;
use App\Http\Controllers\Blog\Admin\CategoryController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::resource('rest', RestTestController::class)->names('restTest');

Route::group([
    'namespace' => 'App\Http\Controllers\Blog',
    'prefix' => 'blog'
], function () {
    Route::resource('posts', PostController::class)->names('blog.posts');
});


// Адмінка
$groupData = [
    'namespace' => 'App\Http\Controllers\Blog\Admin',
    'prefix' => 'admin/blog',
];
Route::group($groupData, function () {
    Route::resource('posts', AdminPostController::class)
        ->except(['show'])
        ->names('blog.admin.posts');
    Route::resource('categories', CategoryController::class)
        ->only(['index','edit','store','update','create'])
        ->names('blog.admin.categories');
});
