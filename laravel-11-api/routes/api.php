<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/all/posts', [PostContoller::class, 'getAllPosts']);
Route::get('/single/post/{post_id}', [PostContoller::class, 'getPost']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    //blog api endpoints start here!!
    Route::post('/add/post', [PostContoller::class, 'addNewPost']);
    Route::post('/edit/post', [PostContoller::class, 'editPost']);
    Route::post('/edit/post/{post_id}', [PostContoller::class, 'editPost2']);
    Route::post('/delete/post/{post_id}', [PostContoller::class, 'deletePost']);
    Route::post('/comment', [CommentController::class, 'postComment']);
});
